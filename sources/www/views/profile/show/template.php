<?php
/** @var ShowProfileView $this */
$bag = $this->Bag;
$user = $bag->User;
$strings = $this->ProfileStrings;
$birthdayFormatted = $this->FormatBirthday();
?>
<div id="user_form" class="frame">
  <div class="frame-header"><?=html($user->FirstName)?> <?=html($user->LastName)?></div>
  <table id="info_table">
    <tr>
      <td class="user-view-label"><?=$strings::LABEL_LOGIN?>: </td>
      <td><?=html($user->Login)?></td>
    </tr>
<?php if (isset($birthdayFormatted)): ?>
    <tr>
      <td class="user-view-label"><?=$strings::LABEL_BIRTHDAY?>: </td>
      <td><?=$birthdayFormatted?></td>
    </tr>
<?php endif ?>
<?php if (isset($user->Phone)): ?>
    <tr>
      <td class="user-view-label"><?=$strings::LABEL_PHONE?>: </td>
      <td>+<?=html($user->Phone)?></td>
    </tr>
<?php endif ?>
<?php if (isset($user->EMail)): ?>
    <tr>
      <td class="user-view-label"><?=$strings::LABEL_EMAIL?>: </td>
      <td><?=html($user->EMail)?></td>
    </tr>
<?php endif ?>
  </table>
  <hr/>
  <div id="button_line">
    <input type="button" name="edit_button" class="button" onclick="navigate('<?=$bag->EditUri?>')" value="<?=$strings::LABEL_EDIT?>">
  </div>
</div>
