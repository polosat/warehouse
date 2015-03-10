<?php
abstract class Strings {
  protected $className;
  protected $language;

  protected function __construct($className) {
    $this->className = $className;
  }

  static public function GetInstance($language) {
    $className = get_called_class();
    if (!empty($language)) {
      $className .= '_' . $language;
    }
    $instance = new $className($className);
    $instance->language = $language;

    return $instance;
  }

  public function GetLanguage() {
    return $this->language;
  }

  public function GetConstant($name) {
    return constant($this->className . '::' . strtoupper($name));
  }
}

//abstract class Strings {
//  static private $instances = array();
//  protected $className;
//
//  protected function __construct($className) {
//    $this->className = $className;
//  }
//
//  static public function GetInstance($language) {
//    $defaultClass = get_called_class();
//    $localizedClass = empty($language) ? $defaultClass : ($defaultClass . '_' . $language);
//
//    if (isset(self::$instances[$localizedClass])) {
//      return self::$instances[$localizedClass];
//    }
//    else if (class_exists($localizedClass)) {
//      return (self::$instances[$localizedClass] = new $localizedClass($localizedClass));
//    }
//    else if (isset(self::$instances[$defaultClass])) {
//      return self::$instances[$defaultClass];
//    }
//    else {
//      return (self::$instances[$defaultClass] = new $defaultClass($defaultClass));
//    }
//  }
//
//  public function GetConstant($name) {
//    return constant($this->className . '::' . strtoupper($name));
//  }
//}