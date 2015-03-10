<?php
class EditProfileViewBag extends LayoutViewBag {
  /** @var  UserEntity */
  public $User;
  public $validationErrors = array();
  public $CancelUri;
}