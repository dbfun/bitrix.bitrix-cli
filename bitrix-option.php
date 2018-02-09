<?php

/*
Получение опций
Указываются опции через STD_IN (с новой строки), и/или через перечисление в параметрах
*/

namespace Bitrixcli;

require(__DIR__ . '/lib/cli-cms.php');

class OptionCli extends BitrixCli {

  protected $options = array(), $item;
  public static $cliParams = array(
    'noVal' // без значения
      => array(),
    'val' // со значением
      => array('o' => 'option', 'v' => 'view', 'f' => 'format'),
    'optionVal' // с необязательным значением
      => array(),
  )
  ;

  public function __construct($BitrixCMS) {
    die('TODO'); // передача модуля и опции
    parent::__construct($BitrixCMS);
  }

  public function run() {
    try {
      $this->getParms();
      $this->outputElements();
    } catch (Exception $e) {
      $this->error($e);
    }
  }

  protected function getParms() {
    $params = $this->getCliParms();

    if(isset($params['o'])) {
      $this->options = array_merge($this->options, (array)$params['o']);
    }

    if(isset($params['option'])) {
      $this->options = array_merge($this->options, (array)$params['option']);
    }

    $options = $this->getStdinStrParms();
    $this->options = array_merge($this->options, $options);

    if(count($this->options) == 0) throw new Exception('No options', 1);
  }

  protected $viewFormat = 'option';
  protected function outputElements() {
    $view = $this->getViewFormat();
    foreach($this->options as $opt) {
      $this->getOpt($opt);
      if(isset($this->item)) {
        $DataView = new DataView($this->item);
        $DataView->view($view);
      }
    }
  }

  // TODO
  protected function getOpt($name) {
    $this->item = \COption::GetOptionString($moduleName, $name, '');
  }

}

$OptionCli = new OptionCli($BitrixCMS);
$OptionCli->run();
