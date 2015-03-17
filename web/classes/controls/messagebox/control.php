<?php
require_once __DIR__ . '/strings.php';

class MessageBox {
  const TYPE_INFO   = 0;
  const TYPE_ERROR  = 1;

  const MB_OK       = 1;
  const MB_CANCEL   = 2;
  const MB_YES      = 4;
  const MB_NO       = 8;

  public $Text;
  public $Type;
  public $Buttons;

  public function __construct($text, $type = self::TYPE_ERROR, $buttons = self::MB_OK) {
    $this->Text = $text;
    $this->Type = $type;
    $this->Buttons = $buttons;
  }

  static public function GetButtonTitles($language) {
    /** @var MessageBoxStrings $strings */
    $strings = MessageBoxStrings::GetInstance($language);

    return json_encode(
      array(
        $strings::BUTTON_OK,
        $strings::BUTTON_CANCEL,
        $strings::BUTTON_YES,
        $strings::BUTTON_NO
      ),
      JSON_UNESCAPED_UNICODE
    );
  }
}