<?php
require_once __DIR__ . '/file.php';
require_once __DIR__ . '/../../classes/model.php';
require_once __DIR__ . '/../../classes/utils.php';

class FilesModel extends Model {
  const OPERATION_SUCCEEDED         = 0;
  const ERROR_FILE_SIZE_TOO_BIG     = 1001;
  const ERROR_FILE_NAME_TOO_LONG    = 1002;
  const ERROR_FILE_NAME_EMPTY       = 1003;
  const ERROR_FILE_DOES_NOT_EXISTS  = 1004;
  const ERROR_USER_DOES_NOT_EXISTS  = 1005;
  const ERROR_FILE_LIMIT_EXCEEDED   = 1006;
  const ERROR_SERVER_FAILURE        = 1007;

  const MAX_FILES_PER_USER  = 20;

  protected $storagePath;

  public function __construct(ModelContext $context) {
    parent::__construct($context);
    $this->storagePath = $context->StoragePath;
  }

  public function GetUserFiles($userID) {

  }

  public function GetFile($fileID) {
    $file = null;

    $dbh = $this->dbh();

    try {
      $sth = $dbh->prepare(self::QUERY_SELECT_FILE);
      $sth->bindValue(':FileID', $fileID, PDO::PARAM_INT);
      $sth->setFetchMode(PDO::FETCH_CLASS, 'StdClass');
      $sth->execute();
      $record = $sth->fetch();

      if ($record) {
        $file = new FileEntity();
        $file->FileID = $fileID;
        $file->FileName = $record->FileName;
        $file->Size = $record->Size;
        $file->UserID = $record->UserID;
        $file->ContentType = $record->ContentType;
        $file->UploadedOn = $record->UploadedOn;
      }
    }
    catch(PDOException $e) {
      throw new DatabaseException($e);
    }

    return $file;
  }

  public function DeleteFile($fileID) {

  }

  public function SaveFile(FileSaveRequest $request) {
    if (!is_uploaded_file($request->UploadedPath))
      throw new LogicException('Invalid file to save');

    if (isset($request->FileID))
      throw new LogicException('FileID must be null.');

    trim_to_null($request->FileName);

    if (empty($request->FileName)) {
      return self::ERROR_FILE_NAME_EMPTY;
    }

    if (mb_strlen($request->FileName) > FileEntity::MAX_NAME_LENGTH) {
      return self::ERROR_FILE_NAME_TOO_LONG;
    }

    $fileSize = filesize($request->UploadedPath);
    if ($fileSize > FileEntity::MAX_FILE_SIZE) {
      return self::ERROR_FILE_SIZE_TOO_BIG;
    }

    //TODO: Check if the file name contains invalid characters. YES, we should do this!
    // IE supports 147 symbols only?
    // Quotes should be eliminated.
    // Implement client-side verification as well

    $permanentPath = null;
    $dbh = $this->dbh();

    try {
      $sth = $dbh->prepare(self::QUERY_INSERT_FILE);
      $sth->bindValue(':UserID', $request->UserID, PDO::PARAM_INT);
      $sth->bindValue(':FileName', $request->FileName);
      $sth->bindValue(':Size', $fileSize, PDO::PARAM_INT);
      $sth->bindValue(':ContentType', $request->ContentType, PDO::PARAM_INT);
      $sth->bindValue(':MaxFilesPerUser', self::MAX_FILES_PER_USER, PDO::PARAM_INT);

      $dbh->beginTransaction();
      $sth->execute();

      if ($sth->rowCount() == 0) {
        $dbh->rollBack();
        return self::ERROR_FILE_LIMIT_EXCEEDED;
      }

      $fileID = $this->dbh()->lastInsertId();
      $permanentPath = $this->storagePath . $fileID;
      if (!@move_uploaded_file($request->UploadedPath, $permanentPath)) {
        $dbh->rollBack();
        return self::ERROR_SERVER_FAILURE;
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
      if ($e->getCode() == self::SQL_ER_DUP_ENTRY) {
        return self::ERROR_USER_DOES_NOT_EXISTS;
      }
      else {
        throw new DatabaseException($e);
      }
    }

    return self::OPERATION_SUCCEEDED;
  }

  const QUERY_SELECT_FILE = <<<SQL
    SELECT
      UserID,
      FileName,
      Size,
      ContentType,
      UploadedOn
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
      UserID = :UserID
    AND
      DeletedOn IS NULL
    HAVING
      COUNT(*) < :MaxFilesPerUser
SQL;
}
