<?php
class MessageBox {
  const TYPE_ERROR  = 1;
  const TYPE_INFO   = 2;

  protected $borderClass;
  protected $text;

  public function __construct($text, $type = MessageBox::TYPE_ERROR) {
    switch ($type) {
      case MessageBox::TYPE_ERROR:
        $this->borderClass = 'error-border';
        break;
      case MessageBox::TYPE_INFO:
        $this->borderClass = 'info-border';
        break;
      default:
        $this->borderClass = 'error-border';
    }
    $this->text = $text;
  }

  public function Render() {
    require __DIR__ . '/template.php';
  }
}