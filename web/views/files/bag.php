<?php
class FilesViewBag extends LayoutViewBag {
  /** @var  FileViewItem[] */
  public $Files = array();
  public $DeleteUri;
}

class FileViewItem {
  public $ID;
  public $Name;
  public $Size;
  public $UploadedOn;
  public $Uri;
}
