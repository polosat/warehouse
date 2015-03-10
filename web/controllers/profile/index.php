<?php
require_once __DIR__ . '/../../views/profile/show/view.php';
require_once __DIR__ . '/../../views/profile/edit/view.php';
require_once __DIR__ . '/../../models/profile/model.php';
require_once __DIR__ . '/callback.php';

class ProfileController extends Controller {
  const ACTION_EDIT = 'edit';
  const ACTION_NEW  = 'new';

  protected function Action_Index() {
    $context = $this->context;
    $request = $context->request;
    $session = $context->session;

    $viewBag = new ShowProfileViewBag();
    $view = new ShowProfileView($viewBag, $request->language);
    $this->InitializeLayoutView($view);
    $viewBag->EditUri = Request::CreateUri($request->language, Application::PROFILE_CONTROLLER, self::ACTION_EDIT);
    $strings = $view->ProfileStrings;

    $callback = $this->UnserializeCallback();

    if ($callback instanceof ShowProfileCallback) {
      if ($callback->reason == ShowProfileCallback::REASON_PROFILE_CHANGED) {
        $viewBag->messageBox = new MessageBox($strings::MESSAGE_PROFILE_CHANGED, MessageBox::TYPE_INFO);
      }
      else {
        throw new LogicException('Unexpected redirect reason.');
      }
    }

    $model = new ProfileModel($context);
    $userID = $session->UserID();
    $user = $model->GetUser($userID);

    if (empty($user)) {
      $callback = new LoginCallback(LoginCallback::REASON_UNKNOWN_USER_ID);
      return $this->CreateCallbackRequest($callback, Application::LOGIN_CONTROLLER);
    }

    $viewBag->User = $user;
    $view->Render();
    return null;
  }

  protected function Action_Edit() {
    $context = $this->context;
    $request = $context->request;
    $session = $context->session;

    $viewBag = new EditProfileViewBag();
    $view = new EditProfileView($viewBag, $request->language);
    $this->InitializeLayoutView($view);
    $viewBag->CancelUri = Request::CreateUri($request->language, Application::PROFILE_CONTROLLER);
    $strings = $view->ProfileStrings;

    $callback = $this->UnserializeCallback();

    if ($callback instanceof EditProfileCallback) {
      if ($callback->reason != EditProfileCallback::REASON_VALIDATION_ERROR)
        throw new LogicException('Unexpected redirect reason.');

      if (isset($callback->OperationResult->Errors[ProfileOperationResult::ERROR_DUPLICATED_RECORD])) {
        $viewBag->messageBox = new MessageBox($strings::ERROR_NOT_CHANGED);
      }
      else {
        $this->PopulateValidationErrors($view, $callback->OperationResult);
      }

      $viewBag->User = $callback->User;
    }
    else {
      $model = new ProfileModel($context);
      $userID = $session->UserID();
      $user = $model->GetUser($userID);

      if (empty($user)) {
        $callback = new LoginCallback(LoginCallback::REASON_UNKNOWN_USER_ID);
        return $this->CreateCallbackRequest($callback, Application::LOGIN_CONTROLLER);
      }

      $viewBag->User = $user;
    }

    $view->Render();
    return null;
  }

  // Update an existing user profile
  protected function ActionPost_Edit() {
    $context = $this->context;
    $session = $context->session;

    $changeRequest = $this->ParseProfileForm();
    $changeRequest->User->UserID = $session->UserID();

    $profileModel = new ProfileModel($context);
    $operationResult = $profileModel->UpdateUser($changeRequest);

    if ($operationResult->Succeeded()) {
      $callback = new ShowProfileCallback(ShowProfileCallback::REASON_PROFILE_CHANGED);
      return $this->CreateCallbackRequest($callback, Application::PROFILE_CONTROLLER);
    }

    if (isset($operationResult->Errors[ProfileOperationResult::ERROR_UNKNOWN_USER_ID])) {
      $callback = new LoginCallback(LoginCallback::REASON_UNKNOWN_USER_ID);
      return $this->CreateCallbackRequest($callback, Application::LOGIN_CONTROLLER);
    }

    $callback = new EditProfileCallback(EditProfileCallback::REASON_VALIDATION_ERROR);
    $callback->User = $changeRequest->User;
    $callback->OperationResult = $operationResult;
    return $this->CreateCallbackRequest($callback, Application::PROFILE_CONTROLLER, self::ACTION_EDIT);
  }

  // Show create new user form
  protected function Action_New() {
    $context = $this->context;
    $request = $context->request;
    $session = $context->session;

    $viewBag = new EditProfileViewBag();
    $viewBag->User = new UserEntity();
    $view = new EditProfileView($viewBag, $request->language);
    $this->InitializeLayoutView($view);
    $viewBag->CancelUri = Request::CreateUri($request->language, Application::PROFILE_CONTROLLER);

    $callback = $this->UnserializeCallback();

    if ($callback instanceof EditProfileCallback) {
      if ($callback->reason != EditProfileCallback::REASON_VALIDATION_ERROR)
        throw new LogicException('Unexpected redirect reason.');

      $this->PopulateValidationErrors($view, $callback->OperationResult);
      $viewBag->User = $callback->User;
    }
    else if ($session->IsAuthenticated()) {
      // An authenticated user shouldn't be able to see this page
      return new Request($request->language);
    }

    $view->Render();
    return null;
  }

  protected function ActionPost_New() {
    $context = $this->context;
    $request = $context->request;
    $session = $context->session;
    /** @var EditProfileViewStrings $strings */
    $strings = EditProfileViewStrings::GetInstance($request->language);

    if ($session->IsAuthenticated()) {
      // An authenticated user shouldn't be able creating new profiles
      return new Request($request->language);
    }

    $changeRequest = $this->ParseProfileForm();

    $profileModel = new ProfileModel($context);
    $operationResult = $profileModel->CreateUser($changeRequest);

    if ($operationResult->Succeeded()) {
      $callback = new LoginCallback(LoginCallback::REASON_SHOW_MESSAGE);
      $callback->messageBox = new MessageBox($strings::MESSAGE_NEW_OK, MessageBox::TYPE_INFO);
      return $this->CreateCallbackRequest($callback, Application::LOGIN_CONTROLLER);
    }

    $callback = new EditProfileCallback(EditProfileCallback::REASON_VALIDATION_ERROR);
    $callback->User = $changeRequest->User;
    $callback->OperationResult = $operationResult;
    return $this->CreateCallbackRequest($callback, Application::PROFILE_CONTROLLER, self::ACTION_NEW);
  }

  protected function ParseProfileForm() {
    $request = $this->context->request;
    $user = new UserEntity();
    $user->FirstName = $request->GetPostedValue(EditProfileView::FIELD_NAME_FIRST_NAME);
    $user->LastName = $request->GetPostedValue(EditProfileView::FIELD_NAME_LAST_NAME);
    $user->Login = $request->GetPostedValue(EditProfileView::FIELD_NAME_LOGIN);
    $user->Password = $request->GetPostedValue(EditProfileView::FIELD_NAME_PASSWORD);
    $user->Birthday = $request->GetPostedValue(EditProfileView::FIELD_NAME_BIRTHDAY);
    $user->EMail = $request->GetPostedValue(EditProfileView::FIELD_NAME_EMAIL);
    $user->Phone = $request->GetPostedValue(EditProfileView::FIELD_NAME_PHONE);

    $changeRequest = new ProfileChangeRequest($user);
    $changeRequest->PasswordConfirmation = $request->GetPostedValue(EditProfileView::FIELD_NAME_PASSWORD_CONFIRM);
    $changeRequest->CurrentPassword = $request->GetPostedValue(EditProfileView::FIELD_NAME_PASSWORD_CURRENT);

    return $changeRequest;
  }

  protected function PopulateValidationErrors(EditProfileView $view, ProfileOperationResult $operationResult) {
    /** @var EditProfileViewBag $viewBag */
    $viewBag = $view->Bag;
    $strings = $view->ProfileStrings;

    if (isset($operationResult->Errors[ProfileOperationResult::ERROR_DUPLICATED_RECORD])) {
      $viewBag->messageBox = new MessageBox($strings::ERROR_NOT_CHANGED);
    }
    else {
      foreach ($operationResult->Errors as $error) {
        switch ($error) {
          case ProfileOperationResult::ERROR_INVALID_LOGIN:
            $viewBag->validationErrors[EditProfileView::FIELD_NAME_LOGIN] = $strings::ERROR_LOGIN;
            break;

          case ProfileOperationResult::ERROR_LOGIN_ALREADY_EXISTS:
            $viewBag->validationErrors[EditProfileView::FIELD_NAME_LOGIN] = $strings::ERROR_LOGIN_EXISTS;
            break;

          case ProfileOperationResult::ERROR_INVALID_FIRST_NAME:
            $viewBag->validationErrors[EditProfileView::FIELD_NAME_FIRST_NAME] = $strings::ERROR_FIRST_NAME;
            break;

          case ProfileOperationResult::ERROR_INVALID_LAST_NAME:
            $viewBag->validationErrors[EditProfileView::FIELD_NAME_LAST_NAME] = $strings::ERROR_LAST_NAME;
            break;

          case ProfileOperationResult::ERROR_INVALID_PASSWORD:
            $viewBag->validationErrors[EditProfileView::FIELD_NAME_PASSWORD] = $strings::ERROR_PASSWORD_PATTERN;
            break;

          case ProfileOperationResult::ERROR_PASSWORDS_MISMATCH:
            $viewBag->validationErrors[EditProfileView::FIELD_NAME_PASSWORD_CONFIRM] = $strings::ERROR_PASSWORD_CONFIRM;
            break;

          case ProfileOperationResult::ERROR_INVALID_EMAIL:
            $viewBag->validationErrors[EditProfileView::FIELD_NAME_EMAIL] = $strings::ERROR_EMAIL_FORMAT;
            break;

          case ProfileOperationResult::ERROR_INVALID_PHONE:
            $viewBag->validationErrors[EditProfileView::FIELD_NAME_PHONE] = $strings::ERROR_PHONE_FORMAT;
            break;

          case ProfileOperationResult::ERROR_INVALID_BIRTHDAY:
            $viewBag->validationErrors[EditProfileView::FIELD_NAME_BIRTHDAY] = $strings::ERROR_BIRTHDAY;
            break;

          case ProfileOperationResult::ERROR_BAD_CURRENT_PASSWORD:
            $viewBag->validationErrors[EditProfileView::FIELD_NAME_PASSWORD_CURRENT] = $strings::ERROR_NOT_AUTHENTICATED;
            break;

          default:
            throw new LogicException('Unexpected profile validation error');
        }
      }
    }
  }

  protected function HeaderItemsMask() {
    return $this->context->session->IsAuthenticated() ?
      HeaderItem::FILES | HeaderItem::LOGOUT :
      HeaderItem::LOGIN;
  }

  protected function RequiresAuthentication()
  {
    return $this->context->request->action != 'new';
  }

  protected function AllowsExpiredSession()
  {
    return $this->context->request->action == 'new';
  }
}

