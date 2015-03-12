<?php
require_once __DIR__ . '/bag.php';
require_once __DIR__ . '/strings.php';
require_once __DIR__ . '/../../classes/controls/messagebox/control.php';

abstract class LayoutView {
  abstract protected function RenderBody();
  abstract protected function BeforeLayoutRender();

  /** @var  LayoutViewStrings */
  public $LayoutStrings;
  public $Bag;

  protected $messageBoxRequired = false;
  protected $focusedElement;

  //TODO: Remove the 'Bag' concept completely? Is it unnecessary? We can keep the data directly in the view's fields
  protected function __construct(LayoutViewBag $bag, $language) {
    $this->Bag = $bag;
    $this->LayoutStrings = LayoutViewStrings::GetInstance($language);
  }

  public function Render() {
    $this->BeforeLayoutRender();

    $bag = $this->Bag;
    $bag->scripts[] = '/views/layout/scripts/main.js';
    $bag->stylesheets[] = '/views/layout/css/main.css';
    $bag->stylesheets[] = '/views/layout/css/drop-menu.css';

    $this->messageBoxRequired = $this->messageBoxRequired || isset($bag->alert);

    if ($this->messageBoxRequired) {
      $bag->stylesheets[] = '/classes/controls/messagebox/css/style.css';
      $bag->scripts[] = '/classes/controls/messagebox/scripts/script.js';
    }

    require 'template.php';
  }
}