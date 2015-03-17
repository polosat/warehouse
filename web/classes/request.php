<?php
require_once __DIR__ . '/exceptions.php';
require_once __DIR__ . '/callback.php';

class Request {
  public $Controller;
  public $Action;
  public $Argument;
  public $Language;
  public $Error;

  public function __construct($language = '', $controller = null, $action = null, $argument = null) {
    $this->Language = $language;
    $this->Controller = $controller;
    $this->Action = $action;
    $this->Argument = $argument;
    $this->Error = '';
  }

  public static function Parse() {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      $request = new Request();
    }
    else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $request = new PostRequest();
      $request->ParsePostedFiles();
    }
    else {
      throw new InvalidRequestException();
    }

    // Clean up parameters of the request
    $language = isset($_GET['language']) ? $_GET['language'] : '';
    $controller = isset($_GET['controller']) ? $_GET['controller'] : '';
    $action = isset($_GET['action']) ? $_GET['action'] : '';
    $request->Error = isset($_GET['error']) && ctype_digit($_GET['error']) ? $_GET['error'] : '';
    $request->Argument = isset($_GET['argument']) ? $_GET['argument'] : '';

    // Check if the http server couldn't parse the uri
    if ($request->Error == '404')
      throw new InvalidRequestException();

    $request->Language = trim(mb_strtolower($language));
    $request->Controller = trim(mb_strtolower($controller));
    $request->Action = trim(mb_strtolower($action));

    // Syntax validation
    if ($request->Language && (mb_strlen($request->Language) != 2 || !self::IsValidName($request->Language))) {
      $request->Language = '';
    }

    if ($request->Controller && !self::IsValidName($request->Controller))
      throw new InvalidRequestException();

    if ($request->Action && !self::IsValidName($request->Action))
      throw new InvalidRequestException();

    return $request;
  }

  public function Uri($language = null) {
    if (is_null($language)) {
      $language = $this->Language;
    }

    $uri = null;
    $items = array($language, $this->Controller, $this->Action, urlencode($this->Argument));
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
    $request = new Request($language, $controller, $action, $argument);
    return $request->Uri();
  }

  public static function IsValidName($text) {
    return (preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $text) === 1);
  }
}

class PostRequest extends Request {
  /** @var  PostedFile[] */
  public $Files = array();

  public function GetPostedValue($name, $nullIfEmpty = true) {
    $value = isset($_POST[$name]) ? $_POST[$name] : null;
    if ($nullIfEmpty && empty($value)) {
      $value = null;
    }
    return $value;
  }

  public function ParsePostedFiles() {
    $files = isset($_FILES) ? $_FILES : array();
    foreach ($files as $fieldName => $fileEntry) {
      if (is_array($fileEntry)) {
        if (isset($fileEntry['name'])) {
          $fileInfo = $fileEntry['name'];
          if (is_array($fileInfo)) {
            foreach ($fileInfo as $index => $value) {
              $fileName = $value;
              $filePath = isset($fileEntry['tmp_name'][$index]) ? $fileEntry['tmp_name'][$index] : null;
              $fileSize = isset($fileEntry['size'][$index]) ? $fileEntry['size'][$index] : null;
              $fileType = isset($fileEntry['type'][$index]) ? $fileEntry['type'][$index] : null;
              $fileError = isset($fileEntry['error'][$index]) ? $fileEntry['error'][$index] : null;
              if ($file = PostedFile::Create($fieldName, $fileName, $filePath, $fileSize, $fileType, $fileError)) {
                $this->Files[] = $file;
              }
            }
          }
          else {
            $fileName = $fileInfo;
            $filePath = isset($fileEntry['tmp_name']) ? $fileEntry['tmp_name'] : null;
            $fileSize = isset($fileEntry['size']) ? $fileEntry['size'] : null;
            $fileType = isset($fileEntry['type']) ? $fileEntry['type'] : null;
            $fileError = isset($fileEntry['error']) ? $fileEntry['error'] : null;
            if ($file = PostedFile::Create($fieldName, $fileName, $filePath, $fileSize, $fileType, $fileError)) {
              $this->Files[] = $file;
            }
          }
        }
      }
    }
  }
}

class PostedFile {
  public $Field;
  public $Name;
  public $Path;
  public $PostedSize;
  public $RealSize;
  public $Type;
  public $Error;

  public static function Create($field, $name, $path, $size, $type, $error) {
    $instance = null;
    $realSize = 0;
    if (isset($error) && isset($name)) {
      if ($error === UPLOAD_ERR_OK && is_uploaded_file($path)) {
        $realSize = filesize($path);
// Shouldn't we rely on user's sent mime type?
//        if ($fileInfo = new finfo(FILEINFO_MIME_TYPE)) {
//          $type = $fileInfo->file($path);
//        }
      }
      $instance = new PostedFile();
      $instance->Field = $field;
      $instance->Name = $name;
      $instance->Error = $error;
      $instance->Path = $path;
      $instance->PostedSize = $size;
      $instance->RealSize = ($realSize !== false) ? $realSize : 0;
      $instance->Type = $type;
    }
    return $instance;
  }
}