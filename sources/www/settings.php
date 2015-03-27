<?php
class Settings {
  // The first language is a default one
  const LANGUAGES = 'en|ru';

  const DB_HOST     = 'localhost';
  const DB_NAME     = 'warehouse';
  const DB_USER     = 'warehouse';
  const DB_PASSWORD = '$ecureP@$$w0rd';
  const DB_CHARSET  = 'utf8';

  const STORAGE_PATH = '/warehouse/storage/'; // TODO: Get from nginx?
  const STORAGE_URI  = '/storage/';

  const MAX_FILES_PER_USER = 20;
  const MAX_FILE_SIZE      = 1048576; //TODO Get from ini and also change resource string
  const SHOW_DECIMAL_SIZE  = false;

  // Session timeout in seconds
  const SESSION_TIMEOUT = 600;

  const VERSION = '5.5';
}
