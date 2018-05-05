<?php
class IndexController extends MainController{
	
	function routifyAction()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);

		$action = 'index';
		if($_SERVER['REQUEST_URI'] == '/' && $_CONFIG['ZAGLUSHKA_INDEX'])
		{
			header('Location: /admin');
			die;
			$action = 'zaglushka';
		}
		
		$CORE->action = $action;
	}
	
	
	
	function index()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		
		//$CONTENT->setTitle('ПаНКи');
		$CONTENT->title = 'OSTATKI.KZ :: Главная';
		//vd($CONTENT);
		
		$MODEL['dealType'] = DealType::code($_REQUEST['type']);
		$MODEL['cats'] = AdvCat::getFullCatsTree($status=Status::code(Status::ACTIVE));
		
		$MODEL['dealTypes'] = DealType::$items;
		
		$advsQuanList = AdvQuan::getListByCity($_GLOBALS['city']->id);
		//vd($advsQuanList);
		
		foreach($MODEL['cats'] as $cat)
		{
			$cat->advsCount = $advsQuanList[$cat->id]->quan;
			foreach($cat->subCats as $subCat)
				$subCat->advsCount = $advsQuanList[$subCat->id]->quan;
		}
		
		$MODEL['totalCount'] = AdvItem::getCount($params=array('statuses' => array(Status::code(Status::ACTIVE))));
		$MODEL['totalCountCurrentCity'] = AdvItem::getCount($params=array('statuses' => array(Status::code(Status::ACTIVE)), 'cityId'=>$_GLOBALS['city']->id, ));
		
		$MODEL['lastAdvs'] = AdvItem::getList($params=array('cityId'=>$_GLOBALS['city']->id, 'statuses'=>array(Status::code(Status::ACTIVE)), 'orderBy'=>'id DESC', 'limit'=>' LIMIT 0, 4 '));
		//vd($MODEL['lastAdvs']);
		
		# 	инициализируем медиа
		foreach($MODEL['lastAdvs'] as $item)
			$ids[] = $item->id;
		$medias = AdvMedia::getByPidsList($ids);
		foreach($MODEL['lastAdvs'] as $key=>$item)
			$MODEL['lastAdvs'][$key]->media = $medias[$item->id];
		

		Core::renderView('index/index.php', $MODEL);
	}
	
	
	
	function zaglushka()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		//Startup::execute(Startup::FRONTEND);
		
		$CORE->setLayout('zaglushka.php');
		
	}
	
}

?>