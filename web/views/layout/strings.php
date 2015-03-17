<?php
class LayoutViewStrings extends Strings {
  const APPLICATION_NAME      = 'Storage';

  const LANGUAGE_EN           = 'English';
  const LANGUAGE_RU           = 'Русский';
  const LANGUAGE_DE           = 'Deutsch';
  const LANGUAGE_FR           = 'Français';

  const HEADER_ITEM_FILES     = 'files';
  const HEADER_ITEM_PROFILE   = 'profile';
  const HEADER_ITEM_NEW_USER  = 'sign up';
  const HEADER_ITEM_LOGIN     = 'login';
  const HEADER_ITEM_LOGOUT    = 'logout';

  const TIME_FORMAT_H24       = 0;
  const UNIT_KILOBYTES        = 'KB';
  const UNIT_MEGABYTES        = 'MB';
  const DECIMAL_POINT         = '.';
}

class LayoutViewStrings_RU extends LayoutViewStrings {
  const HEADER_ITEM_FILES     = 'файлы';
  const HEADER_ITEM_PROFILE   = 'пользователь';
  const HEADER_ITEM_NEW_USER  = 'регистрация';
  const HEADER_ITEM_LOGIN     = 'вход';
  const HEADER_ITEM_LOGOUT    = 'выход';

  const TIME_FORMAT_H24       = 1;
  const UNIT_KILOBYTES        = 'КБ';
  const UNIT_MEGABYTES        = 'МБ';
  const DECIMAL_POINT         = ',';
}