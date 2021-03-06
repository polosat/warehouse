<?php
/** @var LoginView $this */
$strings = $this->LoginStrings;
$bag = $this->Bag;
?>
<div id="login_form" class="frame input-form">
  <div class="frame-header"><?=$strings::TITLE_LOGIN_FORM?></div>
<?php if (isset($bag->ErrorText)): ?>
  <div class="input-error"><?=$bag->ErrorText?></div>
<?php endif ?>
  <form action="" method="post">
    <label for="login"><?=$strings::LABEL_LOGIN?></label>
    <input id="login" name="<?=$this::FIELD_NAME_LOGIN?>" class="sign-up-input" type="text" value="<?=html($bag->UserName)?>"/>
    <label for="password"><?=$strings::LABEL_PASSWORD?></label>
    <input id="password" name="<?=$this::FIELD_NAME_PASSWORD?>" class="sign-up-input" type="password" value=""/>
    <div id="button_line">
      <input id="submit" class="button" type="submit" value="<?=$strings::BUTTON_LOGIN?>">
    </div>
  </form>
</div>
