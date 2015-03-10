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