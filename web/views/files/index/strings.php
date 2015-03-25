<?php
class FilesViewStrings extends Strings {
  const HEADER_TITLE              = 'Manage files';
  const FORM_TITLE                = 'Files';
  const LABEL_TOTAL               = 'Total';
  const LABEL_UPLOADING           = 'Uploading';

  const HINT_RULES                = 'You can upload up to %d files. Size of each file can not be more than %s.';
  const HINT_LIMIT_REACHED        = 'You have uploaded maximum allowed number of files.';
  const HINT_UPLOAD_NEW           = 'Upload a new file.';
  const HINT_DELETE_SELECTED      = 'Delete selected files.';
  const HINT_NOTHING_DELETE       = 'You have selected no files to delete.';

  const ALERT_DELETE_FILE         = 'Are you sure you want to delete %s?';
  const ALERT_DELETE_FILES        = 'Are you sure you want to delete selected files?';
  const ALERT_UPLOAD_FAILED       = 'Upload failed.';

  const COLUMN_NAME               = 'File name';
  const COLUMN_SIZE               = 'Size';
  const COLUMN_UPLOADED           = 'Uploaded';

  const ERROR_FILE_NOT_FOUND      = 'File not found.';
  const ERROR_DELETE_FILE         = 'Can not delete the selected file. Please try later.';
  const ERROR_DELETE_FILES        = 'Can not delete one or more selected files. Please try later.';
  const ERROR_UPLOAD_ERROR        = 'An error occurred during the file upload.';
}

class FilesViewStrings_RU extends FilesViewStrings {
  const HEADER_TITLE              = 'Управление файлами';
  const FORM_TITLE                = 'Файлы';
  const LABEL_TOTAL               = 'Всего';
  const LABEL_UPLOADING           = 'Загрузка';

  const HINT_RULES                = 'Вы можете загрузить не более %d файлов. Размер файла не должен превышать %s.';
  const HINT_LIMIT_REACHED        = 'Загружено максимально возможное количество файлов.';
  const HINT_UPLOAD_NEW           = 'Загрузить новый файл.';
  const HINT_DELETE_SELECTED      = 'Удалить выбранные файлы.';
  const HINT_NOTHING_DELETE       = 'Не выбраны файлы для удаления.';

  const ALERT_DELETE_FILE         = 'Вы действительно хотите удалить файл %s?';
  const ALERT_DELETE_FILES        = 'Вы действительно хотите удалить выбранные файлы?';
  const ALERT_UPLOAD_FAILED       = 'Ошибка загрузки.';

  const COLUMN_NAME               = 'Имя файла';
  const COLUMN_SIZE               = 'Размер';
  const COLUMN_UPLOADED           = 'Загружен';

  const ERROR_FILE_NOT_FOUND      = 'Файл не найден.';
  const ERROR_DELETE_FILE         = 'Не удалось удалить выбранный файл. Попробуйте позже.';
  const ERROR_DELETE_FILES        = 'Не удалось удалить некоторые из выбранных файлов. Попробуйте позже.';
  const ERROR_UPLOAD_ERROR        = 'Произошла ошибка во время загрузки файла.';
}