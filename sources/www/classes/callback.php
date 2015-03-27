<?php
abstract class CallbackBase {
  public $Alert;
  public $Reason;

  public function __construct($reason) {
    /** @var  MessageBox $alert */
    $alert = null;

    $this->Reason = $reason;
    $this->Alert = $alert;
  }
}