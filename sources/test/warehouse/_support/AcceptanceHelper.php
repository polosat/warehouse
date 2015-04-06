<?php
namespace Codeception\Module;

use Codeception\Module;
use Codeception\Configuration as Configuration;

class AcceptanceHelper extends Module {
  protected $requiredFields = ['storage'];
  protected $config = ['cleanup' => true];

  public function _before() {
    $this->_cleanupData();
  }

  public function _cleanupData() {
    if ($this->config['cleanup']) {
      /** @var Filesystem $fileSystem */
      $fileSystem = $this->getModule('Filesystem');
      $path = Configuration::projectDir() . $this->config['storage'];
      $fileSystem->cleanDir($path);
    }
  }
}
