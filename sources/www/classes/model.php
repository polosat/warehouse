<?php
class Model {
  const SQL_INTEGRITY_ERROR = "23000";

  private $dbh;
  private $dsn;
  private $user;
  private $password;

  public function __construct() {
    $this->dsn = 'mysql:host='. Settings::DB_HOST . ';dbname='. Settings::DB_NAME . ';charset=' . Settings::DB_CHARSET;
    $this->user = Settings::DB_USER;
    $this->password = Settings::DB_PASSWORD;
  }

  protected function dbh() {
    if (!isset($this->dbh)) {
      try {
        $this->dbh = new PDO(
          $this->dsn,
          $this->user,
          $this->password,
          array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
          )
        );
        $this->dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      }
      catch (PDOException $e) {
        // We use exception chaining here to prevent possible duplicated calls to $this->dbh()
        // in underlying catch blocks like 'catch(PDOException $e) {...}'
        throw new DatabaseException($e);
      }
    }
    return $this->dbh;
  }
}