<?php
/*
#	формируем выпадающее меню
$menu = Module::getList($onlyActive=true, $pid=0);
foreach($menu as $key=>$val)
{
	$menu2[$key]['item'] = $val;
	$menu2[$key]['subs'] = Module::getList($onlyActive=true, $pid=$val->id ); 
}
$GLOBALS['MENU2'] = $menu2; 



#	ищем текущий модуль
foreach($menu as $item)
{
	if('/'.ADMIN_URL_SIGN.'/'.$item->path == $_SERVER['REQUEST_URI'])
	{
		$_GLOBALS['CURRENT_MODULE'] = $item;
		$_SESSION['LAST_MODULE_ID'] = $item->id;
		break;
	}
}
#	чтобы в аджаксовых запросах была инфа о том, к какому модулю относятся
if(intval($_SESSION['LAST_MODULE_ID']) && !$_GLOBALS['CURRENT_MODULE'])
	$_GLOBALS['CURRENT_MODULE'] = Module::get($_SESSION['LAST_MODULE_ID']);
	
	
	
if($_GLOBALS['ADMIN'])
{
	$_GLOBALS['ADMIN']->initGroup();
	$_GLOBALS['ADMIN']->group->initPrivilegesArr();
	//$ADMIN = $_GLOBALS['ADMIN'];
}
	*/
?>