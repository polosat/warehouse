<?php
class EditProfileViewBag {
  /** @var UserEntity */
  public $User;
  public $validationErrors = array();
  public $CancelUri;
}