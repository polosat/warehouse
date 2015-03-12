<?php
require_once __DIR__ . '/strings.php';
require_once __DIR__ . '/../views/layout/view.php';
require_once __DIR__ . '/../controllers/login/index.php';

abstract class Controller {
  const DEFAULT_ACTION = 'Index';

  abstract protected function RequiresAuthentication();
  abstract protected function AllowsExpiredSession();
  abstract protected function HeaderItemsMask();

  protected $context;

  public function __construct(ControllerContext $context) {
    $this->context = $context;
  }

  public function Invoke() {
    $context = $this->context;
    $session = $context->session;
    $request = $context->request;

    $callbackRequest = null;

    if (!$this->AllowsExpiredSession() && $session->IsExpired()) {
      $callback = new LoginCallback(LoginCallback::REASON_SESSION_EXPIRED);
      $callbackRequest = $this->CreateCallbackRequest($callback, Application::LOGIN_CONTROLLER);
    }
    else if ($this->RequiresAuthentication() && !$context->session->IsAuthenticated()) {
      $callback = new LoginCallback(LoginCallback::REASON_AUTHENTICATION_REQUIRED);
      $callbackRequest = $this->CreateCallbackRequest($callback, Application::LOGIN_CONTROLLER);
    }
    else {
      $methodName = 'Action';
      if ($context->request instanceof PostRequest) {
        $methodName .= 'Post';
      }

      $actionName = empty($request->action) ? Controller::DEFAULT_ACTION : $request->action;
      $methodName .= "_$actionName";

      if (method_exists($this, $methodName)) {
        $callbackRequest = $this->CallMethod($methodName);
      }
      else {
        throw new InvalidRequestException();
      }
    }

    return $callbackRequest;
  }

  protected function CallMethod($methodName) {
    return $this->$methodName();
  }

  protected function CreateCallbackRequest(CallbackBase $callback = null, $controller = null, $action = null) {
    $context = $this->context;
    $request = $context->request;

    if (isset($callback)) {
      $context->PushCallback($callback);
    }

    $callbackRequest = new Request(
      $request->language,
      $controller,
      $action
    );

    return $callbackRequest;
  }

  protected function UnserializeCallback() {
    $serializedCallback = $this->context->serializedCallback;
    return unserialize($serializedCallback);
  }

  protected function InitializeLayoutView(LayoutView $view) {
    $context = $this->context;
    $request = $context->request;
    $layoutStrings = $view->LayoutStrings;
    $bag = $view->Bag;

    $selectedLanguage = $request->language;
    $defaultLanguage = $context->languages[0];

    // Get the current uri without a language prefix
    $baseUri = $request->Uri('');
    $bag->languageItems = array();

    // Reserve a place for the selected language, it should be first
    $bag->languageItems[] = null;
    foreach ($context->languages as $language) {
      $default = ($language == $defaultLanguage);
      $languageName = $layoutStrings->GetConstant("LANGUAGE_$language");
      $uriLanguagePrefix = $default ? '' : "/$language";

      if ($default && empty($selectedLanguage) || $language == $selectedLanguage) {
        $bag->languageItems[0] = new LanguageItem($language, $languageName, $default);
      }
      else {
        $bag->languageItems[] = new LanguageItem($language, $languageName, $default, $uriLanguagePrefix . $baseUri);
      }
    }

    // Initialize header items
    $uriLanguagePrefix = empty($selectedLanguage) ? '' : "/$selectedLanguage";
    $headerItemsMask = $this->HeaderItemsMask();
    $bag->headerItems = array();
    $itemID = 1;

    while ($headerItemsMask) {
      if ($headerItemsMask & $itemID) {
        switch ($itemID) {
          case HeaderItem::FILES:
            $uri = Application::URI_VIEW_FILES;
            $title = $layoutStrings::HEADER_ITEM_FILES;
            break;

          case HeaderItem::PROFILE:
            $uri = Application::URI_PROFILE;
            $title = $layoutStrings::HEADER_ITEM_PROFILE;
            break;

          case HeaderItem::NEW_USER:
            $uri = Application::URI_NEW_USER;
            $title = $layoutStrings::HEADER_ITEM_NEW_USER;
            break;

          case HeaderItem::LOGIN:
            $uri = Application::URI_LOGIN;
            $title = $layoutStrings::HEADER_ITEM_LOGIN;
            break;

          case HeaderItem::LOGOUT:
            $uri = Application::URI_LOGOUT;
            $title = $layoutStrings::HEADER_ITEM_LOGOUT;
            break;

          default:
            throw new LogicException('Unexpected header link.');
        }
        $bag->headerItems[] = new HeaderItem($title, $uriLanguagePrefix . $uri);
        $headerItemsMask &= ~$itemID;
      }
      $itemID <<= 1;
    }
  }
}