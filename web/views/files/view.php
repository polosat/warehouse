<?php
require_once 'bag.php';
require_once 'strings.php';

class FilesView extends LayoutView {
  /** @var FilesViewStrings */
  public $FilesStrings;

  public function __construct(FilesViewBag $bag, $language) {
    parent::__construct($bag, $language);
    $this->FilesStrings = FilesViewStrings::GetInstance($language);
  }

  protected function RenderBody() {
    require 'template.php';
  }
}