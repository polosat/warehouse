<?php
require_once 'bag.php';
require_once 'strings.php';
require_once __DIR__ . '/../../classes/utils.php';

class LoginView extends LayoutView {
  const FIELD_NAME_LOGIN = 'login';
  const FIELD_NAME_PASSWORD = 'password';

  /** @var LoginViewStrings */
  public $LoginStrings;

  public function __construct(LoginViewBag $bag, $language) {
    parent::__construct($bag, $language);
    $this->LoginStrings = LoginViewStrings::GetInstance($language);

    $strings = $this->LoginStrings;
    $this->Bag->headerTitle = $strings::HEADER_TITLE;

    $this->Bag->stylesheets[] = '/views/login/css/style.css';
  }

  protected function RenderBody() {
    require 'template.php';
  }
}