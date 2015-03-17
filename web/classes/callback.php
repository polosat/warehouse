<?php
abstract class CallbackBase {
  public $alert;
  public $reason;

  public function __construct($reason) {
    /** @var  MessageBox $alert */
    $alert = null;

    $this->reason = $reason;
    $this->alert = $alert;
  }
}