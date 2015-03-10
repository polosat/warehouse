<?php
require_once 'session.php';
require_once 'request.php';
require_once 'context.php';
require_once 'controller.php';
require_once __DIR__ . '/../views/404/view.php';

class Application {
  const LOGIN_CONTROLLER    = 'login';
  const PROFILE_CONTROLLER  = 'profile';
  const DEFAULT_CONTROLLER  = 'files';

  const URI_LOGIN       = '/login';
  const URI_LOGOUT      = '/login/logout';
  const URI_VIEW_FILES  = '/files';
  const URI_PROFILE     = '/profile';
  const URI_NEW_USER    = '/profile/new';

  static private $instance;

  protected function __construct() {
    mb_internal_encoding('UTF-8');
    mb_http_output('UTF-8');
  }

  public static function Run() {
    try {
      if (isset(self::$instance))
        throw new LogicException('The application is already started.');

      self::$instance = new Application();
      self::$instance->RouteRequest();
    }
    catch (Exception $e) {
      //TODO: Log the error here
      throw $e;
    }
  }

  protected function RouteRequest() {
    try {
      $request = Request::Parse();
      $session = Session::Open();

      $context = new ControllerContext($request, $session);
      $controller = $this->CreateController($context);
      $callbackRequest = $controller->Invoke();

      if ($callbackRequest instanceof Request) {
        $this->Redirect($callbackRequest);
      }
    }
    catch (InvalidRequestException $e) {
      $language = isset($context) ? $context->request->language : '';
      $this->Show404($language);
    }
  }

  protected function CreateController(ControllerContext $context) {
    $request = $context->request;
    $name = empty($request->controller) ? Application::DEFAULT_CONTROLLER : $request->controller;

    switch ($name) {
      case 'login':
        require_once __DIR__ . '/../controllers/login/index.php';
        return new LoginController($context);
        break;

      case 'files':
        require_once __DIR__ . '/../controllers/files/index.php';
        return new FilesController($context);
        break;

      case 'profile':
        require_once __DIR__ . '/../controllers/profile/index.php';
        return new ProfileController($context);
        break;

      default:
        throw new InvalidRequestException();
    }
  }

  protected function Redirect(Request $request) {
    $uri = $request->Uri();
    header("Location: $uri", true, 303);
  }

  protected function Show404($language) {
    header('X-PHP-Response-Code: 404', true, 404);
    NotFoundView::Render($language);
  }
}