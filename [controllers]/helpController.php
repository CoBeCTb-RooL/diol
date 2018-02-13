<?php
class HelpController extends MainController{
	

	function routifyAction()
	{
		global $CORE;
		$section = $CORE->params[0];
		$p = $CORE->params[1];
		//vd($CORE->params);
		
		/*if($id = intval($CORE->params[0]))
			$action = 'newsItem';
		else
			$action = 'newsList';
		
		if($action)
			$CORE->action = $action;*/
	}
	
	
	function index()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
				
		$CONTENT->setTitle('Помощь');
		

		$crumbs = array();
		$crumbs[] = '<a href="'.Route::getByName(Route::MAIN)->url().'">Главная</a>';
		$crumbs[] = 'Помощь';
		$MODEL['crumbs'] = $crumbs;
		
		Core::renderView('help.php', $MODEL);
	}
	
	
	
	
	
	
	function newsItem()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		
		$id=intval($CORE->params[0]);
		
		$item = News::get($id);
		$CONTENT->setTitle($item->attrs['name']);	
		
		#	крошки
		$crumbs = array();
		$crumbs[] = '<a href="'.Route::getByName(Route::MAIN)->url().'">Главная</a>';
		$crumbs[] = '<a href="'.Route::getByName(Route::NOVOSTI_KARTOCHKA)->url().'">Новости</a>';
		if($item->attrs)
			$crumbs[] = $item->attrs['name'];
		
		$MODEL['crumbs'] = $crumbs;
		$MODEL['item'] = $item;
		$MODEL['settings'] = $settings;
			
		Core::renderView('news/newsItem.php', $MODEL);
	}
	
	
	
	
}




?>