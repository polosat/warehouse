<?php
/** @var ShowProfileView $this */
/** @var ShowProfileViewBag $bag */
$bag = $this->Bag;
$user = $bag->User;
$strings = $this->ProfileStrings;
$birthdayFormatted = $this->FormatBirthday();
?>
<div id="user_form" class="form-frame">
  <h1><?=html($user->FirstName)?> <?=html($user->LastName)?></h1>
  <table>
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
  <form action="<?=$bag->EditUri?>" method="get">
    <input type="submit" class="button" value="<?=$strings::LABEL_EDIT?>">
  </form>
</div>
