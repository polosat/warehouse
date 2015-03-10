<?php
require_once __DIR__ . '/bag.php';
require_once __DIR__ . '/strings.php';
require_once __DIR__ . '/../../classes/controls/messagebox/control.php';

abstract class LayoutView {
  abstract protected function RenderBody();

  /** @var  LayoutViewStrings */
  public $LayoutStrings;
  public $Bag;

  //TODO: Remove the 'Bag' concept completely? Is it unnecessary? We can keep the data directly in the view's fields
  protected function __construct(LayoutViewBag $bag, $language) {
    $this->Bag = $bag;
    $this->LayoutStrings = LayoutViewStrings::GetInstance($language);

    $bag->scripts[] = '/views/layout/scripts/main.js';
    $bag->stylesheets[] = '/views/layout/css/main.css';
    $bag->stylesheets[] = '/views/layout/css/drop-menu.css';
  }

  public function Render() {
    $bag = $this->Bag;

    if (isset($bag->messageBox)) {
      $bag->stylesheets[] = '/classes/controls/messagebox/css/style.css';
    }

    require 'template.php';
  }
}