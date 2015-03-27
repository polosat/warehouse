<?php
class FileUploadStrings extends Strings {
  const HINT_UPLOAD_NEW           = 'Upload a new file.';

  const ERROR_INVALID_REQUEST     = 'Invalid request.';
  const ERROR_FILE_SIZE_TOO_BIG   = 'The file size exceeds the maximum limit allowed.';
  const ERROR_NO_FILE_SELECTED    = 'There was no file selected.';
  const ERROR_INTERRUPTED         = 'The upload process was interrupted.';
  const ERROR_FILE_CORRUPTED      = 'The file uploaded is corrupted. Try again later.';
  const ERROR_SERVER_FAILURE      = 'The server side error occurred.';
  const ERROR_FILE_NAME_EMPTY     = 'The file name can not be empty';
  const ERROR_FILE_NAME_TOO_LONG  = 'File name length should not be more than %d characters.';
  const ERROR_FILE_NAME_WRONG     = 'The following characters in file names are illegal: \\ / * ? : | " < >';
  const ERROR_LIMIT_EXCEEDED      = 'You can not keep in the storage more than %d files.';
  const ERROR_UNKNOWN_USER        = 'Unknown user.';
}

class FileUploadStrings_RU extends FileUploadStrings {
  const HINT_UPLOAD_NEW           = 'Загрузить новый файл.';

  const ERROR_INVALID_REQUEST     = 'Некорректный запрос.';
  const ERROR_FILE_SIZE_TOO_BIG   = 'Размер файла превышает максимально допустимое значение.';
  const ERROR_NO_FILE_SELECTED    = 'Не выбран файл для загрузки.';
  const ERROR_INTERRUPTED         = 'Загрузка файла была прервана.';
  const ERROR_FILE_CORRUPTED      = 'Загруженный файл повреждён. Попробуйте позже.';
  const ERROR_SERVER_FAILURE      = 'Ошибка сервера.';
  const ERROR_FILE_NAME_EMPTY     = 'Необходимо указать имя файла.';
  const ERROR_FILE_NAME_TOO_LONG  = 'Имя файла не должно состоять более чем из %d символов.';
  const ERROR_FILE_NAME_WRONG     = 'Имя файла не должно содержать следующие символы: \\ / * ? : | " < >';
  const ERROR_LIMIT_EXCEEDED      = 'Вы не можете загрузить более чем %d файлов.';
  const ERROR_UNKNOWN_USER        = 'Пользователь не существует.';
}