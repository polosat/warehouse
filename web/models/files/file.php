<?php
class FileEntity {
  const MAX_FILE_SIZE   = 1000000;
  const MAX_NAME_LENGTH = 255;

  public $FileID;
  public $UserID;
  public $FileName;
  public $Size;
  public $ContentType;
  public $UploadedOn;
  public $DeletedOn;
}

class FileSaveRequest {
  public $UploadedPath;
  public $FileName;
  public $UserID;
  public $ContentType;
}