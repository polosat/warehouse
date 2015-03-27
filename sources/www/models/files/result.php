<?php
class FileOperationResult {
  const OPERATION_SUCCEEDED         = 0;
  const ERROR_SERVER_FAILURE        = 1001;

  // These ones are used by the controller only
  const ERROR_INVALID_REQUEST       = 1101;
  const ERROR_NO_FILE_SELECTED      = 1102;
  const ERROR_INTERRUPTED           = 1103;
  const ERROR_FILE_CORRUPTED        = 1104;

  // Save operation
  const ERROR_UNKNOWN_USER          = 1201;
  const ERROR_FILE_SIZE_TOO_BIG     = 1202;
  const ERROR_FILE_NAME_TOO_LONG    = 1203;
  const ERROR_FILE_NAME_EMPTY       = 1204;
  const ERROR_FILE_NAME_WRONG       = 1205;
  const ERROR_FILES_LIMIT_EXCEEDED  = 1206;

  // Delete operation
  const ERROR_FILE_DOES_NOT_EXISTS  = 1301;
  const ERROR_CANT_DELETE_FILE      = 1302;
}