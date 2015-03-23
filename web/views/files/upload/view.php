<?php
require_once __DIR__ . '/strings.php';

class FileUploadView {
  const FIELD_NAME_UPLOAD = 'uploaded_file';

  public $FileUploadStrings;
  public $StatusMessage;

  public function __construct($language) {
    /** @var FileUploadStrings $strings */
    $strings = FileUploadStrings::GetInstance($language);
    $this->FileUploadStrings = $strings;
  }

  public function Render() {
    require __DIR__ . '/template.php';
  }
}
