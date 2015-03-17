<?php
class RuntimeContext {
  public $session;
  public $request;
  public $languages;
  public $serializedCallback;

  public function __construct(Request $request, Session $session) {
    /** @var Request | PostRequest $request */
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