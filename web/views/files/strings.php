<?php
class FilesViewStrings extends Strings {
  const HEADER_TITLE    = 'Manage files';
  const FORM_TITLE      = 'Files';

  const HINT_RULES            = 'You can upload up to 20 files. Size of each file can not be more than 1 MB';
  const HINT_UPLOAD_NEW       = 'Upload a new file';
  const HINT_DELETE_SELECTED  = 'Delete selected files';
  const HINT_NOTHING_DELETE   = 'You haven\'t selected anything';
  const HINT_LIMIT_REACHED    = 'You can\'t upload more files as you\'ve reached the storage capacity limit.';

  const ALERT_DELETE_FILE     = 'Are you sure you want to delete ';
  const ALERT_DELETE_FILES    = 'Are you sure you want to delete selected files?';

  const COLUMN_NAME      = 'File name';
  const COLUMN_SIZE      = 'Size';
  const COLUMN_UPLOADED  = 'Uploaded';

  // TODO: Move these ones to the global level
  const TIME_FORMAT_H24 = 0;
  const UNIT_KILOBYTES  = 'KB';
  const DECIMAL_POINT   = '.';
}

class FilesViewStrings_RU extends FilesViewStrings {
  const HEADER_TITLE    = 'Управление файлами';
  const FORM_TITLE      = 'Файлы';

  const HINT_RULES            = 'Вы можете сохранить до 20 файлов. Размер каждого файла не должен превышать 1 МБ';
  const HINT_UPLOAD_NEW       = 'Upload a new file';
  const HINT_DELETE_SELECTED  = 'Delete selected files';
  const HINT_NOTHING_DELETE   = 'You haven\'t selected anything';
  const HINT_LIMIT_REACHED    = 'You can\'t upload more files as you\'ve reached the storage capacity limit.';

  const ALERT_DELETE_FILE     = 'Are you sure you want to delete ';
  const ALERT_DELETE_FILES    = 'Are you sure you want to delete selected files?';

  const COLUMN_NAME      = 'Имя файла';
  const COLUMN_SIZE      = 'Размер';
  const COLUMN_UPLOADED  = 'Загружен';

  const TIME_FORMAT_H24 = 1;
  const UNIT_KILOBYTES  = 'КБ';
  const DECIMAL_POINT   = ',';
}