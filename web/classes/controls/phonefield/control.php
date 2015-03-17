<?php
class PhoneField {
  protected $inputID;

  public function __construct($inputID) {
    $this->inputID = $inputID;
  }

  public function Render() {
    require 'template.php';
  }
}