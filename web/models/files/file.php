<?php
class FileEntity {
  // TODO: We should investigate here: what does mean 1M in nginx and php configuration files, and do the same in the code
  const MAX_FILE_SIZE   = 1048576; //1000000
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