<?php
require_once __DIR__ . '/bag.php';
require_once __DIR__ . '/strings.php';
require_once __DIR__ . '/../../classes/utils.php';

class LoginView extends LayoutView {
  const FIELD_NAME_LOGIN = 'login';
  const FIELD_NAME_PASSWORD = 'password';

  public $LoginStrings;
  public $Bag;

  public function __construct(LoginViewBag $bag, $language) {
    /** @var LoginViewStrings $strings */
    $strings = LoginViewStrings::GetInstance($language);

    parent::__construct($language);
    $this->LoginStrings = $strings;
    $this->Bag = $bag;
  }

  protected function BeforeLayoutRender() {
    $bag = $this->Bag;
    $strings = $this->LoginStrings;

    $this->headerTitle = $strings::HEADER_TITLE;
    $this->stylesheets[] = '/views/login/css/style.css';
    $this->focusedElement = empty($bag->userName) ? LoginView::FIELD_NAME_LOGIN : LoginView::FIELD_NAME_PASSWORD;
  }

  protected function RenderBody() {
    require 'template.php';
  }
}