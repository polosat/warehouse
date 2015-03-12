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
  }

  protected function BeforeLayoutRender() {
    /** @var LoginViewBag $bag */
    $bag = $this->Bag;

    $strings = $this->LoginStrings;
    $bag->headerTitle = $strings::HEADER_TITLE;
    $bag->stylesheets[] = '/views/login/css/style.css';
    $this->focusedElement = empty($bag->userName) ? LoginView::FIELD_NAME_LOGIN : LoginView::FIELD_NAME_PASSWORD;
  }

  protected function RenderBody() {
    require 'template.php';
  }
}