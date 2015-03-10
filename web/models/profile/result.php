<?php
require_once __DIR__ . '/../../classes/result.php';

class ProfileOperationResult extends ModelOperationResult {
  const ERROR_INVALID_LOGIN         = 1001;
  const ERROR_INVALID_FIRST_NAME    = 1002;
  const ERROR_INVALID_LAST_NAME     = 1003;
  const ERROR_INVALID_PASSWORD      = 1004;
  const ERROR_INVALID_EMAIL         = 1005;
  const ERROR_INVALID_PHONE         = 1006;
  const ERROR_INVALID_BIRTHDAY      = 1007;
  const ERROR_LOGIN_ALREADY_EXISTS  = 1008;
  const ERROR_DUPLICATED_RECORD     = 1009;
  const ERROR_PASSWORDS_MISMATCH    = 1010;
  const ERROR_BAD_CURRENT_PASSWORD  = 1011;
  const ERROR_UNKNOWN_USER_ID       = 1012;
}