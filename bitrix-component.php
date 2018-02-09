<?php

/*
Получение опций
Указываются опции через STD_IN (с новой строки), и/или через перечисление в параметрах
*/

namespace Bitrixcli;

require(__DIR__ . '/lib/cli-cms.php');

class OptionCli extends BitrixCli {

  protected $files = array(), $item;
  public static $cliParams = array(
    'noVal' // без значения
      => array(),
    'val' // со значением
      => array('x' => 'file', 'v' => 'view', 'f' => 'format'),
    'optionVal' // с необязательным значением
      => array(),
  )
  ;

  public function __construct($BitrixCMS) {
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

    if(isset($params['x'])) {
      $this->files = array_merge($this->files, (array)$params['x']);
    }

    if(isset($params['file'])) {
      $this->files = array_merge($this->files, (array)$params['file']);
    }

    $files = $this->getStdinStrParms();
    $this->files = array_merge($this->files, $files);

    if(count($this->files) == 0) {
      try {
        $file = getcwd() . '/index.php';
        if(!file_exists($file)) throw new Exception('No files', 1);
        $this->files = [$file];
      } catch (Exception $e) {

      }


    }
  }

  protected $viewFormat = 'component';
  protected function outputElements() {
    $view = $this->getViewFormat();
    foreach($this->files as $file) {
      $this->getFile($file);
      if(isset($this->item)) {
        $DataView = new DataView($this->item);
        $DataView->view($view);
      }
    }
  }

  protected function getFile($file) {
    unset($this->item);
    if(!$file) {
      $this->warning("File not exists: " . $file);
    }

    try {
      if(!file_exists($file)) throw new Exception("File not exists: " . $file, 1);
    } catch (Exception $e) {
      $_file = rtrim($file, '/') . '/index.php';
      if(!file_exists($_file)) {
        $this->warning("File not exists: " . $file);
        return;
      }
      $file = $_file;
    }

    $fileContent = file_get_contents($file);
    if(preg_match_all('~\$APPLICATION\s*->\s*IncludeComponent\s*\(\s*["|\'](.*?)["|\']~i', $fileContent, $m)) {
      $this->item = array('file' => $file, 'component' => $m[1]);
    } else {
      $this->warning('No "$APPLICATION->IncludeComponent" founded: ' . $file);
    }

  }

}

$OptionCli = new OptionCli($BitrixCMS);
$OptionCli->run();
