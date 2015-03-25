<?php
require_once __DIR__ . '/../../classes/strings.php';

class NotFoundViewStrings extends Strings {
  const PAGE_TITLE  = 'Page not found';
  const PAGE_TEXT   = 'Sorry, but the page you are looking for has not been found.<br/>Check the URL for errors and try again, or go to the %s page: ';
  const TEXT_START  = 'start';
}

class NotFoundViewStrings_RU extends NotFoundViewStrings {
  const PAGE_TITLE = 'Страница не найдена';
  const PAGE_TEXT = 'К сожалению, запрошенная вами страница не найдена.<br/>Исправьте ошибки в URL страницы, и попробуйте ещё раз.<br/> Или перейдите на %s страницу: ';
  const TEXT_START  = 'начальную';
}