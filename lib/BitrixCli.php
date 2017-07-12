<?php

namespace Bitrixcli;

abstract class BitrixCli {

  public function __construct() {
    $this->_getCliParms();
    $this->_getViewFormat();
  }

  // показ предупреждения без прерывания выполнения
  protected function warning($msg) {
    fwrite(STDERR, $msg . PHP_EOL);
  }

  // показ ошибки с выходом
  protected function error($e) {
    fwrite(STDERR, $e->getMessage().PHP_EOL);
    die($e->getCode());
  }

  // получение всех параметров командной строки
  protected function getCliParms() {
    return $this->args;
  }

  protected $args;
  private static $paramsTypeSymbol = array(
    'noVal' => '',
    'val' => ':',
    'optionVal' => '::'
  );

  // получаем параметры командной строки, распарсенные с использованием static::$cliParams
  protected function _getCliParms() {
    $cliParamsStrShort = '';
    $cliParamsStrLong = array();
    foreach(static::$cliParams as $type => $params) {
      if(count($params) > 0) {
        $symbol = self::$paramsTypeSymbol[$type];
        foreach($params as $short => $long) {
          $cliParamsStrShort .= $short . $symbol;
          $cliParamsStrLong[] = $long . $symbol;
        }
      }
    }
    $this->args = getopt($cliParamsStrShort, $cliParamsStrLong);
  }

  protected function getCliIdParms() {
    $ids = array();

    $args =& $this->args;

    if(isset($args['i'])) {
      $_ids = $this->getIntParamList((array)$args['i']);
      $ids = array_merge($ids, $_ids);
    }
    if(isset($args['ID'])) {
      $_ids = $this->getIntParamList((array)$args['ID']);
      $ids = array_merge($ids, $_ids);
    }
    return $ids;
  }

  protected function getStdinIntParms() {
    $params = $this->getStdinParms();
    $ids = $this->getIntParamList($params);
    return $ids;
  }

  protected function getStdinStrParms() {
    $params = $this->getStdinParms();
    $ids = $this->getStrParamList($params);
    return $ids;
  }

  protected function getViewFormat() {
    return $this->viewFormat;
  }

  protected $viewFormat = 'var_dump';
  protected function _getViewFormat() {
    $args =& $this->args;
    switch (true) {
      case isset($args['view']):
        $this->viewFormat = $args['view'];
        break;
      case isset($args['format']):
        $this->viewFormat = $args['format'];
        break;
      case isset($args['v']):
        $this->viewFormat = $args['v'];
        break;
      case isset($args['f']):
        $this->viewFormat = $args['f'];
        break;
    }
  }

  // STD_IN
  protected function getStdinParms() {
    stream_set_blocking(STDIN, 0);
    $fh = fopen('php://stdin', 'r');
    $params = array();
    while($param = fgets($fh, 1024)) {
      $param = trim($param);
      if($param == '') continue;
      $params[] = $param;
    }
    fclose($fh);
    return $params;
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

  protected function getStrParamList(array $list) {
    $ret = array();
    if(count($list) > 0) foreach($list as $val) {
      $ret[] = (string)$val;
    }
    return $ret;
  }

}
