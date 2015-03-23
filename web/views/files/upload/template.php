<?php
/** @var FileUploadView $this */
$strings = $this->FileUploadStrings;
?>
<!doctype html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <link rel="stylesheet" href="/views/files/upload/css/style.css?<?=Settings::VERSION?>"/>
</head>
<body id="upload_body">
  <form id="upload_form" class="upload-form" action="" method="post" enctype="multipart/form-data">
    <input id="upload_input" type="file" name="<?=$this::FIELD_NAME_UPLOAD?>" title="<?=$strings::HINT_UPLOAD_NEW?>">
 </form>
</body>
</html>