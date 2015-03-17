<?php
require_once __DIR__ . '/bag.php';
require_once __DIR__ . '/strings.php';

class FilesView extends LayoutView {
  const FIELD_NAME_USER_FILE  = 'userFile';
  const MINIMUM_FILE_ROWS     = 12;

  public $FilesStrings;
  public $Bag;

  public function __construct(FilesViewBag $bag, $language) {
    /** @var FilesViewStrings $strings */
    $strings = FilesViewStrings::GetInstance($language);

    parent::__construct($language);
    $this->FilesStrings = $strings;
    $this->Bag = $bag;
  }

  protected function BeforeLayoutRender() {
    $strings = $this->FilesStrings;

    $this->messageBoxRequired = true;
    $this->headerTitle = $strings::HEADER_TITLE;
    $this->stylesheets[] = '/views/files/css/style.css';
  }

  protected function RenderBody() {
    require 'template.php';
  }

  protected function FormatSize($bytes) {
    $strings = $this->LayoutStrings;
    return $bytes < 1024 ? $bytes : (ceil($bytes / 1024) . ' ' . $strings::UNIT_KILOBYTES);
  }
}