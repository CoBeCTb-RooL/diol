<?php
error_reporting(E_ERROR  | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING | E_USER_ERROR | E_USER_WARNING | E_USER_NOTICE);
//session_start();

#	необходимые подключения
require_once('config.php');
require_once(INCLUDE_DIR.'/funx.php');

#	необходимые классы
require_once(CLASSES_DIR.'/Slonne.php');
Slonne::loadDir(CLASSES_DIR);
require_once(CONTROLLERS_DIR.'/MainController.php');

Slonne::loadDir(ADMIN_DIR.'/'.MODELS_DIR);
Slonne::loadDir(MODELS_DIR);

#	подключение к базе
DB::connect();

#	считываем рефералки
ReferalTail::init();

#	инициализация констант
/*$t = new Timer('Константы');
Constants::assemble();
$t->stop();
*/


$tmp = null;

?>