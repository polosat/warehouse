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

  protected $kb;
  protected $gb;
  protected $mb;
  protected $system_point;
  protected $replace_system_point;

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
    $this->kb = Settings::SHOW_DECIMAL_SIZE ? 1000 : 1024;
    $this->mb = $this->kb * $this->kb;
    $this->gb = $this->mb * $this->kb;
    $this->system_point = localeconv()['decimal_point'];
    $this->replace_system_point = ($this->system_point != $strings::DECIMAL_POINT);
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

  protected function FormatSize($bytes, $precision = 0, $skipBytesUnits = true) {
    $strings = $this->LayoutStrings;

    if ($bytes >= $this->gb) {
      $result = round_or_floor($bytes / $this->gb, $precision) . ' ' . $strings::UNIT_GIGABYTES;
    }
    elseif ($bytes >= $this->mb) {
      $result = round_or_floor($bytes / $this->mb, $precision) . ' ' . $strings::UNIT_MEGABYTES;
    }
    elseif ($bytes >= $this->kb) {
      $result = round_or_floor($bytes / $this->kb, $precision) . ' ' . $strings::UNIT_KILOBYTES;
    }
    else {
      $result = $bytes . ($skipBytesUnits ? '' : (' ' . $strings::UNIT_BYTES));
    }

    // We do not use number_format() here as we don't need trailing decimal zeroes
    return ($precision > 0 && $this->replace_system_point) ?
      str_replace($this->system_point, $strings::DECIMAL_POINT, $result):
      $result;
  }
}