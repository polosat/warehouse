<?php
require_once __DIR__ . '/../../views/files/view.php';

class FilesController extends Controller {
  protected function Action_Index() {
    $context = $this->context;
    $request = $context->request;
//    $session = $context->session;

    $viewBag = new FilesViewBag();
    $view = new FilesView($viewBag, $request->language);
    $this->InitializeLayoutView($view);

//    $strings = $view->FilesStrings;
    $view->Render();
  }

  protected function PushCallback($reason) {

  }

  protected function GetCallback() {

  }

  protected function RequiresAuthentication()
  {
    return true;
  }

  protected function AllowsExpiredSession()
  {
    return false;
  }

  protected function HeaderItemsMask() {
    return HeaderItem::PROFILE | HeaderItem::LOGOUT;
  }
}