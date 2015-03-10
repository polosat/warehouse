<?php
require_once __DIR__ . '/../../classes/model.php';
require_once __DIR__ . '/../../classes/hash.php';

class LoginModel extends Model {
  public function GetUserID($login, $password) {
    $userID = null;
    $dbh = $this->dbh();

    try {
      $sth = $dbh->prepare(self::QUERY_SELECT_USER_ID);
      $sth->bindValue(':Login', $login);
      $sth->setFetchMode(PDO::FETCH_CLASS, 'StdClass');
      $sth->execute();
      $record = $sth->fetch();

      if ($record) {
        $hashToCheck = crypt($password, $record->PasswordHash);
        if (Hash::Equal($record->PasswordHash, $hashToCheck))
          $userID = $record->UserID;
      }
    }
    catch(PDOException $e) {
      throw new DatabaseException($e);
    }

    return $userID;
  }

  // Just selects ID of the user by his login name
  const QUERY_SELECT_USER_ID = <<<SQL
    SELECT
      UserID, PasswordHash
    FROM
      Users
    WHERE
      Login = :Login
    AND
      TraceID = 0
SQL;
}