<?php
require_once __DIR__ . '/user.php';
require_once __DIR__ . '/result.php';
require_once __DIR__ . '/../../classes/utils.php';
require_once __DIR__ . '/../../classes/hash.php';
require_once __DIR__ . '/../../classes/result.php';
require_once __DIR__ . '/../../classes/model.php';

class ProfileModel extends Model {
  public function GetUser($userID) {
    $user = null;

    $dbh = $this->dbh();

    try {
      $sth = $dbh->prepare(self::QUERY_SELECT_ACTIVE);
      $sth->bindValue(':UserID', $userID, PDO::PARAM_INT);
      $sth->setFetchMode(PDO::FETCH_CLASS, 'StdClass');
      $sth->execute();
      $record = $sth->fetch();

      if ($record) {
        $user = new UserEntity();
        $user->UserID = $record->UserID;
        $user->Login = $record->Login;
        $user->FirstName = $record->FirstName;
        $user->LastName = $record->LastName;
        $user->EMail = $record->EMail;
        $user->Phone = $record->Phone;

        $birthday = $record->Birthday;
        if (isset($birthday)) {
          $ymd = explode('-', $birthday);
          $birthday = $ymd[2] . '/' . $ymd[1] . '/' . $ymd[0];
        }
        $user->Birthday = $birthday;
      }
    }
    catch(PDOException $e) {
      throw new DatabaseException($e);
    }

    return $user;
  }

  public function CreateUser(ProfileChangeRequest $request) {
    $user = $request->User;

    if (isset($user->UserID))
      throw new LogicException('UserID must be null.');

    $this->SanitizeChangeRequest($request);
    $result = $this->ValidateChangeRequest($request);

    if ($result->Succeeded()) {
      $dbh = $this->dbh();

      try {
        $sth = $this->prepareInsert($user);
        $sth->bindValue(':PasswordHash', '');

        // This query fails if there is another active record with the same login name.
        // We do not insert the real password at this step as we don't know yet the UserID
        // which is required to calculate the entity checksum and therefore the password salt.
        $dbh->beginTransaction();
        $sth->execute();

        $userRecordID = $this->dbh()->lastInsertId();
        $user->UserID = $userRecordID;

        $sth = $dbh->prepare(self::QUERY_SET_USER_ID);
        $sth->bindValue(':UserRecordID', $userRecordID, PDO::PARAM_INT);
        $sth->bindValue(':PasswordHash', Hash::Create($user->Password, $user->Checksum()));

        // Set UserID to the newly created identity column value,
        // so duplicated entry exception is not possible at this step
        $sth->execute();
        $dbh->commit();
      }
      catch (PDOException $e) {
        $user->UserID = null;
        if ($dbh->inTransaction()) {
          $dbh->rollBack();
        }
        if ($e->getCode() == self::SQL_ER_DUP_ENTRY) {
          $result->AddError(ProfileOperationResult::ERROR_LOGIN_ALREADY_EXISTS);
        }
        else {
          throw new DatabaseException($e);
        }
      }
    }
    return $result;
  }

  public function UpdateUser(ProfileChangeRequest $request) {
    $user = $request->User;

    if (is_null($user->UserID))
      throw new LogicException('UserID must be specified.');

    $this->SanitizeChangeRequest($request);
    $result = $this->ValidateChangeRequest($request);

    $dbh = $this->dbh();

    if ($result->Succeeded()) {
      try {
        // Set user's password to the current one if there was no password change requested.
        if (empty($user->Password)) {
          $user->Password = $request->CurrentPassword;
        }

        // First we're trying to deactivate the active record for this user
        // and at the same time to detect: if any fields were changed?
        $passwordHash = Hash::Create($user->Password, $user->Checksum());

        $sth = $dbh->prepare(self::QUERY_DEACTIVATE_RECORD);
        $sth->bindValue(':UserID', $user->UserID, PDO::PARAM_INT);
        $sth->bindValue(':PasswordHash', $passwordHash);

        $dbh->beginTransaction();
        $sth->execute();

        // Does the content of the new record differ from the actual one?
        if ($sth->rowCount() == 0) {
          $dbh->rollBack();
          $result->AddError(ProfileOperationResult::ERROR_DUPLICATED_RECORD);
          return $result;
        }

        $sth = $this->prepareInsert($user, false);
        $sth->bindValue(':PasswordHash', $passwordHash);

        // This query fails if there another active record exists with the same login name.
        $sth->execute();
        $dbh->commit();
      }
      catch (PDOException $e) {
        if ($dbh->inTransaction()) {
          $dbh->rollBack();
        }
        if ($e->getCode() == self::SQL_ER_DUP_ENTRY) {
          $result->AddError(ProfileOperationResult::ERROR_LOGIN_ALREADY_EXISTS);
        }
        else {
          throw new DatabaseException($e);
        }
      }
    }
    return $result;
  }

  protected function GetPasswordHash($userID) {
    $dbh = $this->dbh();

    try {
      $sth = $dbh->prepare(self::QUERY_SELECT_PASSWORD_HASH);
      $sth->bindValue(':UserID', $userID, PDO::PARAM_INT);
      $sth->execute();
      $result = $sth->fetchColumn();
      $passwordHash = ($sth->rowCount() == 0) ? null : $result;
    }
    catch(PDOException $e) {
      throw new DatabaseException($e);
    }

    return $passwordHash;
  }

  protected function PrepareInsert(UserEntity $user) {
    $sth = $this->dbh()->prepare(self::QUERY_INSERT);

    $birthday = $user->Birthday;
    if ($birthday) {
      $mdy = explode('/', $user->Birthday);
      $birthday = $mdy[2] . '-' . $mdy[1] . '-' . $mdy[0];
    }

    $sth->bindValue(':UserID', $user->UserID, PDO::PARAM_INT);
    $sth->bindValue(':Login', $user->Login);
    $sth->bindValue(':FirstName', $user->FirstName);
    $sth->bindValue(':LastName', $user->LastName);
    $sth->bindValue(':EMail', $user->EMail);
    $sth->bindValue(':Phone', $user->Phone);
    $sth->bindValue(':Birthday', $birthday);

    return $sth;
  }

  protected function SanitizeChangeRequest(ProfileChangeRequest $request) {
    $user = $request->User;
    trim_to_null($user->Login, false);
    trim_to_null($user->FirstName);
    trim_to_null($user->LastName);
    trim_to_null($user->Birthday);
    trim_to_null($user->EMail);
    trim_to_null($user->Phone);
  }

  protected function ValidateChangeRequest(ProfileChangeRequest $request) {
    $result = new ProfileOperationResult();
    $user = $request->User;

    if (isset($user->UserID)) {
      $currentPasswordHash = $this->GetPasswordHash($user->UserID);

      if (is_null($currentPasswordHash)) {
        $result->AddError(ProfileOperationResult::ERROR_UNKNOWN_USER_ID);
        return $result;
      }

      $hashInRequest = crypt($request->CurrentPassword, $currentPasswordHash);
      if (!Hash::Equal($currentPasswordHash, $hashInRequest)) {
        $result->AddError(ProfileOperationResult::ERROR_BAD_CURRENT_PASSWORD);
      }
    }

    $badLength = empty($user->Login) || mb_strlen($user->Login) > UserEntity::LOGIN_MAX_LENGTH;
    if ($badLength || has_ctrl_chars($user->Login)) {
      $result->AddError(ProfileOperationResult::ERROR_INVALID_LOGIN);
    }

    $badLength = empty($user->FirstName) || mb_strlen($user->FirstName) > UserEntity::FIRST_NAME_MAX_LENGTH;
    if ($badLength || has_ctrl_chars($user->FirstName)) {
      $result->AddError(ProfileOperationResult::ERROR_INVALID_FIRST_NAME);
    }

    $badLength = empty($user->LastName) || mb_strlen($user->LastName) > UserEntity::LAST_NAME_MAX_LENGTH;
    if ($badLength || has_ctrl_chars($user->LastName)) {
      $result->AddError(ProfileOperationResult::ERROR_INVALID_LAST_NAME);
    }

    if ($user->Password != $request->PasswordConfirmation) {
      $result->AddError(ProfileOperationResult::ERROR_PASSWORDS_MISMATCH);
    }
    else if (empty($user->UserID) || $user->Password) {
      $length = mb_strlen($user->Password);
      $badLength = $length > UserEntity::PASSWORD_MAX_LENGTH || $length < UserEntity::PASSWORD_MIN_LENGTH;
      if ($badLength || !preg_match(UserEntity::PASSWORD_PATTERN, $user->Password) || has_ctrl_chars($user->Password)) {
        $result->AddError(ProfileOperationResult::ERROR_INVALID_PASSWORD);
      }
    }

    if (isset($user->EMail)) {
      $badLength = mb_strlen($user->EMail) > UserEntity::EMAIL_MAX_LENGTH;
      if ($badLength || filter_var($user->EMail, FILTER_VALIDATE_EMAIL) === false) {
        $result->AddError(ProfileOperationResult::ERROR_INVALID_EMAIL);
      }
    }

    if (isset($user->Phone)) {
      $badLength = mb_strlen($user->Phone) > UserEntity::PHONE_MAX_LENGTH;
      if ($badLength || !preg_match(UserEntity::PHONE_PATTERN, $user->Phone) || has_ctrl_chars($user->Phone)) {
        $result->AddError(ProfileOperationResult::ERROR_INVALID_PHONE);
      }
    }

    if (isset($user->Birthday)) {
      $invalid_birthday = true;
      $birthday = parse_date_time($user->Birthday, 'd/m/Y');
      if ($birthday) {
        $birthday->setTime(0, 0, 0);
        $minBirthday = self::GetMinAllowedBirthdayDate();
        $maxBirthday = self::GetMaxAllowedBirthdayDate();
        $invalid_birthday = ($minBirthday->diff($birthday)->invert || $birthday->diff($maxBirthday)->invert);
      }
      if ($invalid_birthday) {
        $result->AddError(ProfileOperationResult::ERROR_INVALID_BIRTHDAY);
      }
    }

    return $result;
  }

  static public function GetMaxAllowedBirthdayDate() {
    $date = new DateTime('now', new DateTimeZone('UTC'));
    $date->add(new DateInterval('PT14H'));
    return $date;
  }

  static public function GetMinAllowedBirthdayDate() {
    return new DateTime(UserEntity::MIN_BIRTHDAY_DATE, new DateTimeZone('UTC'));
  }

  // There can be multiple records with the same UserID in order to keep changes history.
  // But for every UserID there is the only one record having TraceID == 0.
  // This the record storing the latest actual data.
  const QUERY_SELECT_ACTIVE = <<<SQL
    SELECT
      UserRecordID,
      UserID,
      Login,
      PasswordHash,
      FirstName,
      LastName,
      EMail,
      Phone,
      Birthday,
      TraceID,
      TraceOn
    FROM
      Users
    WHERE
      UserID = :UserID
    AND
      TraceID = 0
SQL;

  // We do not update existing records, only insert new ones.
  const QUERY_INSERT = <<<SQL
    INSERT INTO Users (
      UserID,
      Login,
      PasswordHash,
      FirstName,
      LastName,
      EMail,
      Phone,
      Birthday,
      TraceID,
      TraceOn
    )
    VALUES (
      :UserID,
      :Login,
      :PasswordHash,
      :FirstName,
      :LastName,
      :EMail,
      :Phone,
      :Birthday,
      0,
      UTC_TIMESTAMP()
    )
SQL;

  // After we created a new record we should set UserID to a unique value.
  // Copy of UserRecordID is OK as it is always unique.
  const QUERY_SET_USER_ID = <<<SQL
    UPDATE Users
    SET
      UserID = UserRecordID,
      PasswordHash = :PasswordHash
    WHERE
      UserRecordID = :UserRecordID
SQL;

  // No records affected if:
  // - There is no user with such ID at all => the user does not exist.
  // - The user exists but is not active => in fact the user does not exist as well.
  // - The user exists and active, but PasswordHash is the same => the update is not necessary.
  const QUERY_DEACTIVATE_RECORD = <<<SQL
    UPDATE Users
    SET
      TraceID = UserRecordID
    WHERE
      UserID = :UserID
    AND
      TraceID = 0
    AND
      PasswordHash <> :PasswordHash
SQL;

  const QUERY_SELECT_PASSWORD_HASH = <<<SQL
    SELECT
      PasswordHash
    FROM
      Users
    WHERE
      UserID = :UserID
    AND
      TraceID = 0
SQL;
}