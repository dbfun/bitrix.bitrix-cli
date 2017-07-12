<?php

namespace Bitrixcli;

class DataView {

  private $data;
  public function __construct($data) {
    $this->data = $data;
  }

  public function showVar($format = null) {
    $var = !defined('LANG_CHARSET') || LANG_CHARSET == 'UTF-8' ? $this->data : $this->iconvArray(LANG_CHARSET, 'utf-8', $this->data);
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
