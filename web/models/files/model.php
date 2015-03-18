<?php
require_once __DIR__ . '/file.php';
require_once __DIR__ . '/result.php';
require_once __DIR__ . '/../../classes/model.php';
require_once __DIR__ . '/../../classes/utils.php';

class FilesModel extends Model {
  public function GetUserFiles($userID) {
    $dbh = $this->dbh();

    try {
      $sth = $dbh->prepare(self::QUERY_SELECT_USER_FILES);
      $sth->bindValue(':UserID', $userID, PDO::PARAM_INT);
      $sth->setFetchMode(PDO::FETCH_CLASS, 'FileEntity');
      $sth->execute();
      $files = $sth->fetchAll();
    }
    catch(PDOException $e) {
      throw new DatabaseException($e);
    }

    /** @var FileEntity[] $files */
    return $files;
  }

  public function GetFile($fileID) {
    /** @var FileEntity $file */
    $file = null;

    $dbh = $this->dbh();

    try {
      $sth = $dbh->prepare(self::QUERY_SELECT_FILE);
      $sth->bindValue(':FileID', $fileID, PDO::PARAM_INT);
      $sth->setFetchMode(PDO::FETCH_CLASS, 'FileEntity');
      $sth->execute();
      $file = $sth->fetch();
    }
    catch(PDOException $e) {
      throw new DatabaseException($e);
    }

    return $file ? $file : null;
  }

  public function DeleteUserFiles($userID, array $files) {
    $failed = array();
    $deleted = array();

    foreach ($files as $fileID) {
      $fileID = intval($fileID);

      $filePath = Settings::STORAGE_PATH . "$fileID.$userID";
      if (@unlink($filePath) || !file_exists($filePath)) {
        $deleted[] = $fileID;
      }
      else {
        $failed[] = $fileID;
      }
    }

    if ($deleted) {
      $files = implode(',', $deleted);
      $query = sprintf(self::QUERY_DELETE_USER_FILES, $files);

      $dbh = $this->dbh();

      try {
        $sth = $dbh->prepare($query);
        $sth->bindValue(':UserID', $userID, PDO::PARAM_INT);
        $sth->execute();
      }
      catch(PDOException $e) {
        throw new DatabaseException($e);
      }
    }

    return $failed;
  }

  public function SaveFile(FileSaveRequest $request) {
    if (!is_uploaded_file($request->UploadedPath))
      throw new LogicException('Invalid file to save');

    if (isset($request->FileID))
      throw new LogicException('FileID must be null.');

    trim_to_null($request->FileName);

    if (empty($request->FileName)) {
      return FileOperationResult::ERROR_FILE_NAME_EMPTY;
    }

    if (mb_strlen($request->FileName) > FileEntity::MAX_NAME_LENGTH) {
      return FileOperationResult::ERROR_FILE_NAME_TOO_LONG;
    }

    $fileSize = filesize($request->UploadedPath);
    if ($fileSize > Settings::MAX_FILE_SIZE) {
      return FileOperationResult::ERROR_FILE_SIZE_TOO_BIG;
    }

    if (preg_match(FileEntity::WRONG_NAME_MASK, $request->FileName) === 1) {
      return FileOperationResult::ERROR_FILE_NAME_WRONG;
    }

    $permanentPath = null;
    $dbh = $this->dbh();

    try {
      $sth = $dbh->prepare(self::QUERY_INSERT_FILE);
      $sth->bindValue(':UserID', $request->UserID, PDO::PARAM_INT);
      $sth->bindValue(':WhereUserID', $request->UserID, PDO::PARAM_INT);
      $sth->bindValue(':FileName', $request->FileName);
      $sth->bindValue(':Size', $fileSize, PDO::PARAM_INT);
      $sth->bindValue(':ContentType', $request->ContentType);
      $sth->bindValue(':MaxFilesPerUser', Settings::MAX_FILES_PER_USER, PDO::PARAM_INT);

      $dbh->beginTransaction();
      $sth->execute();

      if ($sth->rowCount() == 0) {
        $dbh->rollBack();
        return FileOperationResult::ERROR_FILES_LIMIT_EXCEEDED;
      }

      $fileID = $this->dbh()->lastInsertId();
      $permanentPath = Settings::STORAGE_PATH . "$fileID.$request->UserID";
      if (!@move_uploaded_file($request->UploadedPath, $permanentPath)) {
        $dbh->rollBack();
        return FileOperationResult::ERROR_SERVER_FAILURE;
      }

      $dbh->commit();
    }
    catch (PDOException $e) {
      if ($dbh->inTransaction()) {
        $dbh->rollBack();
      }
      if (file_exists($permanentPath)) {
        unlink($permanentPath);
      }
      if ($e->getCode() == self::SQL_INTEGRITY_ERROR) {
        return FileOperationResult::ERROR_USER_DOES_NOT_EXISTS;
      }
      else {
        throw new DatabaseException($e);
      }
    }

    return FileOperationResult::OPERATION_SUCCEEDED;
  }

  const QUERY_DELETE_USER_FILES = <<<SQL
    UPDATE
      Files
    SET
      DeletedOn = UTC_TIMESTAMP()
    WHERE
      FileID IN (%s)
    AND
      UserID = :UserID
    AND
      DeletedOn IS NULL
SQL;

  const QUERY_SELECT_USER_FILES = <<<SQL
    SELECT
      FileID,
      FileName,
      Size,
      ContentType,
      UploadedOn
    FROM
      Files
    WHERE
      UserID = :UserID
    AND
      DeletedOn IS NULL
    ORDER BY
      UploadedOn DESC
SQL;

  const QUERY_SELECT_FILE = <<<SQL
    SELECT
      FileID,
      UserID,
      FileName,
      Size,
      ContentType,
      UploadedOn,
      DeletedOn
    FROM
      Files
    WHERE
      FileID = :FileID
    AND
      DeletedOn IS NULL
SQL;

  // If we've exceeded files per user limit, then we'll have 0 affected rows
  const QUERY_INSERT_FILE = <<<SQL
    INSERT INTO Files (
      UserID,
      FileName,
      Size,
      ContentType,
      UploadedOn,
      DeletedOn
    )
    SELECT
      :UserID,
      :FileName,
      :Size,
      :ContentType,
      UTC_TIMESTAMP(),
      NULL
    FROM
      Files
    WHERE
      UserID = :WhereUserID
    AND
      DeletedOn IS NULL
    HAVING
      COUNT(*) < :MaxFilesPerUser
SQL;
}
