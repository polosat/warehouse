<?php
function has_ctrl_chars($text) {
  return preg_match('/[[:cntrl:]]/u', $text) !== 0;
}

function trim_to_null(&$string, $trim = true) {
  $trimmed = trim($string);
  if (mb_strlen($trimmed) == 0) {
    $string = null;
  }
  else if ($trim) {
    $string = $trimmed;
  }
}

function parse_date_time($string, $format, DateTimeZone $timeZone = null) {
  if (!is_string($string) || !is_string($format))
    return false;

  if (!$timeZone) {
    $timeZone = new DateTimeZone('UTC');
  }

  $dateTime = DateTime::createFromFormat($format, $string, $timeZone);
  $error = DateTime::getLastErrors();

  return ($dateTime && $error['warning_count'] == 0 && $error['error_count'] == 0) ? $dateTime : false;
}

function html($text) {
  return htmlentities($text, ENT_QUOTES, 'UTF-8');
}