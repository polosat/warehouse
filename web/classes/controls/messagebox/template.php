<div id="message-box-shadow">
  <div id="message-box" class="<?=$this->borderClass?>">
    <div id="message-box-text"><?=$this->text?></div>
    <input id="message_box_button" type="button" class="button" value="OK" onclick="document.body.className = ''">
  </div>
</div>
<script type="text/javascript">
  document.getElementById('message_box_button').focus();
</script>

