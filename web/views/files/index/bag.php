<?php
class FilesViewBag {
  /** @var FileViewItem[] */
  public $Files = array();
  public $TotalSize;
  public $UploadUri;
  public $DeleteUri;
  public $StatusUri;
}

class FileViewItem {
  public $ID;
  public $Name;
  public $Size;
  public $UploadedOn;
  public $Uri;
}
