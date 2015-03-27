<?php
class RuntimeContext {
  public $Session;
  public $Request;
  public $Languages;
  public $SerializedCallback;

  public function __construct(Request $request, Session $session) {
    /** @var Request | PostRequest $request */
    $this->Request = $request;
    $this->Session = $session;

    // Read serialized callback and remove it from the session
    $this->SerializedCallback = $this->Session->GetValue(Session::CALLBACK);
    $this->Session->UnsetValue(Session::CALLBACK);

    // Setup languages
    $this->Languages = explode('|', Settings::LANGUAGES);
    if ($this->Languages[0] == '')
      throw new LogicException('Languages have not been set.');

    if (!in_array($this->Request->Language, $this->Languages)) {
      $this->Request->Language = '';
    }
  }

  public function PushCallback($callback) {
    $serializedCallback = serialize($callback);
    $this->Session->SetValue(Session::CALLBACK, $serializedCallback);
  }
}