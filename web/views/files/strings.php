<?php
class FilesViewStrings extends Strings {
  const HEADER_TITLE              = 'Manage files';
  const FORM_TITLE                = 'Files';
  const LABEL_TOTAL               = 'Total';

  const HINT_RULES                = 'You can upload up to %d files. Size of each file can not be more than %s.';
  const HINT_LIMIT_REACHED        = 'You have uploaded maximum allowed number of files.';
  const HINT_UPLOAD_NEW           = 'Upload a new file.';
  const HINT_DELETE_SELECTED      = 'Delete selected files.';
  const HINT_NOTHING_DELETE       = 'You have selected no files to delete.';

  const ALERT_DELETE_FILE         = 'Are you sure you want to delete %s?';
  const ALERT_DELETE_FILES        = 'Are you sure you want to delete selected files?';

  const COLUMN_NAME               = 'File name';
  const COLUMN_SIZE               = 'Size';
  const COLUMN_UPLOADED           = 'Uploaded';

  const ERROR_INVALID_REQUEST     = 'There was no file uploaded.';
  const ERROR_FILE_SIZE_TOO_BIG   = 'The file size exceeds the maximum limit allowed.';
  const ERROR_NO_FILE_SELECTED    = 'There was no file selected.';
  const ERROR_INTERRUPTED         = 'The upload process was interrupted.';
  const ERROR_FILE_CORRUPTED      = 'The file uploaded is corrupted. Try again later.';
  const ERROR_SERVER_FAILURE      = 'The server side error occurred.';
  const ERROR_FILE_NOT_FOUND      = 'File not found.';
  const ERROR_DELETE_FILE         = 'Can not delete the selected file. Please try later.';
  const ERROR_DELETE_FILES        = 'Can not delete one or more selected files. Please try later.';
  const ERROR_FILE_NAME_EMPTY     = 'The file name can not be empty';
  const ERROR_FILE_NAME_TOO_LONG  = 'File name length should not be more than %d characters.';
  const ERROR_FILE_NAME_WRONG     = 'The following characters in file names are illegal: \\ / * ? : | " < >';
  const ERROR_LIMIT_EXCEEDED      = 'You can not keep in the storage more than %d files.';
  const ERROR_UNKNOWN_USER        = 'Unknown user.';
}

class FilesViewStrings_RU extends FilesViewStrings {
  const HEADER_TITLE              = 'Управление файлами';
  const FORM_TITLE                = 'Файлы';
  const LABEL_TOTAL               = 'Всего';

  const HINT_RULES                = 'Вы можете загрузить не более %d файлов. Размер файла не должен превышать %s.';
  const HINT_LIMIT_REACHED        = 'Загружено максимально возможное количество файлов.';
  const HINT_UPLOAD_NEW           = 'Загрузить новый файл.';
  const HINT_DELETE_SELECTED      = 'Удалить выбранные файлы.';
  const HINT_NOTHING_DELETE       = 'Не выбраны файлы для удаления.';

  const ALERT_DELETE_FILE         = 'Вы действительно хотите удалить файл %s?';
  const ALERT_DELETE_FILES        = 'Вы действительно хотите удалить выбранные файлы?';

  const COLUMN_NAME               = 'Имя файла';
  const COLUMN_SIZE               = 'Размер';
  const COLUMN_UPLOADED           = 'Загружен';

  const ERROR_INVALID_REQUEST     = 'Нет файла для загрузки.';
  const ERROR_FILE_SIZE_TOO_BIG   = 'Размер файла превышает максимально допустимое значение.';
  const ERROR_NO_FILE_SELECTED    = 'Не выбран файл для загрузки.';
  const ERROR_INTERRUPTED         = 'Загрузка файла была прервана.';
  const ERROR_FILE_CORRUPTED      = 'Загруженный файл повреждён. Попробуйте позже.';
  const ERROR_SERVER_FAILURE      = 'Ошибка сервера.';
  const ERROR_FILE_NOT_FOUND      = 'Файл не найден.';
  const ERROR_DELETE_FILE         = 'Не удалось удалить выбранный файл Попробуйте позже.';
  const ERROR_DELETE_FILES        = 'Не удалось удалить некоторые из выбранных файлов. Попробуйте позже.';
  const ERROR_FILE_NAME_EMPTY     = 'Необходимо указать имя файла.';
  const ERROR_FILE_NAME_TOO_LONG  = 'Имя файла не должно состоять более чем из %d символов.';
  const ERROR_FILE_NAME_WRONG     = 'Имя файла не должно содержать ни один из следующих символов : \\ / * ? : | " < >';
  const ERROR_LIMIT_EXCEEDED      = 'Вы не можете загрузить более чем %d файлов.';
  const ERROR_UNKNOWN_USER        = 'Пользователь не существует.';
}