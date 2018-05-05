<?php function vd($a){echo '<pre>'; var_dump($a); echo '</pre>'; }

error_reporting(E_ERROR  | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING | E_USER_ERROR | E_USER_WARNING | E_USER_NOTICE);
session_start();


require_once('config.php');

require_once(CLASSES_DIR.'/Timer.php');
require_once(CLASSES_DIR.'/Url.php');
require_once('header.php');


# 	глобальный таймер
$globalTimer = new Timer('ГЛОБАЛЬНЫЙ ТАЙМЕР', Timer::TYPE_GLOBAL_TIMER);



$url = $_SERVER['PATH_INFO'];




# 	адаптация урлов
Route::$routes = array(		
		new Route('/category/{int}', 			'/adv/cat/{int}', 			Route::SPISOK_OBYAVLENIY_KATEGORII),
		new Route('/category', 					'/adv/cat/', 				Route::SPISOK_OBYAVLENIY_KATEGORII),
		new Route('/categories', 				'/adv/catsList', 			Route::SPISOK_KATEGORIY),
		new Route('/adv/{int}', 				'/adv/item/{int}', 			Route::KARTOCHKA_OBYAVLENIYA),
		new Route('/news/{int}', 				'/news/{int}', 				Route::NOVOSTI_KARTOCHKA),
		
		new Route('/profile/advs/edit', 		'/cabinet/advs/edit/',		Route::CABINET_ADV_EDIT),
		new Route('/profile/advs/', 			'/cabinet/profile/advs/',	Route::CABINET_MY_ADVS),
		new Route('/profile/edit', 				'/cabinet/profile/edit',	Route::CABINET_PROFILE_EDIT),
		new Route('/profile/password_change', 	'/cabinet/profile/password_change',Route::CABINET_PROFILE_CHANGE_PASSWORD),
		new Route('/profile/password_reset', 	'/cabinet/profile/password_reset_claim',Route::CABINET_PASSWORD_RESET_CLAIM),
		new Route('/profile', 					'/cabinet', 				Route::CABINET),
		
		// 	просто роуты для генерации ссылок
		new Route('/index', 					'', 						Route::MAIN),
		new Route('/adv/artnums_list_ajax', 	'', 						Route::ARTNUMS_LIST_BY_BRAND),
		new Route('/suggestions', 				'', 						Route::SUGGESTIONS),
		new Route('/news', 						'', 						Route::NEWS),
		new Route('/help', 						'', 						Route::HELP),

);
if($route = Route::getSuitableRoute($url))
	$url = $route->urlTransformed ? $route->urlTransformed : $route->pattern;


//vd($route);
	
	
$CORE = new Core($url);

# 	ГЛОБАЛЬНАЯ ЗАГЛУШКА
if($_CONFIG['ZAGLUSHKA'] && !$CORE->isAdminka)
{

	require(ROOT.'/'.VIEWS_DIR.'/'.LAYOUTS_DIR.'/zaglushka.php'); die;
}
//vd($_CONFIG);

$CORE->route = $route;
$CONTENT = new Content();

$t = new Timer('Подгрузка контроллера');
require_once($CORE->controllerPath);
//Slonne::loadDir( (IS_ADMINKA ? ADMIN_DIR.'/' : '') .  CONTROLLERS_DIR);
$t->stop();

//echo "!";

ob_start();
$CORE->setLayout($CORE->isAdminka ? $_CONFIG['DEFAULT_ADMIN_LAYOUT'] : $_CONFIG['DEFAULT_LAYOUT']);
call_user_func($CORE->controller.'::routifyAction');

$CORE->executeAction();
$CONTENT->content = ob_get_clean();





# 	запоминаем таймеры (только если есть лэйаут)
Timer::fixSessionTimers();
if($CORE->layout)
{
	$globalTimer->stop(); 	# 	завершение глобального таймера
	unset($_SESSION['timers']);
	$CORE->globalTimer = clone $globalTimer;
}
foreach($_SESSION['timersNewPortion'] as $t)
	$_SESSION['timers'][] = $t;

unset($_SESSION['timersNewPortion']);




$CORE->renderLayout();


?>

