<?php
class Settings {
  // The first language is a default one
  const LANGUAGES = 'en|ru';

  const DB_HOST     = '127.0.0.1';
  const DB_NAME     = 'storage';
  const DB_USER     = 'dbuser';
  const DB_PASSWORD = 'SecureP@ssw0rd';
  const DB_CHARSET  = 'utf8';

  const STORAGE_PATH = '/Users/polosat/Code/bitbucket/smalltools/tallinn/warehouse/';
  const STORAGE_URI  = '/warehouse/';

  const MAX_FILES_PER_USER = 20;
  const MAX_FILE_SIZE      = 1048576;
  const SHOW_DECIMAL_SIZE  = false;

  // Session timeout in seconds
  const SESSION_TIMEOUT = 600;

  const VERSION = '5.3';
}
