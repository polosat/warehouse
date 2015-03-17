<?php
class HeaderItem {
  const FILES     = 0x1;
  const PROFILE   = 0x2;
  const NEW_USER  = 0x4;
  const LOGIN     = 0x8;
  const LOGOUT    = 0x10;

  public $Text;
  public $Uri;

  public function __construct($text, $uri) {
    $this->Text = $text;
    $this->Uri = $uri;
  }
}

class LanguageItem {
  public $Code;
  public $Default;
  public $Name;
  public $Uri;

  public function __construct($code, $name, $default = false, $uri = null) {
    $this->Code = $code;
    $this->Default = $default;
    $this->Name = $name;
    $this->Uri = $uri;
  }
}