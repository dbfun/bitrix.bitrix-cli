<?php

namespace Bitrixcli;

class BitrixCMS {

  protected $rootDir, $dir, $configFile, $config;
  public function __construct() {
    $this->dir = getcwd();
  }

  public function getConfig() {
    $this->findrootDir($this->dir);
    $this->loadBitrixConfig();
  }

  public function getRootDir() {
    return $this->rootDir;
  }

  protected function findrootDir($dir) {
    if(!$dir || $dir == '/') throw new Exception("Can not find bitrix root dir");
    $configFile = $dir . '/bitrix/.settings.php';
    if(file_exists($configFile)) {
      $this->rootDir = $dir;
      $this->configFile = $configFile;
      chdir($this->rootDir);
      return;
    }
    return $this->findrootDir(dirname($dir));
  }

  protected function loadBitrixConfig() {
    $this->config = include($this->configFile);
  }

}
