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
      'birthday',
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

    $this->Stylesheets[] = '/views/profile/edit/css/style.css';

    $this->Stylesheets[] = '/classes/controls/datepicker/css/style.css';
    $this->Scripts[] = '/classes/controls/datepicker/scripts/script.js';

    $this->Stylesheets[] = '/classes/controls/phonefield/css/style.css';
    $this->Scripts[] = '/classes/controls/phonefield/scripts/script.js';

    $this->HeaderTitle = isset($user->UserID) ? $strings::HEADER_TITLE_EDIT_PROFILE : $strings::HEADER_TITLE_NEW_USER;

    if (count($bag->ValidationErrors) > 0) {
      reset($bag->ValidationErrors);
      $this->FocusedElement = key($bag->ValidationErrors);
    }
    else {
      $this->FocusedElement = self::FIELD_NAME_LOGIN;
    }
  }

  public function Render() {
    parent::Render();
  }

  protected function RenderBody() {
    require 'template.php';
  }
}