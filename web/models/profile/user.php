<?php
class UserEntity {
  const FIRST_NAME_MAX_LENGTH       = 255;
  const LAST_NAME_MAX_LENGTH        = 255;
  const LOGIN_MAX_LENGTH            = 32;
  const PASSWORD_MAX_LENGTH         = 64;
  const PASSWORD_MIN_LENGTH         = 1;
//  const PASSWORD_MIN_LENGTH         = 8;
  // TODO: allow latin letters only as Mac doesn't allow to enter non latin letters
//  const PASSWORD_PATTERN            = '/(?=.*\d)(?=.*[A-Z])/';
  const PASSWORD_PATTERN            = '/.*/';
  const EMAIL_MAX_LENGTH            = 255;
  const PHONE_MAX_LENGTH            = 32;
  const PHONE_PATTERN               = '/^\d[\d\ \-]+\d$/';
  const MIN_BIRTHDAY_DATE           = '1880-01-01';

  public $UserID;
  public $Login;
  public $Password;
  public $FirstName;
  public $LastName;
  public $Birthday;
  public $EMail;
  public $Phone;

  public function Checksum() {
    $content = '';
    foreach (get_object_vars($this) as $field => $value) {
      if ($field != 'Password') {
        $content .= "$field:$value;";
      }
    }
    $checksum = substr(
      str_replace('+', '.',
        base64_encode(
          md5($content, true)
        )
      ), 0, 22
    );
    return $checksum;
  }
}

class ProfileChangeRequest {
  public $User;
  public $PasswordConfirmation;
  public $CurrentPassword;

  public function __construct(UserEntity $user) {
    $this->User = $user;
  }
}
