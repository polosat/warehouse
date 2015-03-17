<?php
require_once __DIR__ . '/strings.php';
require_once __DIR__ . '/items.php';
require_once __DIR__ . '/../../classes/controls/messagebox/control.php';

abstract class LayoutView {
  abstract protected function RenderBody();
  abstract protected function BeforeLayoutRender();

  public $LayoutStrings;

  public $Stylesheets;
  public $Scripts;

  public $HeaderTitle;
  public $HeaderItems;
  public $LanguageItems = array();

  public $Alert;
  public $MessageBoxRequired = false;

  public $FocusedElement;

  protected function __construct($language) {
    /** @var LayoutViewStrings  $strings */
    /** @var HeaderItem[]       $headerItems */
    /** @var LanguageItem[]     $languageItems */
    /** @var MessageBox         $alert */

    $strings = LayoutViewStrings::GetInstance($language);
    $headerItems = array();
    $languageItems = array();
    $alert = null;

    $this->HeaderItems = $headerItems;
    $this->LanguageItems = $languageItems;
    $this->LayoutStrings = $strings;
    $this->Stylesheets = array();
    $this->Scripts = array();
    $this->HeaderTitle = '';
    $this->Alert = $alert;
  }

  public function Render() {
    $this->BeforeLayoutRender();

    $this->Scripts[] = '/views/layout/scripts/main.js';
    $this->Stylesheets[] = '/views/layout/css/main.css';
    $this->Stylesheets[] = '/views/layout/css/drop-menu.css';

    $this->MessageBoxRequired = $this->MessageBoxRequired || isset($this->Alert);

    if ($this->MessageBoxRequired) {
      $this->Stylesheets[] = '/classes/controls/messagebox/css/style.css';
      $this->Scripts[] = '/classes/controls/messagebox/scripts/script.js';
    }

    require 'template.php';
  }
}