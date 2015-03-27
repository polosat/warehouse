<?php
class LoginViewStrings extends Strings {
  const HEADER_TITLE                  = 'Welcome';
  const TITLE_LOGIN_FORM              = 'Login';

  const LABEL_LOGIN                   = 'User name';
  const LABEL_PASSWORD                = 'Password';
  const BUTTON_LOGIN                  = 'Enter';

  const ERROR_AUTHENTICATION_FAILED   = 'Invalid user name or password.';
  const ERROR_AUTHENTICATION_REQUIRED = 'Authentication is required to perform this operation.';
  const ERROR_SESSION_EXPIRED         = 'Your session has expired due to an extended period of inactivity.<br />Enter your user name and password to continue.';
  const ERROR_UNKNOWN_USER            = 'The user does not exist.';
}

class LoginViewStrings_RU extends LoginViewStrings {
  const HEADER_TITLE                  = 'Добро пожаловать';
  const TITLE_LOGIN_FORM              = 'Вход в систему';

  const LABEL_LOGIN                   = 'Имя пользователя';
  const LABEL_PASSWORD                = 'Пароль';
  const BUTTON_LOGIN                  = 'Вход';

  const ERROR_AUTHENTICATION_FAILED   = 'Неверное имя пользователя или пароль.';
  const ERROR_AUTHENTICATION_REQUIRED = 'Для продолжения работы необходимо ввести имя пользователя и пароль.';
  const ERROR_SESSION_EXPIRED         = 'Вы были отключены по причине длительного времени бездействия.<br />Для возобновления работы введите ваше имя пользователя и пароль.';
  const ERROR_UNKNOWN_USER            = 'Пользователь не найден.';
}