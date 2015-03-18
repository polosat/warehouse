<?php
require_once __DIR__ . '/bag.php';
require_once __DIR__ . '/strings.php';

class FilesView extends LayoutView {
  const FIELD_NAME_UPLOADED_FILE  = 'uploaded_file';
  const FIELD_NAME_SELECTED_FILE  = 'selected_file';

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

    $this->MessageBoxRequired = true;
    $this->HeaderTitle = $strings::HEADER_TITLE;
    $this->Stylesheets[] = '/views/files/css/style.css';
    $this->Scripts[] = '/views/files/scripts/script.js';
  }

  protected function RenderBody() {
    require 'template.php';
  }
}