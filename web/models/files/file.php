<?php
class FileEntity {
  const MAX_NAME_LENGTH = 250;
  const WRONG_NAME_MASK = '/[\/\\\*\?:\|\"\<\>]+/';

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