<?php
require_once __DIR__ . '/../../strings.php';

class MessageBoxStrings extends Strings {
  const BUTTON_OK     = 'OK';
  const BUTTON_CANCEL = 'Cancel';
  const BUTTON_YES    = 'Yes';
  const BUTTON_NO     = 'No';
}

class MessageBoxStrings_RU extends MessageBoxStrings {
  const BUTTON_OK     = 'OK';
  const BUTTON_CANCEL = 'Отмена';
  const BUTTON_YES    = 'Да';
  const BUTTON_NO     = 'Нет';
}
