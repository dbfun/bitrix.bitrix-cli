<?php

namespace Bitrixcli;

class DataView {

  private $data;
  public function __construct($data) {
    switch(true) {
      case is_array($data):
        $data = !defined('LANG_CHARSET') || LANG_CHARSET == 'UTF-8' ? $data : $this->iconvArray(LANG_CHARSET, 'utf-8', $data);
        break;
    }
    $this->data = $data;
  }

  public function view($format) {
    switch($format) {
      case 'var_dump':
        $this->var_dump();
        break;
      case 'var_export':
        $this->var_export();
        break;
      case 'letter':
        $this->letter();
        break;
      default:
        throw new Exception("No such view format", 1);
    }
  }

  protected function letter() {
    echo $this->data['ID'] . ' > ' . $this->data['EVENT_NAME'].PHP_EOL;
    echo implode(PHP_EOL, $this->data['C_FIELDS']);
    echo PHP_EOL.PHP_EOL;
  }

  protected function var_dump() {
    var_dump($this->data);
    echo PHP_EOL;
  }

  protected function var_export() {
    var_export($this->data);
    echo PHP_EOL;
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
