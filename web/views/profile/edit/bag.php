<?php
class EditProfileViewBag {
  /** @var UserEntity */
  public $User;
  public $ValidationErrors = array();
  public $CancelUri;
}