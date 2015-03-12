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
  }

  public function BeforeLayoutRender() {
    /** @var EditProfileViewBag $bag */
    $bag = $this->Bag;
    $user = $bag->User;
    $strings = $this->ProfileStrings;

    $bag->stylesheets[] = '/views/profile/edit/css/style.css';
    $bag->stylesheets[] = '/classes/controls/datepicker/css/style.css';
    $bag->scripts[] = '/classes/controls/datepicker/scripts/script.js';
    $bag->stylesheets[] = '/classes/controls/phonefield/css/style.css';
    $bag->scripts[] = '/classes/controls/phonefield/scripts/script.js';

    $bag->headerTitle = isset($user->UserID) ? $strings::HEADER_TITLE_EDIT_PROFILE : $strings::HEADER_TITLE_NEW_USER;

    $this->birthdayPicker = new DatePicker(
      EditProfileView::FIELD_NAME_BIRTHDAY,
      $strings->GetLanguage(),
      ProfileModel::GetMinAllowedBirthdayDate(),
      ProfileModel::GetMaxAllowedBirthdayDate()
    );

    $this->phoneField = new PhoneField(self::FIELD_NAME_PHONE);

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