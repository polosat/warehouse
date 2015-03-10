<?php
class LayoutViewStrings extends Strings {
  const APPLICATION_NAME      = 'Storage';

  const LANGUAGE_EN           = 'English';
  const LANGUAGE_RU           = 'Русский';
  const LANGUAGE_DE           = 'Deutsch';
  const LANGUAGE_FR           = 'Français';

  const HEADER_ITEM_FILES     = 'file storage';
  const HEADER_ITEM_PROFILE   = 'user profile';
  const HEADER_ITEM_NEW_USER  = 'sign up';
  const HEADER_ITEM_LOGIN     = 'login';
  const HEADER_ITEM_LOGOUT    = 'logout';
}

class LayoutViewStrings_RU extends LayoutViewStrings {
  const HEADER_ITEM_FILES     = 'файлы';
  const HEADER_ITEM_PROFILE   = 'пользователь';
  const HEADER_ITEM_NEW_USER  = 'регистрация';
  const HEADER_ITEM_LOGIN     = 'вход';
  const HEADER_ITEM_LOGOUT    = 'выход';
}