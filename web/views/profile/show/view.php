<?php
require_once __DIR__ . '/bag.php';
require_once __DIR__ . '/strings.php';
require_once __DIR__ . '/../../../classes/controls/datepicker/control.php';

class ShowProfileView extends LayoutView {
  public $ProfileStrings;
  public $Bag;

  public function __construct(ShowProfileViewBag $bag, $language) {
    /** @var  ShowProfileViewStrings $strings*/
    $strings = ShowProfileViewStrings::GetInstance($language);

    parent::__construct($language);
    $this->ProfileStrings = $strings;
    $this->Bag = $bag;
  }

  protected function FormatBirthday() {
    $birthday = $this->Bag->User->Birthday;
    $language = $this->ProfileStrings->GetLanguage();

    if (isset($birthday)) {
      $date = DateTime::createFromFormat('d/m/Y', $birthday);
      $formatted = DatePicker::FormatDate($language, $date);
      return mb_strtolower($formatted);
    }
    else {
      return null;
    }
  }

  protected function BeforeLayoutRender() {
    $strings = $this->ProfileStrings;

    $this->HeaderTitle = $strings::HEADER_TITLE;
    $this->Stylesheets[] = '/views/profile/show/css/style.css';
  }

  protected function RenderBody() {
    require 'template.php';
  }
}