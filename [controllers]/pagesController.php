<?php 
class PagesController extends MainController{
	
	function routifyAction()
	{
		global $CORE;
		$section = $CORE->params[0];
		$p = $CORE->params[1];
		//vd($CORE->params);
	
		if($id = intval($CORE->params[0]))
			$action = 'pagesItem';
		else
			$action = 'pagesList';

		if($action)
			$CORE->action = $action;
	}
	
	
	function pagesList()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		
		$_GLOBALS['TITLE'] = Slonne::getTitle('Разделы');
		 
		$pages = Page::getChildren(113);
		$MODEL['pages'] = $pages;
		
		Core::renderView('pages/pagesList.php', $MODEL);
	}
	
	
	
	
	function pagesItem($id)
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		
		$id = intval($CORE->params[0]);
		
		$p = Page::get($id);
		//vd($p);
		//Page::a();
		$_GLOBALS['TITLE'] = Slonne::getTitle($p->attrs['name']);
		$MODEL['p'] = $p;
		
		#	крошки
		$tree = Page::getTree($p->id);
		foreach($tree as $key=>$page)
			$treeIds[] = $page->id;
	
		$crumbs = array();
		$crumbs[] = '<a href="/'.LANG.'/">'.$_CONST['ГЛАВНАЯ'].'</a>';
		if(in_array(1, $treeIds))	#	если открыт раздел ГЛАВНОГО МЕНЮ, а не левый
		{
			$i = 0;
			foreach($tree as $key=>$page)
			{
				$i++;
				if($key == 1)	#	главное меню
					continue;
	
				if($i < count($tree))
				{
					$crumbs[] = '<a href="#'.$page->attrs['name'].'">'.$page->attrs['name'].'</a>';
				}
				else 	
					$crumbs[] = ''.$page->attrs['name'].'';
			}
		}
		else	#	открыт левый
		{
			$crumbs[] = ''.$p->attrs['name'].'';
		}
		
		$MODEL['crumbs'] = $crumbs;

		Core::renderView('pages/page.php', $MODEL);
	}
	
	
	
	
}


?>