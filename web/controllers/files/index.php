<?php
require_once __DIR__ . '/../../views/files/view.php';
require_once __DIR__ . '/../../models/files/model.php';
require_once __DIR__ . '/callback.php';

class FilesController extends Controller {
  // TODO: Replace exact definitions in the Application class by ControllerClassName::Name (for all controllers)
  const NAME          = 'files';
  const ACTION_GET    = 'get';
  const ACTION_DELETE = 'delete';

  protected function Action_Index() {
    $context = $this->context;
    $session = $context->session;
    $request = $context->request;

    $userID = $session->UserID();
    $viewBag = new FilesViewBag();

    $model = new FilesModel($context);
    $files = $model->GetUserFiles($userID);

    $viewBag->DeleteUri = '/' . self::NAME . '/' . self::ACTION_DELETE;
    $downloadUriBase = '/' . self::NAME . '/' . self::ACTION_GET . '/';
    foreach ($files as $file) {
      $item = new FileViewItem();
      $item->ID = $file->FileID;
      $item->Name = $file->FileName;
      $item->Size = $file->Size;
      $item->UploadedOn = $this->GetTimestamp($file->UploadedOn);
      $item->Uri = $downloadUriBase . $file->FileID . '/' . rawurlencode($file->FileName);
      $viewBag->Files[] = $item;
    }

    $view = new FilesView($viewBag, $request->language);
    $this->InitializeLayoutView($view);
    $view->Render();
  }

  protected function Action_Get() {
    // TODO: Check userID here as well. Should we perform this checking at the model level?
    $context = $this->context;
    $session = $context->session;
    $request = $context->request;

    if ($request->argument) {
      $a = explode('/', $request->argument);
      $userID = $session->UserID();
      $fileID = $a[0];
      $fileName = $a[1];
      $path = $context->StoragePath . "$fileID.$userID";
      if (!file_exists($path)) {
        // show message box "The file does not exists";
        return null;
      }

      // TODO Verify file name as well as fileID
      $model = new FilesModel($context);
      $file = $model->GetFile($request->argument);
      if ($file->UserID != $userID) {
        // show message box "The file does not exists";
        return null;
      }

      $contentType = empty($file->ContentType) ? 'application/octet-stream' : $file->ContentType;
      $uri = $context->StorageUri . "$fileID.$userID";
      header('X-Accel-Redirect: ' . $uri);
      header('Content-Type: ' . $contentType);
      header('Content-Disposition: attachment');

      return null;
    }
  }

  protected function ActionPost_Delete() {
    $a = 0;
  }

  // TODO: rename to 'new'
  protected function ActionPost_Index() {
    $context = $this->context;
    $session = $context->session;
    $request = $context->request;
    $files = $request->Files;

    if ($request->error == 413) {
      // show message box "The file size exceeds the limit allowed"
      return null;
    }

    if (count($files) == 0) {
      // show message box "No file was selected"
      return null;
    }

    if (count($files) != 1 || $files[0]->Field != FilesView::FIELD_NAME_USER_FILE) {
      // show message box "Invalid request"
      return null;
    }

    $file = $files[0];

    switch ($file->Error) {
      case UPLOAD_ERR_OK:
        break;

      case UPLOAD_ERR_NO_FILE:
        // show message box "No file was selected"
        break;

      case UPLOAD_ERR_INI_SIZE:
      case UPLOAD_ERR_FORM_SIZE:
        // show message box "The file size exceeds the limit allowed"
        return null;

      case UPLOAD_ERR_PARTIAL:
        // show message box "The file uploading was interrupted"
        return null;

      default:
        // show message box "Server side error occurred"
        return null;
    }

    if ($file->PostedSize != $file->RealSize) {
      // show message box "The uploaded file was damaged"
      return null;
    }

    $model = new FilesModel($context);
    $saveRequest = new FileSaveRequest();
    $saveRequest->UserID = $session->UserID();
    $saveRequest->FileName = $file->Name;
    $saveRequest->ContentType = $file->Type;
    $saveRequest->UploadedPath = $file->Path;
    $result = $model->SaveFile($saveRequest);

    return new Request($request->language, Application::DEFAULT_CONTROLLER, 'Index');
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