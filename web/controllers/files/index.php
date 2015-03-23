<?php
require_once __DIR__ . '/../../views/files/index/view.php';
require_once __DIR__ . '/../../views/files/upload/view.php';
require_once __DIR__ . '/../../models/files/model.php';
require_once __DIR__ . '/callback.php';

class FilesController extends Controller {
  const CONTROLLER_NAME = Application::FILES_CONTROLLER;

  const ACTION_DOWNLOAD = 'download';
  const ACTION_DELETE   = 'delete';
  const ACTION_UPLOAD   = 'upload';

  protected function Action_Index() {
    $context = $this->context;
    $session = $context->Session;
    $request = $context->Request;
    $language = $request->Language;

    $bag = new FilesViewBag();
    $view = new FilesView($bag, $request->Language);
    $this->InitializeLayoutView($view);

    $callback = $this->UnserializeCallback();
    if ($callback instanceof FilesCallback) {
      $view->Alert = $callback->Alert;
    }

    $userID = $session->UserID();
    $model = new FilesModel($context);
    $files = $model->GetUserFiles($userID);

    $bag->UploadUri = Request::CreateUri($language, self::CONTROLLER_NAME, self::ACTION_UPLOAD);
    $bag->DeleteUri = Request::CreateUri($language, self::CONTROLLER_NAME, self::ACTION_DELETE);
    $downloadUriBase = Request::CreateUri($language, self::CONTROLLER_NAME, self::ACTION_DOWNLOAD);

    $bag->TotalSize = 0;
    foreach ($files as $file) {
      $item = new FileViewItem();
      $item->ID = $file->FileID;
      $item->Name = $file->FileName;
      $item->Size = $file->Size;
      $item->UploadedOn = $this->GetTimestamp($file->UploadedOn);
      $item->Uri = $downloadUriBase . '/' . $file->FileID . '/' . rawurlencode($file->FileName);
      $bag->Files[] = $item;
      $bag->TotalSize += $item->Size;
    }

    $view->Render();
  }

  protected function Action_Download() {
    $context = $this->context;
    $session = $context->Session;
    $request = $context->Request;
    $language = $request->Language;
    /** @var FilesViewStrings $strings */
    $strings = FilesViewStrings::GetInstance($language);

    $idName = explode('/', $request->Argument);
    if (count($idName) != 2)
      return new Request($language, self::CONTROLLER_NAME);

    $userID = $session->UserID();
    $fileID = intval($idName[0]);
    $fileName = mb_strtolower($idName[1]);
    $fileNameInternal = "$fileID.$userID";

    $path = Settings::STORAGE_PATH . $fileNameInternal;

    if (!file_exists($path)) {
      $callback = new FilesCallback($strings::ERROR_FILE_NOT_FOUND);
      return $this->CreateCallbackRequest($callback, self::CONTROLLER_NAME);
    }

    $model = new FilesModel($context);
    $file = $model->GetFile($fileID, $fileName);

    if (is_null($file) || $file->UserID != $userID || mb_strtolower($file->FileName) != $fileName ) {
      $callback = new FilesCallback($strings::ERROR_FILE_NOT_FOUND);
      return $this->CreateCallbackRequest($callback, self::CONTROLLER_NAME);
    }

    $contentType = empty($file->ContentType) ? 'application/octet-stream' : $file->ContentType;
    $uri = Settings::STORAGE_URI . $fileNameInternal;
    header('X-Accel-Redirect: ' . $uri);
    header('Content-Type: ' . $contentType);
    header('Content-Disposition: attachment');

    return null;
  }

  protected function ActionPost_Delete() {
    $context = $this->context;
    $session = $context->Session;
    $request = $context->Request;
    $language = $request->Language;

    $filesToDelete = $request->GetPostedValue(FilesView::FIELD_NAME_SELECTED_FILE);
    if (is_array($filesToDelete) && count($filesToDelete) > 0) {
      $userID = $session->UserID();
      $model = new FilesModel();
      $failed = $model->DeleteUserFiles($userID, $filesToDelete);

      if ($failed) {
        /** @var FilesViewStrings $strings */
        $strings = FilesViewStrings::GetInstance($language);
        $errorMessage = (count($filesToDelete) > 1) ? $strings::ERROR_DELETE_FILES : $strings::ERROR_DELETE_FILE;
        $callback = new FilesCallback($errorMessage);
        return $this->CreateCallbackRequest($callback, self::CONTROLLER_NAME);
      }
    }
    return new Request($language, self::CONTROLLER_NAME);
  }

  protected function Action_Upload() {
    $context = $this->context;
    $request = $context->Request;
    $language = $request->Language;

    $view = new FileUploadView($language);

    if ($request->Error == 413) {
      $strings = $view->FileUploadStrings;
      $callback = new FilesCallback($strings::ERROR_FILE_SIZE_TOO_BIG);
      $context->PushCallback($callback);
      header('Status: 444');
    }
    else {
      $view->Render();
    }
  }

  protected function ActionPost_Upload() {
    $context = $this->context;
    $request = $context->Request;
    $session = $context->Session;
    $files = $request->Files;

    try {
      $operationResult = $this->SaveUploadedFile($session->UserID(), $files);
    }
    catch(Exception $e) {
      // TODO: Log error here
      $operationResult = FileOperationResult::ERROR_SERVER_FAILURE;
    }

    /** @var FileUploadStrings $strings */
    $strings = FileUploadStrings::GetInstance($request->Language);

    switch ($operationResult) {
      case FileOperationResult::OPERATION_SUCCEEDED:
        return;

      case FileOperationResult::ERROR_SERVER_FAILURE:
        $alert = $strings::ERROR_SERVER_FAILURE;
        break;

      case FileOperationResult::ERROR_INVALID_REQUEST:
        $alert = $strings::ERROR_INVALID_REQUEST;
        break;

      case FileOperationResult::ERROR_NO_FILE_SELECTED:
        $alert = $strings::ERROR_NO_FILE_SELECTED;
        break;

      case FileOperationResult::ERROR_INTERRUPTED:
        $alert = $strings::ERROR_INTERRUPTED;
        break;

      case FileOperationResult::ERROR_FILE_CORRUPTED:
        $alert = $strings::ERROR_FILE_CORRUPTED;
        break;

      case FileOperationResult::ERROR_UNKNOWN_USER:
        $alert = $strings::ERROR_UNKNOWN_USER;
        break;

      case FileOperationResult::ERROR_FILE_SIZE_TOO_BIG:
        $alert = $strings::ERROR_FILE_SIZE_TOO_BIG;
        break;

      case FileOperationResult::ERROR_FILE_NAME_TOO_LONG:
        $alert = sprintf($strings::ERROR_FILE_NAME_TOO_LONG, FileEntity::MAX_NAME_LENGTH);
        break;

      case FileOperationResult::ERROR_FILE_NAME_EMPTY:
        $alert = $strings::ERROR_FILE_NAME_EMPTY;
        break;

      case FileOperationResult::ERROR_FILE_NAME_WRONG:
        $alert = $strings::ERROR_FILE_NAME_WRONG;
        break;

      case FileOperationResult::ERROR_FILES_LIMIT_EXCEEDED:
        $alert = sprintf($strings::ERROR_LIMIT_EXCEEDED, Settings::MAX_FILES_PER_USER);
        break;

      default:
        // TODO: Log error here
        $alert = $strings::ERROR_SERVER_FAILURE;
    }

    $callback = new FilesCallback($alert);
    $context->PushCallback($callback);
  }

  protected function SaveUploadedFile($userID, $files) {
    if (count($files) == 0) {
      return FileOperationResult::ERROR_NO_FILE_SELECTED;
    }

    if (count($files) != 1 || $files[0]->Field != FilesView::FIELD_NAME_UPLOADED_FILE) {
      return FileOperationResult::ERROR_INVALID_REQUEST;
    }

    $file = $files[0];

    switch ($file->Error) {
      case UPLOAD_ERR_OK:
        // Continue upload processing
        break;

      case UPLOAD_ERR_NO_FILE:
        return FileOperationResult::ERROR_NO_FILE_SELECTED;

      case UPLOAD_ERR_INI_SIZE:
      case UPLOAD_ERR_FORM_SIZE:
        return FileOperationResult::ERROR_FILE_SIZE_TOO_BIG;

      case UPLOAD_ERR_PARTIAL:
        return FileOperationResult::ERROR_INTERRUPTED;

      default:
        return FileOperationResult::ERROR_SERVER_FAILURE;
    }

    if ($file->PostedSize != $file->RealSize) {
      return FileOperationResult::ERROR_FILE_CORRUPTED;
    }

    $model = new FilesModel();
    $saveRequest = new FileSaveRequest();
    $saveRequest->UserID = $userID;
    $saveRequest->FileName = $file->Name;
    $saveRequest->ContentType = $file->Type;
    $saveRequest->UploadedPath = $file->Path;
    $result = $model->SaveFile($saveRequest);

    return $result;
  }

  protected function GetTimestamp($dateTimeString) {
    $dateTime = new DateTime($dateTimeString, new DateTimeZone('UTC'));
    return $dateTime->getTimestamp();
  }

  protected function HeaderItemsMask() {
    return HeaderItem::PROFILE | HeaderItem::LOGOUT;
  }

  protected function RequiresAuthentication()
  {
    return true;
  }

  protected function AllowsExpiredSession()
  {
    return false;
  }
}