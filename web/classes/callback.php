<?php
abstract class CallbackBase {
  /** @var  MessageBox */
  public $alert;
  public $reason;

  public function __construct($reason) {
    $this->reason = $reason;
  }
}