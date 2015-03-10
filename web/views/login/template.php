<?php
/** @var LoginView $this */
/** @var LoginViewBag $bag */
$strings = $this->LoginStrings;
$bag = $this->Bag;
$userName = empty($bag->userName) ? '' : $bag->userName;
$focusedControl = empty($userName) ? LoginView::FIELD_NAME_LOGIN : LoginView::FIELD_NAME_PASSWORD;
?>
<div id="login_form" class="form-frame">
  <h1><?=$strings::TITLE_LOGIN_FORM?></h1>
<?php if (isset($bag->errorText)): ?>
  <div class="form-frame-error"><?=$bag->errorText?></div>
<?php endif ?>
  <form action="" method="post">
    <label for="login"><?=$strings::LABEL_LOGIN?></label>
    <input id="login" name="<?=$this::FIELD_NAME_LOGIN?>" class="sign-up-input" type="text" value="<?=html($userName)?>"/>
    <label for="password"><?=$strings::LABEL_PASSWORD?></label>
    <input id="password" name="<?=$this::FIELD_NAME_PASSWORD?>" class="sign-up-input" type="password" value=""/>
    <p id="button_box">
      <input id="submit" class="button" type="submit" value="<?=$strings::BUTTON_LOGIN?>">
    </p>
  </form>
  <script type="text/javascript">document.getElementById('<?=$focusedControl?>').focus();</script>
</div>
