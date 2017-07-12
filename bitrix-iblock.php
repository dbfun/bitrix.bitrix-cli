<?php

/*
Получение сведений об инфоблоке
Указываются ID через STD_IN (с новой строки), и/или через перечисление в параметрах
*/

namespace Bitrixcli;

require(__DIR__ . '/lib/cli-cms.php');

class SectionCli extends BitrixCli {

  protected $ids = array(), $item;
  public static $cliParams = array(
    'noVal' // без значения
      => array(),
    'val' // со значением
      => array('i' => 'ID', 'v' => 'view', 'f' => 'format'),
    'optionVal' // с необязательным значением
      => array(),
  )
  ;

  public function __construct() {
    if(!\CModule::IncludeModule('iblock')) throw new Exception('Can not include iblock');
    parent::__construct();
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
    $ids = $this->getCliIdParms();
    $this->ids = array_merge($this->ids, $ids);

    $ids = $this->getStdinIntParms();
    $this->ids = array_merge($this->ids, $ids);

    $this->ids = array_filter(array_unique($this->ids));
    if(count($this->ids) == 0) throw new Exception('No ids', 1);
  }

  protected $viewFormat = 'iblock';
  protected function outputElements() {
    foreach($this->ids as $id) {
      $this->getIblock($id);
      if(isset($this->item)) {
        $DataView = new DataView($this->item);
        $view = $this->getViewFormat();
        $DataView->view($view);
      }
    }
  }

  protected function getIblock($id) {
    unset($this->item);

    $arSort = array('SORT' => 'ASC');

    $arFilter = array('id' => $id);
    $rs = \CIBlock::GetList($arSort, $arFilter, $bIncCnt = true);
    if($rs->SelectedRowsCount() == 0) {
      $this->warning('Can not find iblock by ID: ' . $id);
    } else {
      $this->item = $rs->Fetch();
    }
  }

}

$SectionCli = new SectionCli();
$SectionCli->run();
