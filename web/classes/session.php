<?php
class Session {
  const USER_ID       = 'USER_ID';
  const LAST_ACTIVITY = 'LAST_ACTIVITY';
  const CALLBACK      = 'CALLBACK';

  static protected $instance;
  protected $expired;
  protected $userID;

  public static function Open() {
    if (isset(self::$instance))
      throw new LogicException("A session can not be opened more than once per request.");

    self::$instance = new Session();
    return self::$instance;
  }

  protected function __construct() {
    $status = session_status();

    if ($status == PHP_SESSION_DISABLED)
      throw new RuntimeException("Sessions are disabled.");

    if ($status == PHP_SESSION_ACTIVE)
      throw new RuntimeException("A session is started already.");

    session_cache_limiter('nocache');

    if (!session_start())
      throw new RuntimeException('Unable to start a session.');

    $this->expired = false;
    $this->userID = null;
    if (isset($_SESSION[self::USER_ID])) {
      $this->userID = $_SESSION[self::USER_ID];

      if (isset($_SESSION[self::LAST_ACTIVITY])) {
        $this->expired = (time() - $_SESSION[self::LAST_ACTIVITY] > Settings::SESSION_TIMEOUT);
      }

      if (!$this->expired) {
        $_SESSION[self::LAST_ACTIVITY] = time();
      }
    }
  }

  public function Close() {
    session_write_close();
  }

  public function IsClosed() {
    return (session_status() != PHP_SESSION_ACTIVE);
  }

  public function Reset($userID = null) {
    if ($this->IsClosed())
      throw new LogicException('Can not modify a closed session.');

    $this->userID = $userID;
    $this->expired = false;
    session_regenerate_id(true);
    session_unset();

    if (isset($userID)) {
      $_SESSION[self::USER_ID] = $userID;
    }
  }

  public function Destroy() {
    $this->Reset();
    session_destroy();
  }

  public function IsExpired() {
    return $this->expired;
  }

  public function IsAuthenticated() {
    return isset($this->userID) && !$this->expired;
  }

  public function UserID() {
    return $this->expired ? null : $this->userID;
  }

  public function GetValue($key) {
    if ($this->IsClosed())
      throw new LogicException('Can not read from a closed session');

    return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
  }

  public function SetValue($key, $value) {
    if ($this->IsClosed())
      throw new LogicException('Can not write into a closed session');

    $_SESSION[$key] = $value;
  }

  public function UnsetValue($key) {
    if ($this->IsClosed())
      throw new LogicException('Can not write into a closed session');

    if (isset($_SESSION[$key])) {
      unset($_SESSION[$key]);
    }
  }

  public function GetArrayValue($arrayKey, $valueKey) {
    if ($this->IsClosed())
      throw new LogicException('Can not read from a closed session');

    return isset($_SESSION[$arrayKey]) && isset($_SESSION[$arrayKey][$valueKey]) ?
      $_SESSION[$arrayKey][$valueKey] :
      null;
  }

  public function SetArrayValue($arrayKey, $valueKey, $value) {
    if ($this->IsClosed())
      throw new LogicException('Can not read from a closed session');

    if (!isset($_SESSION[$arrayKey])) {
      $_SESSION[$arrayKey] = array();
    }
    $_SESSION[$arrayKey][$valueKey] = $value;
  }

  public function UnSetArrayValue($arrayKey, $valueKey) {
    if ($this->IsClosed())
      throw new LogicException('Can not read from a closed session');

    if (isset($_SESSION[$arrayKey]) && isset($_SESSION[$arrayKey][$valueKey])) {
      unset($_SESSION[$arrayKey][$valueKey]);
    }
  }
}