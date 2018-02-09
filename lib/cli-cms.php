<?php

/*

Подключение CMS Bitrix

Использование:
require('lib/cli-cms.php');

*/


require(__DIR__ . '/index.php');
$BitrixCMS = new \Bitrixcli\BitrixCMS();
$BitrixCMS->init();

$LANGUAGE_ID = getenv('BX_ENV_LANGUAGE_ID');
if($LANGUAGE_ID === false) { $LANGUAGE_ID = 'ru'; }

// Язык сайта

define('LANGUAGE_ID', $LANGUAGE_ID);
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

// Уровень ошибок

$BX_ENV_ERR_REP = getenv('BX_ENV_ERR_REP');
if($BX_ENV_ERR_REP === false) { $BX_ENV_ERR_REP = 'E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED'; }
$BX_ENV_ERR_REP = eval('return ' . $BX_ENV_ERR_REP . ';');
error_reporting($BX_ENV_ERR_REP);
