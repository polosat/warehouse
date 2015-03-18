<?php
class FileOperationResult {
  const OPERATION_SUCCEEDED         = 0;
  // Save operation
  const ERROR_FILE_NAME_EMPTY       = 1003;
  const ERROR_FILE_NAME_TOO_LONG    = 1002;
  const ERROR_FILE_SIZE_TOO_BIG     = 1001;
  const ERROR_FILE_NAME_WRONG       = 1004;

  const ERROR_FILE_DOES_NOT_EXISTS  = 1005;
  const ERROR_USER_DOES_NOT_EXISTS  = 1006;
  const ERROR_FILES_LIMIT_EXCEEDED  = 1007;
  const ERROR_CANT_DELETE_FILE      = 1008;
  const ERROR_SERVER_FAILURE        = 1009;
}