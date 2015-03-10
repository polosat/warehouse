<?php
class Hash {
  public static function Create($string, $salt = null) {
    if ($salt === null) {
      $salt = substr(
        str_replace('+', '.',
          base64_encode(
            pack('N4', mt_rand(), mt_rand(), mt_rand(), mt_rand())
          )
        ), 0, 22
      );
    }
    else if (!is_string($salt) || strlen($salt) != 22)
      throw new LogicException('Salt must be a string of 22 characters length.');

    return crypt($string, '$2y$08$'. $salt);
  }

  public static function Equal($hash1, $hash2) {
    $hash1Length = strlen($hash1);
    if ($hash1Length !== strlen($hash2)) {
      return false;
    }

    $result = 0;
    for ($i = 0; $i < $hash1Length; $i++) {
      $result |= ord($hash1[$i]) ^ ord($hash2[$i]);
    }

    return ($result === 0);
  }
}