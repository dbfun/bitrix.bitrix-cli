<?php

/*
Получение данных элементов из инфоблоков
Указываются ID через STD_IN (с новой строки), и/или через перечисление в параметрах
*/

namespace Bitrixcli;

require(__DIR__ . '/lib/cli-cms.php');


class ElementCli extends BitrixCli {

  protected $ids = array(), $item, $isHistory;
  public function __construct() {
    if(!\CModule::IncludeModule('iblock')) throw new Exception("Can not include iblock");
  }

  public function run() {
    try {
      $this->getParms();
      $this->getElements();
    } catch (Exception $e) {
      $this->error($e);
    }
  }

  protected function getElements() {
    $arSort = array("SORT" => "ASC");
    $arSelect = array("*");
    $arNavStartParams = false;
    foreach($this->ids as $id) {
      $this->getElement($id);
      if(isset($this->item)) {
        $this->showVar($this->item);
      }
    }
  }

  private function showVar($_var, $format = null) {
    $var = !defined('LANG_CHARSET') || LANG_CHARSET == 'UTF-8' ? $_var : $this->iconvArray(LANG_CHARSET, 'utf-8', $_var);
    switch($format) {
      case 'letter':
        $var = (array)$var;
        foreach($var as $v) {
          echo $v['ID'] . ' > ' . $v['EVENT_NAME'].PHP_EOL;
          echo implode(PHP_EOL, $v['C_FIELDS']);
          echo PHP_EOL.PHP_EOL;
        }
        break;
      default:
        var_dump($var);
    }

  }

  protected function getElement($id) {
    unset($this->item);
    $this->isHistory = false;

    $arFilter = array("ID" => $id);
    $rs = \CIBlockElement::GetList($arSort, $arFilter, false, $arNavStartParams, $arSelect);
    if($rs->SelectedRowsCount() == 0) {
      $this->isHistory = true;
      $arFilter['SHOW_NEW'] = 'Y';
      $rs = \CIBlockElement::GetList($arSort, $arFilter, false, $arNavStartParams, $arSelect);
    }
    if($rs->SelectedRowsCount() == 0) {
      $this->warning("Can not find element with id: " . $id);
    } else {
      $this->item = $rs->Fetch();
    }
  }

  protected function getParms() {
    $this->getCliParms();
    $this->getStdinParms();
    $this->ids = array_filter(array_unique($this->ids));
    if(count($this->ids) == 0) throw new Exception("No ids", 1);
  }

  protected function getCliParms() {
    $args = getopt('i:', array('ID:'));
    if(isset($args['i'])) {
      $ids = $this->getIntParamList((array)$args['i']);
      $this->ids = array_merge($this->ids, $ids);
    }
    if(isset($args['ID'])) {
      $ids = $this->getIntParamList((array)$args['ID']);
      $this->ids = array_merge($this->ids, $ids);
    }
  }

  protected function getStdinParms() {
    stream_set_blocking(STDIN, 0);
    $fh = fopen('php://stdin', 'r');
    $ids = array();
    while($id = fgets($fh, 1024)) {
      $id = trim($id);
      if($id == '') continue;
      $ids[] = $id;
    }
    fclose($fh);
    $ids = $this->getIntParamList($ids);
    $this->ids = array_merge($this->ids, $ids);
  }

  protected function getIntParamList(array $list) {
    $ret = array();
    if(count($list) > 0) foreach($list as $val) {
      if(is_numeric($val)) {
        $ret[] = (int)$val;
      } else {
        $this->warning($val . ' is not valid numeric value');
      }
    }
    return $ret;
  }

  protected function warning($msg) {
    fwrite(STDERR, $msg . PHP_EOL);
  }

  protected function error($e) {
    fwrite(STDERR, $e->getMessage().PHP_EOL);
    die($e->getCode());
  }

  protected function iconvArray($charset_from, $charset_to, $arData) {
    $arTmp = array();
    foreach($arData as $k => $_v)
    {
      if(is_array($_v)) {
        $v = $this->iconvArray($charset_from, $charset_to, $_v);
      } else {
        $v = iconv($charset_from, $charset_to.'//IGNORE', $_v);
        if(empty($v)) $v = iconv($charset_from, $charset_to.'//TRANSLIT', $_v);
      }
      $k = iconv($charset_from, $charset_to.'//IGNORE', $k);
      $arTmp[$k] = $v;
    }
    return $arTmp;
  }

}

// global $argv; die(var_dump($argv));

$ElementCli = new ElementCli();
$ElementCli->run();
