<?php
class Settings {
  // The first language is a default one
  const LANGUAGES = 'en|ru';

  const DB_HOST     = '127.0.0.1';
  const DB_NAME     = 'storage';
  const DB_USER     = 'dbuser';
  const DB_PASSWORD = 'SecureP@ssw0rd';
  const DB_CHARSET  = 'utf8';

  const STORAGE_PATH        = '/Users/polosat/Code/bitbucket/smalltools/tallinn/warehouse/';
  const STORAGE_URI         = '/warehouse/';
  const BYTES_1MB           = 1048576;
  const MAX_FILES_PER_USER  = 20;
  const MAX_FILE_SIZE       = self::BYTES_1MB;

  // Session timeout in seconds
  const SESSION_TIMEOUT = 600;

  const VERSION = '2.5';
}
