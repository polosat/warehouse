<?php
require_once __DIR__ . '/strings.php';

class NotFoundView {
  static public function Render($language) {
    $strings = NotFoundViewStrings::GetInstance($language);
    require 'template.php';
  }
}