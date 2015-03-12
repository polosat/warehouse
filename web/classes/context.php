<?php
class ModelContext {
  public $Dsn;
  public $DbUser;
  public $DbPassword;
  public $StoragePath;

  public function __construct($dsn, $user, $password, $storagePath, $storageUri) {
    $this->Dsn = $dsn;
    $this->DbUser = $user;
    $this->DbPassword = $password;
    $this->StoragePath = $storagePath;
    $this->StorageUri = $storageUri;
  }
}

class ControllerContext extends ModelContext {
  /** @var Session */
  public $session;
  /** @var Request | PostRequest */
  public $request;

  public $languages;

  public $serializedCallback;

  public function __construct(Request $request, Session $session) {
    // Setup database
    $dsn = 'mysql:host='. Settings::DB_HOST . ';dbname='. Settings::DB_NAME . ';charset=' . Settings::DB_CHARSET;
    parent::__construct($dsn, Settings::DB_USER, Settings::DB_PASSWORD, Settings::STORAGE_PATH, Settings::STORAGE_URI);

    $this->request = $request;
    $this->session = $session;

    // Read serialized callback and remove it from the session
    $this->serializedCallback = $this->session->GetValue(Session::CALLBACK);
    $this->session->UnsetValue(Session::CALLBACK);

    // Setup languages
    $this->languages = explode('|', Settings::LANGUAGES);
    if ($this->languages[0] == '')
      throw new LogicException('Languages have not been set.');

    if (!in_array($this->request->language, $this->languages)) {
      $this->request->language = '';
    }
  }

  public function PushCallback($callback) {
    $serializedCallback = serialize($callback);
    $this->session->SetValue(Session::CALLBACK, $serializedCallback);
  }
}