<?php
class ShowProfileCallback extends CallbackBase {
  const REASON_PROFILE_CHANGED    = 1001;
}

class EditProfileCallback extends CallbackBase {
  const REASON_VALIDATION_ERROR   = 1001;

  /** @var  UserEntity */
  public $User;
  /** @var  ProfileOperationResult */
  public $OperationResult;
}