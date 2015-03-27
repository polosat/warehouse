<?php
class FilesCallback extends CallbackBase {
  const REASON_OPERATION_ERROR  = 1001;

  public function __construct($errorMessage) {
    parent::__construct(self::REASON_OPERATION_ERROR);
    $this->Alert = new MessageBox($errorMessage);
  }
}