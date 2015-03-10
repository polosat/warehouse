<?php
abstract class CallbackBase {
  /** @var  MessageBox */
  public $messageBox;
  public $reason;

  public function __construct($reason) {
    $this->reason = $reason;
  }
}