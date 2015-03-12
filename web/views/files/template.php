<?php
$fileName = '讀萬卷書不如行萬裡路.txt';
$uri = '/ru/files/get/12/' . rawurlencode($fileName);
$a = 0;
?>
<form action="" method="post" enctype="multipart/form-data">
<!--  <input name="file[]" type="file"><br/>-->
<!--  <input name="file[]" type="file"><br/>-->
<!--  <input name="file[]" type="file"><br/>-->
<!--  <input name="file2" type="file"><br/>-->
<!--  <input name="file3" type="file"><br/>-->
  <input name="<?=FilesView::FIELD_NAME_USER_FILE?>" type="file"><br/>
  <input type="submit">
</form>
<br/>
<a href="<?=html($uri)?>"><?=html($fileName)?></a>
<br/>
<input type="button" value="Click Me!" onclick="messageBox.show('This is a text displayed in the message box.', MessageBox.TypeInfo, MessageBox.ButtonYes | MessageBox.ButtonNo)">
