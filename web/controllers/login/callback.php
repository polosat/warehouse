<?php
class LoginCallback extends CallbackBase {
  const REASON_AUTHENTICATION_FAILED    = 1001;
  const REASON_AUTHENTICATION_REQUIRED  = 1002;
  const REASON_SESSION_EXPIRED          = 1003;
  const REASON_UNKNOWN_USER_ID          = 1004;
  const REASON_SHOW_MESSAGE             = 1005;

  public $UserName;
}