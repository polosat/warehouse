<?php
require_once 'bag.php';
require_once 'strings.php';
require_once __DIR__ . '/../../../classes/controls/datepicker/control.php';

class ShowProfileView extends LayoutView {
  /** @var  ShowProfileViewStrings */
  public $ProfileStrings;

  public function __construct(ShowProfileViewBag $bag, $language) {
    parent::__construct($bag, $language);

    $this->ProfileStrings = ShowProfileViewStrings::GetInstance($language);
    $strings = $this->ProfileStrings;
    $this->Bag->headerTitle = $strings::HEADER_TITLE;
    $this->Bag->stylesheets[] = '/views/profile/show/css/style.css';
  }

  protected function FormatBirthday() {
    /** @var ShowProfileViewBag $bag */
    $bag = $this->Bag;
    $birthday = $bag->User->Birthday;
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

  protected function RenderBody() {
    require 'template.php';
  }
}