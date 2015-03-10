<?php
require_once 'bag.php';
require_once 'strings.php';
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

  /** @var  EditProfileViewStrings */
  public $ProfileStrings;

  protected $birthdayPicker;
  protected $phoneField;

  public function __construct(EditProfileViewBag $bag, $language) {
    parent::__construct($bag, $language);
    $this->ProfileStrings = EditProfileViewStrings::GetInstance($language);

    $this->Bag->stylesheets[] = '/views/profile/edit/css/style.css';

    $this->Bag->stylesheets[] = '/classes/controls/datepicker/css/style.css';
    $this->Bag->scripts[] = '/classes/controls/datepicker/scripts/script.js';
    $this->Bag->stylesheets[] = '/classes/controls/phonefield/css/style.css';
    $this->Bag->scripts[] = '/classes/controls/phonefield/scripts/script.js';
    $this->birthdayPicker = new DatePicker(
      EditProfileView::FIELD_NAME_BIRTHDAY,
      $language,
      ProfileModel::GetMinAllowedBirthdayDate(),
      ProfileModel::GetMaxAllowedBirthdayDate()
    );

    $this->phoneField = new PhoneField(self::FIELD_NAME_PHONE);
  }

  public function Render() {
    /** @var EditProfileViewBag $bag */
    $bag = $this->Bag;
    $user = $bag->User;
    $strings = $this->ProfileStrings;
    $bag->headerTitle = isset($user->UserID) ? $strings::HEADER_TITLE_EDIT_PROFILE : $strings::HEADER_TITLE_NEW_USER;
    parent::Render();
  }

  protected function RenderBody() {
    require 'template.php';
  }
}