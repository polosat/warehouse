<?php
class FilesViewStrings extends Strings {
  const HEADER_TITLE          = 'Manage files';
  const FORM_TITLE            = 'Files';

  const HINT_RULES            = 'You can upload up to %d files. Size of each file can not be more than %s';
  const HINT_UPLOAD_NEW       = 'Upload a new file';
  const HINT_DELETE_SELECTED  = 'Delete selected files';
  const HINT_NOTHING_DELETE   = 'You haven\'t selected anything';
  const HINT_LIMIT_REACHED    = 'You can\'t upload more files as you\'ve reached the storage capacity limit.';

  const ALERT_DELETE_FILE     = 'Are you sure you want to delete \\\'%s\\\'?';
  const ALERT_DELETE_FILES    = 'Are you sure you want to delete selected files?';

  const COLUMN_NAME           = 'File name';
  const COLUMN_SIZE           = 'Size';
  const COLUMN_UPLOADED       = 'Uploaded';
}

class FilesViewStrings_RU extends FilesViewStrings {
  const HEADER_TITLE          = 'Управление файлами';
  const FORM_TITLE            = 'Файлы';

  const HINT_RULES            = 'Вы можете сохранить до %d файлов. Размер каждого файла не должен превышать %s';
  const HINT_UPLOAD_NEW       = 'Upload a new file';
  const HINT_DELETE_SELECTED  = 'Delete selected files';
  const HINT_NOTHING_DELETE   = 'You haven\'t selected anything';
  const HINT_LIMIT_REACHED    = 'You can\'t upload more files as you\'ve reached the storage capacity limit.';

  const ALERT_DELETE_FILE     = 'Are you sure you want to delete \\\'%s\\\'?';
  const ALERT_DELETE_FILES    = 'Are you sure you want to delete selected files?';

  const COLUMN_NAME           = 'Имя файла';
  const COLUMN_SIZE           = 'Размер';
  const COLUMN_UPLOADED       = 'Загружен';
}