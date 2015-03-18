<?php
require_once __DIR__ . '/../../views/files/view.php';
require_once __DIR__ . '/../../models/files/model.php';
require_once __DIR__ . '/callback.php';

class FilesController extends Controller {
  const CONTROLLER_NAME = Application::FILES_CONTROLLER;
  const ACTION_GET      = 'get';
  const ACTION_NEW      = 'new';
  const ACTION_DELETE   = 'delete';

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

    $bag->UploadUri = Request::CreateUri($language, self::CONTROLLER_NAME, self::ACTION_NEW);
    $bag->DeleteUri = Request::CreateUri($language, self::CONTROLLER_NAME, self::ACTION_DELETE);
    $downloadUriBase = Request::CreateUri($language, self::CONTROLLER_NAME, self::ACTION_GET) . '/';

    $bag->TotalSize = 0;
    foreach ($files as $file) {
      $item = new FileViewItem();
      $item->ID = $file->FileID;
      $item->Name = $file->FileName;
      $item->Size = $file->Size;
      $item->UploadedOn = $this->GetTimestamp($file->UploadedOn);
      $item->Uri = $downloadUriBase . $file->FileID . '/' . rawurlencode($file->FileName);
      $bag->Files[] = $item;
      $bag->TotalSize += $item->Size;
    }

    $view->Render();
  }

  protected function Action_Get() {
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

  protected function Action_New() {
    $context = $this->context;
    $request = $context->Request;
    $language = $request->Language;

    /** @var FilesViewStrings $strings */
    $strings = FilesViewStrings::GetInstance($language);

    if ($request->Error == 413) {
      $callback = new FilesCallback($strings::ERROR_FILE_SIZE_TOO_BIG);
      return $this->CreateCallbackRequest($callback, self::CONTROLLER_NAME);
    }

    return new Request($language, self::CONTROLLER_NAME);
  }

  protected function ActionPost_New() {
    $context = $this->context;
    $session = $context->Session;
    $request = $context->Request;
    $language = $request->Language;
    $files = $request->Files;

    /** @var FilesViewStrings $strings */
    $strings = FilesViewStrings::GetInstance($language);

    if ($request->Error == 413) {
      $callback = new FilesCallback($strings::ERROR_FILE_SIZE_TOO_BIG);
      return $this->CreateCallbackRequest($callback, self::CONTROLLER_NAME);
    }

    if (count($files) == 0) {
      $callback = new FilesCallback($strings::ERROR_NO_FILE_SELECTED);
      return $this->CreateCallbackRequest($callback, self::CONTROLLER_NAME);
    }

    if (count($files) != 1 || $files[0]->Field != FilesView::FIELD_NAME_UPLOADED_FILE) {
      $callback = new FilesCallback($strings::ERROR_INVALID_REQUEST);
      return $this->CreateCallbackRequest($callback, self::CONTROLLER_NAME);
    }

    $file = $files[0];

    switch ($file->Error) {
      case UPLOAD_ERR_OK:
        break;

      case UPLOAD_ERR_NO_FILE:
        $callback = new FilesCallback($strings::ERROR_NO_FILE_SELECTED);
        return $this->CreateCallbackRequest($callback, self::CONTROLLER_NAME);

      case UPLOAD_ERR_INI_SIZE:
      case UPLOAD_ERR_FORM_SIZE:
        $callback = new FilesCallback($strings::ERROR_FILE_SIZE_TOO_BIG);
        return $this->CreateCallbackRequest($callback, self::CONTROLLER_NAME);

      case UPLOAD_ERR_PARTIAL:
        $callback = new FilesCallback($strings::ERROR_INTERRUPTED);
        return $this->CreateCallbackRequest($callback, self::CONTROLLER_NAME);

      default:
        $callback = new FilesCallback($strings::ERROR_SERVER_FAILURE);
        return $this->CreateCallbackRequest($callback, self::CONTROLLER_NAME);
    }

    if ($file->PostedSize != $file->RealSize) {
      $callback = new FilesCallback($strings::ERROR_FILE_CORRUPTED);
      return $this->CreateCallbackRequest($callback, self::CONTROLLER_NAME);
    }

    $model = new FilesModel($context);
    $saveRequest = new FileSaveRequest();
    $saveRequest->UserID = $session->UserID();
    $saveRequest->FileName = $file->Name;
    $saveRequest->ContentType = $file->Type;
    $saveRequest->UploadedPath = $file->Path;
    $result = $model->SaveFile($saveRequest);

    switch ($result) {
      case FileOperationResult::OPERATION_SUCCEEDED:
        break;

      case FileOperationResult::ERROR_FILE_NAME_EMPTY:
        $callback = new FilesCallback($strings::ERROR_FILE_NAME_EMPTY);
        return $this->CreateCallbackRequest($callback, self::CONTROLLER_NAME);

      case FileOperationResult::ERROR_FILE_NAME_TOO_LONG:
        $callback = new FilesCallback(sprintf($strings::ERROR_FILE_NAME_TOO_LONG, FileEntity::MAX_NAME_LENGTH));
        return $this->CreateCallbackRequest($callback, self::CONTROLLER_NAME);

      case FileOperationResult::ERROR_FILE_SIZE_TOO_BIG:
        $callback = new FilesCallback($strings::ERROR_FILE_SIZE_TOO_BIG);
        return $this->CreateCallbackRequest($callback, self::CONTROLLER_NAME);

      case FileOperationResult::ERROR_FILE_NAME_WRONG:
        $callback = new FilesCallback($strings::ERROR_FILE_NAME_WRONG);
        return $this->CreateCallbackRequest($callback, self::CONTROLLER_NAME);

      case FileOperationResult::ERROR_FILES_LIMIT_EXCEEDED:
        $callback = new FilesCallback(sprintf($strings::ERROR_LIMIT_EXCEEDED, Settings::MAX_FILES_PER_USER));
        return $this->CreateCallbackRequest($callback, self::CONTROLLER_NAME);

      case FileOperationResult::ERROR_SERVER_FAILURE:
        $callback = new FilesCallback($strings::ERROR_SERVER_FAILURE);
        return $this->CreateCallbackRequest($callback, self::CONTROLLER_NAME);

      case FileOperationResult::ERROR_USER_DOES_NOT_EXISTS:
        $callback = new FilesCallback($strings::ERROR_UNKNOWN_USER);
        return $this->CreateCallbackRequest($callback, self::CONTROLLER_NAME);

      default:
        throw new LogicException('Unexpected error code');
    }

    return new Request($request->Language, Application::DEFAULT_CONTROLLER, 'Index');
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