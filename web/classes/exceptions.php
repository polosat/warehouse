<?php
class InvalidRequestException extends DomainException {
  public function __construct() {
    $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    parent::__construct($uri);
  }
}

class DatabaseException extends RuntimeException {
  function __construct(PDOException $pdoException = null) {
    parent::__construct('Database error', $pdoException->code, $pdoException);
  }
}