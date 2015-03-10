<?php
require_once __DIR__ . '/exceptions.php';
require_once __DIR__ . '/callback.php';

class PostRequest extends Request {
  public $files;

  public function GetPostedValue($name, $nullIfEmpty = true) {
    $value = isset($_POST[$name]) ? $_POST[$name] : null;
    if ($nullIfEmpty && empty($value)) {
      $value = null;
    }
    return $value;
  }
}

class Request {
  public $controller;
  public $action;
  public $argument;
  public $language;

  public static function Parse() {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      $request = new Request();
    }
    else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $request = new PostRequest();
      $request->files = isset($_FILES) ? $_FILES : null;
    }
    else {
      throw new InvalidRequestException();
    }

    // Clean up parameters of the request
    $language = isset($_GET['language']) ? $_GET['language'] : '';
    $controller = isset($_GET['controller']) ? $_GET['controller'] : '';
    $action = isset($_GET['action']) ? $_GET['action'] : '';
    $request->argument = isset($_GET['argument']) ? $_GET['argument'] : '';

    // Check if the http server couldn't parse the uri
    if ($controller == '404')
      throw new InvalidRequestException();

    $request->language = trim(mb_strtolower($language));
    $request->controller = trim(mb_strtolower($controller));
    $request->action = trim(mb_strtolower($action));

    // Syntax validation
    if ($request->language && (mb_strlen($request->language) != 2 || !self::IsValidName($request->language))) {
      $request->language = '';
    }

    if ($request->controller && !self::IsValidName($request->controller))
      throw new InvalidRequestException();

    if ($request->action && !self::IsValidName($request->action))
      throw new InvalidRequestException();

    return $request;
  }

  public function Uri($language = null) {
    if (is_null($language)) {
      $language = $this->language;
    }

    $uri = null;
    $items = array($language, $this->controller, $this->action, urlencode($this->argument));
    foreach ($items as $item) {
      if ($item) {
        $uri .= "/$item";
      }
      else if (is_null($uri)) {
        $uri = '';
      }
      else {
        break;
      }
    }

    return (empty($uri) ? '/' : $uri);
  }

  public static function CreateUri($language = '', $controller = null, $action = null, $argument = null) {
    $request = new Request();
    $request->language = $language;
    $request->controller = $controller;
    $request->action = $action;
    $request->argument = $argument;
    return $request->Uri();
  }

  public static function IsValidName($text) {
    return (preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $text) === 1);
  }
}