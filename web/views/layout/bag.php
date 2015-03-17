<?php
// TODO: Or even better? We get rid of LayoutBag moving its properties to the LayoutView but still use bags for specific views
abstract class LayoutViewBag {
  public $stylesheets = array();
  public $scripts = array();

  public $headerTitle = '';

  /** @var  HeaderItem[] */
  public $headerItems = array();

  /** @var  LanguageItem[] */
  public $languageItems = array();

  /** @var  MessageBox */
  public $alert;
}

class HeaderItem {
  const FILES     = 0x1;
  const PROFILE   = 0x2;
  const NEW_USER  = 0x4;
  const LOGIN     = 0x8;
  const LOGOUT    = 0x10;

  public $text;
  public $uri;

  public function __construct($text, $uri) {
    $this->text = $text;
    $this->uri = $uri;
  }
}

class LanguageItem {
  public $code;
  public $default;
  public $name;
  public $uri;

  public function __construct($code, $name, $default = false, $uri = null) {
    $this->code = $code;
    $this->default = $default;
    $this->name = $name;
    $this->uri = $uri;
  }
}