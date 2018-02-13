<?php
class NewsController extends MainController{
	
	
	static $settings = array(
		'YEARS_NAVIGATION_IN_NEWS_LIST' => false,
	);
	
	
	
	function routifyAction()
	{
		global $CORE;
		$section = $CORE->params[0];
		$p = $CORE->params[1];
		//vd($CORE->params);
		
		if($id = intval($CORE->params[0]))
			$action = 'newsItem';
		else
			$action = 'newsList';
		
		if($action)
			$CORE->action = $action;
	}
	
	
	function newsList()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
				
		$CONTENT->setTitle('Новости');	
		
		$year = intval($CORE->specialParams['year']);
		$elPP = 5;
		$page = intval($CORE->specialParams['p']) ? intval($CORE->specialParams['p'])-1 : 0;
		
		$limit=" LIMIT ".$page*$elPP.", ".$elPP."";
		$clause = ($year? " AND YEAR(dt) = '".$year."'" : "") ;
		
		$news = News::getChildren(0, "", ($year? " AND YEAR(dt) = '".$year."'" : "") );
		$totalElements = count($news); 
		$news = News::getChildren(0, $limit, $clause);
	
		//vd($news);
		$MODEL['news'] = $news;
		
		#	работа с годами
		$years [] = $_CONST['ВСЕ']; 
		for($i = date('Y'); $i> (date('Y')-3); $i-- )
		{
			$years[]=$i;
		}
		
		$MODEL['years'] = $years;
		$MODEL['chosenYear'] = $year;
		$MODEL['elPP'] = $elPP;
		$MODEL['page'] = $page;
		$MODEL['totalElements'] = $totalElements;
		$MODEL['settings'] = self::$settings;
		//$MODEL['crumbs'] = $crumbs; 
		
		$crumbs = array();
		$crumbs[] = '<a href="'.Route::getByName(Route::MAIN)->url().'">Главная</a>';
		$crumbs[] = 'Новости';
		$MODEL['crumbs'] = $crumbs;
		
		Core::renderView('news/newsList.php', $MODEL);
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