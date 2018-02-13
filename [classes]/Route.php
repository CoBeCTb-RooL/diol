<?php
class Route{
	public $name;
	public $pattern;
	public $replaceWith;
	
	public $urlSource;
	public $urlTransformed;
	
	static $routes;
	
	
	const MAIN 								= 'Главная';
	const SPISOK_OBYAVLENIY_KATEGORII 		= 'Список объявлений категории';
	const SPISOK_KATEGORIY 					= 'Список категорий';
	const KARTOCHKA_OBYAVLENIYA 			= 'Карточка объявления';
	
	const NOVOSTI_KARTOCHKA 				= 'Новости карточка';
	const CABINET 							= 'Личный кабинет';
	const CABINET_PROFILE_EDIT 				= 'Редактирование профиля';
	const CABINET_ADV_EDIT 					= 'Редактирование объявления';
	const CABINET_PROFILE_CHANGE_PASSWORD 	= 'Смена пароля';
	const CABINET_MY_ADVS 					= 'Мои объявления';
	const CABINET_PASSWORD_RESET_CLAIM 		= 'Восстановление пароля';
	
	const ARTNUMS_LIST_BY_BRAND 			= 'Список артНомеров по бренду'; 
	const SUGGESTIONS 			 			= 'Вопросы / предложения';
	const NEWS 					 			= 'Новости';
	const HELP 					 			= 'Помощь';

	
	function __construct($pattern, $replaceWith, $name)
	{
		$this->pattern = $pattern;
		$this->replaceWith = $replaceWith;
		$this->name = $name;
	}
	
	
	
	
	function getSuitableRoute($url)
	{
		global $_CONFIG;
		
		# 	если передан язык
		$tmp = Url::parse($url);
		if($_CONFIG['langs'][$tmp[0]])
			unset($tmp[0]);
		$url = '/'.join('/', $tmp);
		
		
		$routes = Route::$routes;
		
		// 	сперва пробегаемся по всем роутам, проверка на полное совпадение
		foreach($routes as $r)
		{
			/*vd($url);
			vd($r->pattern);
			echo '<hr />';*/
			if($url == $r->pattern)
			{
				$r->urlSource = $url ;
				$replaceWith = $r->replaceWith;
				$r->urlTransformed = $r->replaceWith;
				return $r;
			}
		}
		
		
		foreach($routes as $r)
		{
			$pattern = str_replace('{SMTH}', '', $r->pattern);

			// 	если встретился int
			if( ($intPosition = strpos($pattern, '{int}')) >0)
			{
				$patternPieceBeforeInt = mb_substr($pattern, 0, $intPosition);
				$intPart = mb_substr($pattern, $intPosition);
				if(strpos($url, $patternPieceBeforeInt) === 0)
				{
					$urlIntPart = str_replace($patternPieceBeforeInt, '', $url );
					
					if(intval($urlIntPart))
					{
						$r->urlSource = $url ;
						$replaceWith = str_replace('{int}', $urlIntPart, $r->replaceWith);
						$r->urlTransformed = str_replace('{int}', $urlIntPart, $r->replaceWith);
						return $r;
					}
				}
			}
			
			
			if(strpos($url, $pattern) === 0)
			{
				$r->urlSource = $url ;
				if($r->replaceWith)
				{
					$replaceWith = str_replace('{SMTH}', '', $r->replaceWith);
					$r->urlTransformed = str_replace($pattern, $replaceWith, $url);
				}
				else
					$r->urlTransformed = $url;
				
				return $r;
			}
		}
	}
	
	
	
	function getByName($name)
	{//vd($name);
		foreach(Route::$routes as $key=>$r)
			if($r->name == $name)
				return $r;
	}
	
	
	
	
	function url($urlPiece)
	{
		global $CORE, $_CONFIG;
		//vd($CORE);
		//vd($urlPiece);
		$ret = ($CORE->lang->code != $_CONFIG['default_lang']->code ? '/'.$CORE->lang->code:'').$this->pattern;
		$ret = str_replace('{SMTH}', '', str_replace('{int}', '', $ret));
		//vd(substr($ret, strlen($ret)-1));
		$ret .=(substr($ret, strlen($ret)-1) !='/' ? '/' : '') . $urlPiece;
		
		$ret = str_replace('//', '/', $ret);
		
		return $ret;
	}
	
	
	
}