<?php
class AppException extends Exception {
  function __construct($message, $code = 0, Exception $previous = null) {
    parent::__construct($message, $code, $previous);
  }
}

class NoDifferenceException extends Exception {
  function __construct() {
    parent::__construct();
  }
}

class UniqueViolationException extends Exception {
  function __construct(Exception $previous) {
    parent::__construct("", 0, $previous);
  }
}