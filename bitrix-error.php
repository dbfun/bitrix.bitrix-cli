<?php

/*
Получение ошибок
Указываются опции: лимит и показывать ли имя файла лога
*/

namespace Bitrixcli;

require(__DIR__ . '/lib/cli-cms.php');

class ErrorCli extends BitrixCli {

  protected $BitrixCMS, $limit = 13, $isShowLogFileName = false;
  public static $cliParams = array(
    'noVal' // без значения
      => array('f' => 'file'),
    'val' // со значением
      => array('l' => 'limit'),
    'optionVal' // с необязательным значением
      => array(),
  )
  ;

  public function __construct($BitrixCMS) {
    $this->BitrixCMS = $BitrixCMS;
    parent::__construct();
  }

  public function run() {
    try {
      $this->getParms();
      $this->outputLog();
    } catch (Exception $e) {
      $this->error($e);
    }
  }

  protected function getParms() {
    $params = $this->getCliParms();

    if(isset($params['l'])) {
      $this->limit = $params['l'];
    }

    if(isset($params['limit'])) {
      $this->limit = $params['limit'];
    }

    if(isset($params['f'])) {
      $this->isShowLogFileName = true;
    }

    if(isset($params['file'])) {
      $this->isShowLogFileName = true;
    }
  }

  protected function outputLog() {
    $config = $this->BitrixCMS->getConfig();

    $logFile =& $config['exception_handling']['value']['log']['settings']['file'];
    if(!isset($logFile) || !$logFile) throw new Exception("Empty settings: log file");

    if($this->isShowLogFileName) {
      echo $logFile . PHP_EOL;
    }

    $cmd = sprintf('tail -n %d %s', $this->limit, escapeshellarg($logFile));
    echo shell_exec($cmd);
    die();
  }

}

$ErrorCli = new ErrorCli($BitrixCMS);
$ErrorCli->run();
