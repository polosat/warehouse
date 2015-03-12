<?php
require_once 'bag.php';
require_once 'strings.php';

class FilesView extends LayoutView {
  const FIELD_NAME_USER_FILE = 'userFile';

  /** @var FilesViewStrings */
  public $FilesStrings;

  public function __construct(FilesViewBag $bag, $language) {
    parent::__construct($bag, $language);
    $this->FilesStrings = FilesViewStrings::GetInstance($language);
  }

  protected function BeforeLayoutRender() {
    $this->messageBoxRequired = true;
    $this->Bag->stylesheets[] = '/views/files/css/style.css';
  }

  protected function RenderBody() {
    require 'template.php';
  }
}