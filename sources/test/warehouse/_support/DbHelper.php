<?php
namespace Codeception\Module;

use Codeception\Lib\Driver\Db as Driver;
use Codeception\Exception\Module as ModuleException;
use Codeception\Exception\ModuleConfig as ModuleConfigException;
use Codeception\Configuration as Configuration;

class DbHelper extends Db {
  public function _initialize()
  {
    if ($this->config['dump'] && ($this->config['cleanup'] or ($this->config['populate']))) {
      $sql = '';
      $dumpFiles = is_array($this->config['dump']) ? $this->config['dump'] : [$this->config['dump']];

      foreach ($dumpFiles as $dump) {
        if (!file_exists(Configuration::projectDir() . $dump)) {
          throw new ModuleConfigException(
            __CLASS__,
            "\nFile with dump doesn't exist.
              Please, check path for sql file: " . $dump
          );
        }
        $sql .= file_get_contents(Configuration::projectDir() . $dump) . "\n";
      }

      $sql = preg_replace('%/\*(?!!\d+)(?:(?!\*/).)*\*/%s', "", $sql);
      if (!empty($sql)) {
        $this->sql = explode("\n", $sql);
      }
    }

    try {
      $this->driver = Driver::create($this->config['dsn'], $this->config['user'], $this->config['password']);
    } catch (\PDOException $e) {
      throw new ModuleException(__CLASS__, $e->getMessage() . ' while creating PDO connection');
    }

    $this->dbh = $this->driver->getDbh();

    // starting with loading dump
    if ($this->config['populate']) {
      $this->cleanup();
      $this->loadDump();
      $this->populated = true;
    }
  }
}