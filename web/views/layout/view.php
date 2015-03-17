<?php
require_once __DIR__ . '/strings.php';
require_once __DIR__ . '/items.php';
require_once __DIR__ . '/../../classes/controls/messagebox/control.php';

abstract class LayoutView {
  abstract protected function RenderBody();
  abstract protected function BeforeLayoutRender();

  public $LayoutStrings;

  public $stylesheets;
  public $scripts;

  public $headerTitle;
  public $headerItems;
  public $languageItems = array();

  public $alert;
  public $messageBoxRequired = false;

  public $focusedElement;

  protected function __construct($language) {
    /** @var LayoutViewStrings  $strings */
    /** @var HeaderItem[]       $headerItems */
    /** @var LanguageItem[]     $languageItems */
    /** @var MessageBox         $alert */

    $strings = LayoutViewStrings::GetInstance($language);
    $headerItems = array();
    $languageItems = array();
    $alert = null;

    $this->headerItems = $headerItems;
    $this->languageItems = $languageItems;
    $this->LayoutStrings = $strings;
    $this->stylesheets = array();
    $this->scripts = array();
    $this->headerTitle = '';
    $this->alert = $alert;
  }

  public function Render() {
    $this->BeforeLayoutRender();

    $this->scripts[] = '/views/layout/scripts/main.js';
    $this->stylesheets[] = '/views/layout/css/main.css';
    $this->stylesheets[] = '/views/layout/css/drop-menu.css';

    $this->messageBoxRequired = $this->messageBoxRequired || isset($this->alert);

    if ($this->messageBoxRequired) {
      $this->stylesheets[] = '/classes/controls/messagebox/css/style.css';
      $this->scripts[] = '/classes/controls/messagebox/scripts/script.js';
    }

    require 'template.php';
  }
}