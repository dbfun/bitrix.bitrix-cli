<?php

namespace Bitrixcli;

abstract class BitrixCli {

  protected function warning($msg) {
    fwrite(STDERR, $msg . PHP_EOL);
  }

  protected function error($e) {
    fwrite(STDERR, $e->getMessage().PHP_EOL);
    die($e->getCode());
  }

  protected function getCliIdParms() {
    $ids = array();
    $args = getopt('i:', array('ID:'));
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

}
