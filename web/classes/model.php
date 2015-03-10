<?php
class Model {
  const SQL_ER_DUP_ENTRY = "23000";

  private $dbh;
  private $dsn;
  private $user;
  private $password;

  public function __construct(ModelContext $context) {
    $this->dsn = $context->Dsn;
    $this->user = $context->DbUser;
    $this->password = $context->DbPassword;
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