<?php
require_once __DIR__ . '/../settings.php';

class Database {
  public $SQL_ER_DUP_ENTRY = "23000";

  private $dbh = null;
  private $dsn;
  private static $instance = null;

  private function __construct() {
    $this->dsn = 'mysql:host=' . Settings::DB_HOST . ';dbname=' . Settings::DB_NAME . ';charset=' . Settings::DB_CHARSET;
  }

  public static function GetInstance() {
    if (self::$instance === null) {
      self::$instance = new Database();
    }
    return self::$instance;
  }

  public function dbh() {
    if ($this->dbh === null) {
      $this->dbh = new PDO(
        $this->dsn,
        Settings::DB_USER,
        Settings::DB_PASSWORD,
        array(
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        )
      );
    }
    return $this->dbh;
  }
}