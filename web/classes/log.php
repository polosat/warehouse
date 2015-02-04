<?php
require_once __DIR__ . '/../settings.php';

class Log {
  public static function Write($message, $issueID) {
    $utc_date = gmdate('Y-m-d');
    $utc_time = gmdate('d/m/Y H:i:s T');
    $log_message = "[$utc_time]\n$message\n\n";
    $log_file = Settings::LOG_BASE . Settings::LOG_FILE . "_$utc_date" . Settings::LOG_EXTENSION;
    $a = error_log($log_message, 3, $log_file);
  }
}