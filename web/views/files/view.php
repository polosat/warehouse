<?php
require_once 'bag.php';
require_once 'strings.php';

class FilesView extends LayoutView {
  const FIELD_NAME_USER_FILE  = 'userFile';
  const MINIMUM_FILE_ROWS     = 12;

  /** @var FilesViewStrings */
  public $FilesStrings;

  public function __construct(FilesViewBag $bag, $language) {
    parent::__construct($bag, $language);
    $this->FilesStrings = FilesViewStrings::GetInstance($language);
  }

  protected function BeforeLayoutRender() {
    $bag = $this->Bag;
    $strings = $this->FilesStrings;
    $this->messageBoxRequired = true;
    $bag->headerTitle = $strings::HEADER_TITLE;
    $this->Bag->stylesheets[] = '/views/files/css/style.css';
  }

  protected function RenderBody() {
    require 'template.php';
  }

  //TODO: Move to settings
  const MB = 1048576;
  protected function FormatSize($bytes) {
    $strings = $this->FilesStrings;
    return $bytes < 1024 ? $bytes : (ceil($bytes / 1024) . ' ' . $strings::UNIT_KILOBYTES);
  }
}