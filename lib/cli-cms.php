<?php

/*

Подключение CMS Bitrix

Использование:
require('lib/cli-cms.php');

*/


require(__DIR__ . '/index.php');
$BitrixCMS = new \Bitrixcli\BitrixCMS();
$BitrixCMS->init();

define("LANGUAGE_ID", "ru"); // TODO заполнять из параметров вызова и/или переменной окружения
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

// Выводим ошибки
// TODO сделать вывод ошибок опционально
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
