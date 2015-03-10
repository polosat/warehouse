<?php
require_once __DIR__ . '/../../views/login/view.php';
require_once __DIR__ . '/../../models/login/model.php';
require_once __DIR__ . '/callback.php';

class LoginController extends Controller {
  protected function Action_Index() {
    $context = $this->context;
    $request = $context->request;
    $session = $context->session;

    $callback = $this->GetCallback();

    // Normally an authenticated and non-expired user shouldn't see the login prompt
    if (!$callback && $session->IsAuthenticated() && !$session->IsExpired()) {
      return new Request($request->language);
    }

    $viewBag = new LoginViewBag();
    $view = new LoginView($viewBag, $request->language);
    $this->InitializeLayoutView($view);

    $strings = $view->LoginStrings;

    if (isset($callback)) {
      switch ($callback->reason) {
        case LoginCallback::REASON_AUTHENTICATION_FAILED:
          $viewBag->errorText = $strings::ERROR_AUTHENTICATION_FAILED;
          $viewBag->userName = $callback->UserName;
          break;

        case LoginCallback::REASON_SESSION_EXPIRED:
          $viewBag->messageBox = new MessageBox($strings::ERROR_SESSION_EXPIRED);
          break;

        case LoginCallback::REASON_AUTHENTICATION_REQUIRED:
          //$viewBag->messageBox = new MessageBox($strings::ERROR_AUTHENTICATION_REQUIRED);
          break;

        case LoginCallback::REASON_UNKNOWN_USER_ID:
          $viewBag->messageBox = new MessageBox($strings::ERROR_UNKNOWN_USER);
          break;

        case LoginCallback::REASON_SHOW_MESSAGE:
          if (isset($callback->messageBox)) {
            $viewBag->messageBox = $callback->messageBox;
          }
          break;

        default:
          throw new LogicException('Unexpected redirect reason');
      }
    }

    $view->Render();

    return null;
  }

  protected function ActionPost_Index() {
    $context = $this->context;
    $session = $context->session;
    $request = $context->request;

    $login = $request->GetPostedValue(LoginView::FIELD_NAME_LOGIN);
    $password = $request->GetPostedValue(LoginView::FIELD_NAME_PASSWORD);

    $loginModel = new LoginModel($context);
    $userID = $loginModel->GetUserID($login, $password);

    if ($userID) {
      $session->Reset($userID);
      $redirect = $this->CreateCallbackRequest(null, Application::DEFAULT_CONTROLLER);
    }
    else {
      $callback = new LoginCallback(LoginCallback::REASON_AUTHENTICATION_FAILED);
      $callback->UserName = $login;
      $redirect = $this->CreateCallbackRequest($callback, Application::LOGIN_CONTROLLER);
    }

    return $redirect;
  }

  protected function Action_Logout() {
    $this->context->session->Destroy();
    return new Request('', Application::LOGIN_CONTROLLER);
  }

  protected function GetCallback() {
    $callback = $this->UnserializeCallback();
    return $callback instanceof LoginCallback ? $callback : null;
  }

  protected function HeaderItemsMask() {
    return $this->context->session->IsAuthenticated() ?
      HeaderItem::FILES | HeaderItem::PROFILE | HeaderItem::LOGOUT :
      HeaderItem::NEW_USER;
  }

  protected function RequiresAuthentication()
  {
    return false;
  }

  protected function AllowsExpiredSession()
  {
    return true;
  }
}