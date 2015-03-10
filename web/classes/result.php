<?php
abstract class ModelOperationResult {
  public $Errors = array();

  public function Succeeded() {
    return count($this->Errors) == 0;
  }

  public function Failed() {
    return count($this->Errors) > 0;
  }

  public function AddError($errorCode) {
    $this->Errors[$errorCode] = $errorCode;
  }
}