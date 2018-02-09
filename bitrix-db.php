<?php

/*
Получение параметров подключения к БД
*/

namespace Bitrixcli;

require(__DIR__ . '/lib/cli-cms.php');

class DbCli extends BitrixCli {

  protected $limit = 13, $isShowLogFileName = false;
  public static $cliParams = array(
    'noVal' // без значения
      => array(),
    'val' // со значением
      => array(),
    'optionVal' // с необязательным значением
      => array(),
  )
  ;

  public function __construct($BitrixCMS) {
    parent::__construct($BitrixCMS);
  }

  public function run() {
    try {
      $this->outputElements();
    } catch (Exception $e) {
      $this->error($e);
    }
  }

  protected function outputElements() {
    $config = $this->BitrixCMS->getConfig();
    $connection =& $config['connections']['value']['default'];
    if(!isset($connection) || !$connection) throw new Exception("Empty settings: connections");

    $DataView = new DataView($connection);
    $DataView->view('db');
  }

}

$DbCli = new DbCli($BitrixCMS);
$DbCli->run();
