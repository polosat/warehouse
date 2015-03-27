<?php
class EditProfileViewStrings extends Strings {
  const HEADER_TITLE_NEW_USER     = 'Create account';
  const HEADER_TITLE_EDIT_PROFILE = 'Change account';

  const FORM_TITLE                = 'Profile';

  const LABEL_LOGIN               = 'Login';
  const LABEL_PASSWORD_CURRENT    = 'Current password';
  const LABEL_PASSWORD            = 'Password';
  const LABEL_PASSWORD_CONFIRM    = 'Confirm password';
  const LABEL_FIRST_NAME          = 'First name';
  const LABEL_LAST_NAME           = 'Last name';
  const LABEL_EMAIL               = 'E-Mail';
  const LABEL_PHONE               = 'Phone';
  const LABEL_BIRTHDAY            = 'Birthday';

  const BUTTON_REGISTER           = 'OK';
  const BUTTON_CHANGE             = 'Change';
  const BUTTON_CANCEL             = 'Cancel';

  const HINT_PASSWORD             = 'Minimum 8 characters, at least one digit and one uppercase latin letter. ';
  const HINT_EMPTY_PASSWORD       = 'Leave this field empty if you don\'t want to change your password.';
  const HINT_PASSWORD_CONFIRM     = 'Enter the password again, exactly as in the previous field.';
  const HINT_PHONE                = 'A phone number including country code. Only digits, dashes and spaces are allowed.';
  const HINT_PASSWORD_CURRENT     = 'Enter your current password for security purposes.';
  const HINT_MANDATORY            = 'mandatory field';

  const ERROR_NOT_CHANGED         = 'You have not made any changes';
  const ERROR_LOGIN               = 'Please enter your login name';
  const ERROR_LOGIN_EXISTS        = 'This login name has been taken already';
  const ERROR_FIRST_NAME          = 'Please enter your first name';
  const ERROR_LAST_NAME           = 'Please enter your last name';
  const ERROR_PASSWORD_PATTERN    = 'The password does not meet the requirements';
  const ERROR_PASSWORD_CONFIRM    = 'The password and its confirmation are not equal';
  const ERROR_NOT_AUTHENTICATED   = 'Incorrect password';
  const ERROR_EMAIL_FORMAT        = 'This is not valid email address';
  const ERROR_PHONE_FORMAT        = 'The phone number does not correspond required pattern';
  const ERROR_BIRTHDAY            = 'The date entered is invalid';

  const MESSAGE_NEW_OK            = 'The new account has been successfully created.<br/>Use your login and password to access the system.';
}

class EditProfileViewStrings_RU extends EditProfileViewStrings {
  const HEADER_TITLE_NEW_USER     = 'Регистрация';
  const HEADER_TITLE_EDIT_PROFILE = 'Изменение учётной записи';

  const FORM_TITLE                = 'Информация о пользователе';

  const LABEL_LOGIN               = 'Имя пользователя';
  const LABEL_PASSWORD_CURRENT    = 'Текущий пароль';
  const LABEL_PASSWORD            = 'Пароль';
  const LABEL_PASSWORD_CONFIRM    = 'Подтверждение пароля';
  const LABEL_FIRST_NAME          = 'Имя';
  const LABEL_LAST_NAME           = 'Фамилия';
  const LABEL_EMAIL               = 'Адрес электронной почты';
  const LABEL_PHONE               = 'Телефон';
  const LABEL_BIRTHDAY            = 'Дата рождения';

  const BUTTON_REGISTER           = 'OK';
  const BUTTON_CHANGE             = 'Изменить';
  const BUTTON_CANCEL             = 'Отмена';

  const HINT_PASSWORD             = 'Не менее 8-ми символов, одна цифра и одна заглавная латинская буква. ';
  const HINT_EMPTY_PASSWORD       = 'Оставьте это поле пустым, если не хотите менять текущий пароль.';
  const HINT_PASSWORD_CONFIRM     = 'Введите пароль точно так же, как и в предыдущем поле. ';
  const HINT_PHONE                = 'Номер телефона, включая код страны. Только цифры, тире и пробелы.';
  const HINT_PASSWORD_CURRENT     = 'В целях безопасности укажите ваш текущий пароль.';
  const HINT_MANDATORY            = 'обязательное поле';

  const ERROR_NOT_CHANGED         = 'Вы не внесли никаких изменений в персональные данные.';
  const ERROR_LOGIN               = 'Пожалуйста, укажите имя пользователя';
  const ERROR_LOGIN_EXISTS        = 'Это имя пользователя уже занято';
  const ERROR_FIRST_NAME          = 'Пожалуйста, введите ваше имя';
  const ERROR_LAST_NAME           = 'Пожалуйста, введите вашу фамилию';
  const ERROR_PASSWORD_PATTERN    = 'Пароль не соответствует требованиям безопасности';
  const ERROR_PASSWORD_CONFIRM    = 'Подтверждение не совпадает с введённым паролем';
  const ERROR_NOT_AUTHENTICATED   = 'Неверный пароль';
  const ERROR_EMAIL_FORMAT        = 'Недопустимый адрес электронной почты';
  const ERROR_PHONE_FORMAT        = 'Номер телефона не соответствует требуемому формату';
  const ERROR_BIRTHDAY            = 'Неверная дата';

  const MESSAGE_NEW_OK            = 'Новая учётная запись успешно зарегистрирована.<br/>Введите ваше имя пользователя и пароль для входа в систему.';
}