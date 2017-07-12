<?php

namespace Bitrixcli;

class BitrixCMS {

  protected $rootDir, $dir, $configFile, $config;
  public function __construct() {
    $this->dir = getcwd();
  }

  public function getConfig() {
    if(isset($this->config)) return $this->config;
    $this->config = include($this->configFile);
    return $this->config;
  }

  public function init() {
    $this->findrootDir($this->dir);
    $_SERVER["DOCUMENT_ROOT"] = $this->rootDir;
    return $this;
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



}
