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
    $request = $context->Request;
    $session = $context->Session;

    $bag = new ShowProfileViewBag();
    $view = new ShowProfileView($bag, $request->Language);
    $this->InitializeLayoutView($view);
    $bag->EditUri = Request::CreateUri($request->Language, Application::PROFILE_CONTROLLER, self::ACTION_EDIT);
    $strings = $view->ProfileStrings;

    $callback = $this->UnserializeCallback();

    if ($callback instanceof ShowProfileCallback) {
      if ($callback->Reason == ShowProfileCallback::REASON_PROFILE_CHANGED) {
        $view->Alert = new MessageBox($strings::MESSAGE_PROFILE_CHANGED, MessageBox::TYPE_INFO);
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

    $bag->User = $user;
    $view->Render();
    return null;
  }

  protected function Action_Edit() {
    $context = $this->context;
    $request = $context->Request;
    $session = $context->Session;

    $bag = new EditProfileViewBag();
    $view = new EditProfileView($bag, $request->Language);
    $this->InitializeLayoutView($view);
    $bag->CancelUri = Request::CreateUri($request->Language, Application::PROFILE_CONTROLLER);
    $strings = $view->ProfileStrings;

    $callback = $this->UnserializeCallback();

    if ($callback instanceof EditProfileCallback) {
      if ($callback->Reason != EditProfileCallback::REASON_VALIDATION_ERROR)
        throw new LogicException('Unexpected redirect reason.');

      if (isset($callback->OperationResult->Errors[ProfileOperationResult::ERROR_DUPLICATED_RECORD])) {
        $view->Alert = new MessageBox($strings::ERROR_NOT_CHANGED);
      }
      else {
        $this->PopulateValidationErrors($view, $callback->OperationResult);
      }

      $bag->User = $callback->User;
    }
    else {
      $model = new ProfileModel($context);
      $userID = $session->UserID();
      $user = $model->GetUser($userID);

      if (empty($user)) {
        $callback = new LoginCallback(LoginCallback::REASON_UNKNOWN_USER_ID);
        return $this->CreateCallbackRequest($callback, Application::LOGIN_CONTROLLER);
      }

      $bag->User = $user;
    }

    $view->Render();
    return null;
  }

  // Update an existing user profile
  protected function ActionPost_Edit() {
    $context = $this->context;
    $session = $context->Session;

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
    $request = $context->Request;
    $session = $context->Session;

    $bag = new EditProfileViewBag();
    $bag->User = new UserEntity();
    $view = new EditProfileView($bag, $request->Language);
    $this->InitializeLayoutView($view);
    $bag->CancelUri = Request::CreateUri($request->Language, Application::PROFILE_CONTROLLER);

    $callback = $this->UnserializeCallback();

    if ($callback instanceof EditProfileCallback) {
      if ($callback->Reason != EditProfileCallback::REASON_VALIDATION_ERROR)
        throw new LogicException('Unexpected redirect reason.');

      $this->PopulateValidationErrors($view, $callback->OperationResult);
      $bag->User = $callback->User;
    }
    else if ($session->IsAuthenticated()) {
      // An authenticated user shouldn't be able to see this page
      return new Request($request->Language);
    }

    $view->Render();
    return null;
  }

  protected function ActionPost_New() {
    $context = $this->context;
    $request = $context->Request;
    $session = $context->Session;
    /** @var EditProfileViewStrings $strings */
    $strings = EditProfileViewStrings::GetInstance($request->Language);

    if ($session->IsAuthenticated()) {
      // An authenticated user shouldn't be able creating new profiles
      return new Request($request->Language);
    }

    $changeRequest = $this->ParseProfileForm();

    $profileModel = new ProfileModel($context);
    $operationResult = $profileModel->CreateUser($changeRequest);

    if ($operationResult->Succeeded()) {
      $callback = new LoginCallback(LoginCallback::REASON_SHOW_MESSAGE);
      $callback->Alert = new MessageBox($strings::MESSAGE_NEW_OK, MessageBox::TYPE_INFO);
      return $this->CreateCallbackRequest($callback, Application::LOGIN_CONTROLLER);
    }

    $callback = new EditProfileCallback(EditProfileCallback::REASON_VALIDATION_ERROR);
    $callback->User = $changeRequest->User;
    $callback->OperationResult = $operationResult;
    return $this->CreateCallbackRequest($callback, Application::PROFILE_CONTROLLER, self::ACTION_NEW);
  }

  protected function ParseProfileForm() {
    $request = $this->context->Request;
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
    $bag = $view->Bag;
    $strings = $view->ProfileStrings;

    if (isset($operationResult->Errors[ProfileOperationResult::ERROR_DUPLICATED_RECORD])) {
      $view->Alert = new MessageBox($strings::ERROR_NOT_CHANGED);
    }
    else {
      foreach ($operationResult->Errors as $error) {
        switch ($error) {
          case ProfileOperationResult::ERROR_INVALID_LOGIN:
            $bag->ValidationErrors[EditProfileView::FIELD_NAME_LOGIN] = $strings::ERROR_LOGIN;
            break;

          case ProfileOperationResult::ERROR_LOGIN_ALREADY_EXISTS:
            $bag->ValidationErrors[EditProfileView::FIELD_NAME_LOGIN] = $strings::ERROR_LOGIN_EXISTS;
            break;

          case ProfileOperationResult::ERROR_BAD_CURRENT_PASSWORD:
            $bag->ValidationErrors[EditProfileView::FIELD_NAME_PASSWORD_CURRENT] = $strings::ERROR_NOT_AUTHENTICATED;
            break;

          case ProfileOperationResult::ERROR_INVALID_PASSWORD:
            $bag->ValidationErrors[EditProfileView::FIELD_NAME_PASSWORD] = $strings::ERROR_PASSWORD_PATTERN;
            break;

          case ProfileOperationResult::ERROR_PASSWORDS_MISMATCH:
            $bag->ValidationErrors[EditProfileView::FIELD_NAME_PASSWORD_CONFIRM] = $strings::ERROR_PASSWORD_CONFIRM;
            break;

          case ProfileOperationResult::ERROR_INVALID_FIRST_NAME:
            $bag->ValidationErrors[EditProfileView::FIELD_NAME_FIRST_NAME] = $strings::ERROR_FIRST_NAME;
            break;

          case ProfileOperationResult::ERROR_INVALID_LAST_NAME:
            $bag->ValidationErrors[EditProfileView::FIELD_NAME_LAST_NAME] = $strings::ERROR_LAST_NAME;
            break;

          case ProfileOperationResult::ERROR_INVALID_BIRTHDAY:
            $bag->ValidationErrors[EditProfileView::FIELD_NAME_BIRTHDAY] = $strings::ERROR_BIRTHDAY;
            break;

          case ProfileOperationResult::ERROR_INVALID_PHONE:
            $bag->ValidationErrors[EditProfileView::FIELD_NAME_PHONE] = $strings::ERROR_PHONE_FORMAT;
            break;

          case ProfileOperationResult::ERROR_INVALID_EMAIL:
            $bag->ValidationErrors[EditProfileView::FIELD_NAME_EMAIL] = $strings::ERROR_EMAIL_FORMAT;
            break;

          default:
            throw new LogicException('Unexpected profile validation error');
        }
      }
    }
  }

  protected function HeaderItemsMask() {
    return $this->context->Session->IsAuthenticated() ?
      HeaderItem::FILES | HeaderItem::LOGOUT :
      HeaderItem::LOGIN;
  }

  protected function RequiresAuthentication()
  {
    return $this->context->Request->Action != 'new';
  }

  protected function AllowsExpiredSession()
  {
    return $this->context->Request->Action == 'new';
  }
}

