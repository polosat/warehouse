<?php
/** @var EditProfileView $this */
$bag = $this->Bag;
$user = $bag->User;
$strings = $this->ProfileStrings;

$existingUser = isset($user->UserID);
$submitName = $existingUser ? $strings::BUTTON_CHANGE : $strings::BUTTON_REGISTER;
?>
<div id="profile_form" class="frame input-form">
  <h1><?=$strings::FORM_TITLE?></h1>
  <form action="" method="post">
    <label for="login"><?=$strings::LABEL_LOGIN?><span class="text-red"> *</span></label>
    <input id="login" name="<?=EditProfileView::FIELD_NAME_LOGIN?>" type="text" value="<?=html($user->Login)?>" maxlength="<?=UserEntity::LOGIN_MAX_LENGTH?>" />
<?php if (isset($bag->ValidationErrors[EditProfileView::FIELD_NAME_LOGIN])): ?>
    <div class="input-error"><?=$bag->ValidationErrors[EditProfileView::FIELD_NAME_LOGIN]?></div>
<?php endif ?>

<?php if ($existingUser): ?>
    <label for="password_current"><?=$strings::LABEL_PASSWORD_CURRENT?><span class="text-red"> *</span></label>
    <div class="input-hint"><?=$strings::HINT_PASSWORD_CURRENT?></div>
    <input id="password_current" name="<?=EditProfileView::FIELD_NAME_PASSWORD_CURRENT?>" type="password"/>
<?php if (isset($bag->ValidationErrors[EditProfileView::FIELD_NAME_PASSWORD_CURRENT])): ?>
    <div class="input-error"><?=$bag->ValidationErrors[EditProfileView::FIELD_NAME_PASSWORD_CURRENT]?></div>
<?php endif ?>
<?php endif ?>

    <label for="password"><?=$strings::LABEL_PASSWORD?><span class="text-red"> *</span></label>
    <div class="input-hint"><?php echo $strings::HINT_PASSWORD; $existingUser && print '<br/>'.$strings::HINT_EMPTY_PASSWORD; ?></div>
    <input id="password" name="<?=EditProfileView::FIELD_NAME_PASSWORD?>" type="password" maxlength="<?=UserEntity::PASSWORD_MAX_LENGTH?>"/>
<?php if (isset($bag->ValidationErrors[EditProfileView::FIELD_NAME_PASSWORD])): ?>
    <div class="input-error"><?=$bag->ValidationErrors[EditProfileView::FIELD_NAME_PASSWORD]?></div>
<?php endif ?>

    <label for="confirm_password"><?=$strings::LABEL_PASSWORD_CONFIRM?><span class="text-red"> *</span></label>
    <div class="input-hint"><?=$strings::HINT_PASSWORD_CONFIRM?></div>
    <input id="confirm_password" name="<?=EditProfileView::FIELD_NAME_PASSWORD_CONFIRM?>" type="password" maxlength="<?=UserEntity::PASSWORD_MAX_LENGTH?>"/>
<?php if (isset($bag->ValidationErrors[EditProfileView::FIELD_NAME_PASSWORD_CONFIRM])): ?>
    <div class="input-error"><?=$bag->ValidationErrors[EditProfileView::FIELD_NAME_PASSWORD_CONFIRM]?></div>
<?php endif ?>

    <label for="first_name"><?=$strings::LABEL_FIRST_NAME?><span class="text-red"> *</span></label>
    <input id="first_name" name="<?=EditProfileView::FIELD_NAME_FIRST_NAME?>" type="text" value="<?=html($user->FirstName)?>"/>
<?php if (isset($bag->ValidationErrors[EditProfileView::FIELD_NAME_FIRST_NAME])): ?>
    <div class="input-error"><?=$bag->ValidationErrors[EditProfileView::FIELD_NAME_FIRST_NAME]?></div>
<?php endif ?>

    <label for="last_name"><?=$strings::LABEL_LAST_NAME?><span class="text-red"> *</span></label>
    <input id="last_name" name="<?=EditProfileView::FIELD_NAME_LAST_NAME?>" type="text" value="<?=html($user->LastName)?>"/>
<?php if (isset($bag->ValidationErrors[EditProfileView::FIELD_NAME_LAST_NAME])): ?>
    <div class="input-error"><?=$bag->ValidationErrors[EditProfileView::FIELD_NAME_LAST_NAME]?></div>
<?php endif ?>

    <label for="birthday"><?=$strings::LABEL_BIRTHDAY?></label>
    <input id="birthday" name="<?=EditProfileView::FIELD_NAME_BIRTHDAY?>" type="text" value="<?=$user->Birthday?>" maxlength="10"/>
<?php if (isset($bag->ValidationErrors[EditProfileView::FIELD_NAME_BIRTHDAY])): ?>
    <div class="input-error"><?=$bag->ValidationErrors[EditProfileView::FIELD_NAME_BIRTHDAY]?></div>
<?php endif ?>

    <label for="phone"><?=$strings::LABEL_PHONE?></label>
    <div class="input-hint"><?=$strings::HINT_PHONE?></div>
    <input id="phone" name="<?=EditProfileView::FIELD_NAME_PHONE?>" type="text" value="<?=html($user->Phone)?>" maxlength="32"/>
<?php if (isset($bag->ValidationErrors[EditProfileView::FIELD_NAME_PHONE])): ?>
    <div class="input-error"><?=$bag->ValidationErrors[EditProfileView::FIELD_NAME_PHONE]?></div>
<?php endif ?>

    <label for="e_mail"><?=$strings::LABEL_EMAIL?></label>
    <input id="e_mail" name="<?=EditProfileView::FIELD_NAME_EMAIL?>" type="text" value="<?=html($user->EMail)?>"/>
<?php if (isset($bag->ValidationErrors[EditProfileView::FIELD_NAME_EMAIL])): ?>
    <div class="input-error"><?=$bag->ValidationErrors[EditProfileView::FIELD_NAME_EMAIL]?></div>
<?php endif ?>
    <hr/>
    <div class="text-11 input-hint"><span class="text-red">*</span> <?=$strings::HINT_MANDATORY?></div>
    <div id="button_line">
      <input id="submit" class="button" type="submit" value="<?=$submitName?>">
<?php if ($existingUser): ?>
      <input id="cancel" class="button" type="button" value="<?=$strings::BUTTON_CANCEL?>" onclick="navigate('<?=$bag->CancelUri?>')">
<?php endif ?>
    </div>
  </form>
</div>
<?php
  $this->birthdayPicker->Render();
  $this->phoneField->Render();
?>