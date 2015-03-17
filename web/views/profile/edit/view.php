<?php
require_once __DIR__ . '/bag.php';
require_once __DIR__ . '/strings.php';
require_once __DIR__ . '/../../../classes/controls/datepicker/control.php';
require_once __DIR__ . '/../../../classes/controls/phonefield/control.php';

class EditProfileView extends LayoutView {
  const FIELD_NAME_FIRST_NAME       = 'firstName';
  const FIELD_NAME_LAST_NAME        = 'lastName';
  const FIELD_NAME_LOGIN            = 'login';
  const FIELD_NAME_PASSWORD         = 'password';
  const FIELD_NAME_PASSWORD_CONFIRM = 'confirmPassword';
  const FIELD_NAME_PASSWORD_CURRENT = 'currentPassword';
  const FIELD_NAME_BIRTHDAY         = 'birthday';
  const FIELD_NAME_EMAIL            = 'email';
  const FIELD_NAME_PHONE            = 'phone';

  public $ProfileStrings;
  public $Bag;

  protected $birthdayPicker;
  protected $phoneField;

  public function __construct(EditProfileViewBag $bag, $language) {
    /** @var EditProfileViewStrings $strings */
    $strings = EditProfileViewStrings::GetInstance($language);

    parent::__construct($language);

    $this->ProfileStrings = $strings;
    $this->Bag = $bag;

    $this->birthdayPicker = new DatePicker(
      self::FIELD_NAME_BIRTHDAY,
      $strings->GetLanguage(),
      ProfileModel::GetMinAllowedBirthdayDate(),
      ProfileModel::GetMaxAllowedBirthdayDate()
    );

    $this->phoneField = new PhoneField(self::FIELD_NAME_PHONE);
  }

  public function BeforeLayoutRender() {
    $bag = $this->Bag;
    $user = $bag->User;
    $strings = $this->ProfileStrings;

    $this->stylesheets[] = '/views/profile/edit/css/style.css';

    $this->stylesheets[] = '/classes/controls/datepicker/css/style.css';
    $this->scripts[] = '/classes/controls/datepicker/scripts/script.js';

    $this->stylesheets[] = '/classes/controls/phonefield/css/style.css';
    $this->scripts[] = '/classes/controls/phonefield/scripts/script.js';

    $this->headerTitle = isset($user->UserID) ? $strings::HEADER_TITLE_EDIT_PROFILE : $strings::HEADER_TITLE_NEW_USER;

    if (count($bag->validationErrors) > 0) {
      reset($bag->validationErrors);
      $this->focusedElement = key($bag->validationErrors);
    }
    else {
      $this->focusedElement = self::FIELD_NAME_LOGIN;
    }
  }

  public function Render() {
    parent::Render();
  }

  protected function RenderBody() {
    require 'template.php';
  }
}