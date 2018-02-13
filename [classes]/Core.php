<?php
class Core{
	public $controller;
	public $action;
	public $params;
	
	public $layout;
	public $view;
	//public $startupFile;
	
	public $lang;
	public $isAdminka;
	
	public $controllerPath;
	public $layoutPath;
	
	public $url;
	public $route;
	//public $urlParts;
	public $specialParams;
	public $globalTimer;
	
	
	const INDEX_CONTROLLER = 'index';
	const ERROR_CONTROLLER = 'error';
	
	const SUBPARAMS_SEPARATOR = '_';
	
	
	
	# 	ключ для защиты крона от левого запуска
	const CRON_KEY_1 = 'Nv928Qj498';
	const CRON_VALUE_1 = 'p^dZ39JV_3dD-163jUifn8F0f';
	
	
	function __construct($url)
	{	
		$this->parseUrl($url);
	}
	
	
	function parseUrl($url)
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		
		$this->url = $url;
		
		$this->urlParts = Url::parse($this->url);
		
		$this->isAdminka = false;

		if($_CONFIG['langs'][$this->urlParts[0]])
		{
			$this->lang = $_CONFIG['langs'][$this->urlParts[0]];
			$_SESSION['lang'] = $this->lang->code;	#	для того, чтобы в аджаксовыхзапросах помнить язык
			$controllerIndexInUrl = 1;
		}
		elseif($this->urlParts[0] == ADMIN_URL_SIGN)	#	админка
		{
			$this->isAdminka = true;
			//echo "!";
			$this->lang = $_CONFIG['default_lang'];
			$controllerIndexInUrl = 1;
		}
		else	#	Безъязыковый запрос (скорее всего AJAX)
		{
			//$this->lang = Lang::code($_SESSION['lang']);
			$this->lang =  $this->lang ? $this->lang : $_CONFIG['default_lang'];
			$controllerIndexInUrl = 0;
		}
		//vd($this->lang);
		define('LANG', $this->lang);
		
		# 	контроллер
		$this->controller = $this->urlParts[$controllerIndexInUrl];
		if($this->controller)
		{
			$this->setController($this->controller);
			if(!file_exists($this->controllerPath))
				$this->setController(self::ERROR_CONTROLLER);
		}
		else
			$this->setController(self::INDEX_CONTROLLER);
		
		# 	экшн
		$this->action = $this->urlParts[$controllerIndexInUrl+1];
		/*if(!$this->action)
			$this->action='index';*/
		//vd($this);
		
		#	наполняем $_PARAMS
		$urlSectionsCount=count($this->urlParts);
		for ($i = ($controllerIndexInUrl+1); $i < $urlSectionsCount; $i++)
			$this->params[$i-($controllerIndexInUrl+1)] = $this->urlParts[$i];
		
		# 	спец-парамс
		$this->getSpecialParams();
		
		
	}
	
	
	
	
	function setController($controller)
	{
		$this->controller = $controller.'Controller';
		$this->controllerPath = ($this->isAdminka && $this->controller!=self::ERROR_CONTROLLER.'Controller' ? ADMIN_DIR.'/' : '').CONTROLLERS_DIR.'/'.$this->controller.'.php';
	}
	
	
	function setLayout($layout)
	{
		$this->layout = $layout;
		if($this->layout)
			$this->layoutPath = ($this->isAdminka ? ADMIN_DIR.'/' : '').VIEWS_DIR.'/'.LAYOUTS_DIR.'/'.$layout.'';
		else
			$this->layoutPath = null;
	}
	
	
	
	
	function executeAction()
	{
		if($this->controller)
		{
			if(class_exists($this->controller))
			{
				if($this->action)
				{
					if(method_exists($this->controller, $this->action))
					{
						call_user_func(''.$this->controller.'::'.$this->action.'');
						//echo ''.$this->controller.'::'.$this->action.'';
					}
					else
						echo 'uNDeFiNeD aCTioN <b>"'.$this->action.'"</b> iN <b>"'.$this->controller.'"</b>';
				}
				else
					eval($this->controller.'::index();');
			}
			else
				echo 'uNDeFiNeD CoNTRoLLeR <b>"'.$this->controller.'"</b>';
		}
		else
			echo 'No CoNTRoLLeR iNiTiaLiZeD';
	}
	
	
	
	
	
	
	function renderLayout()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		//vd($CORE);
		if($this->layout)
		{	
			if(file_exists($this->layoutPath))
				require_once($this->layoutPath);
			else
			{
				echo 'LaYouT <b>"'.$this->layout.'"</b> NoT FouND!';
				echo $CONTENT->content;
			}
		}
		else 
			echo $CONTENT->content;
	}
	
	
	
	
	
	#	перекидывает модель во вью
	function renderView($viewName, &$model, $buffer = false, $ignoreIsAdminka=false)
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		$CORE->view = $viewName;
		self::renderPartial($viewName, $model, $buffer, $ignoreIsAdminka);
	}
	
	function renderPartial($viewName, &$model, $buffer = false, $ignoreIsAdminka=false)
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		if($buffer)
			ob_start();
	
		$viewPath = ROOT.'/'.( $CORE->isAdminka && !$ignoreIsAdminka ? ADMIN_DIR.'/' : '' ).VIEWS_DIR.'/'.$viewName;
		//vd($viewPath);
		$view = realpath($viewPath);
		//vd(ROOT.'/'.VIEWS_DIR.'/'.$viewName);
		if(file_exists($view))
		{
			foreach($GLOBALS as $key=>$val){$$key = $val;}
			${'MODEL'} = $model;
			require($view);
			//$model=NULL;
		}
		else
			echo 'eRRoR: VieW <b>"'.$viewName.'"</b> NoT FouND. <span style="font-size: 13px; ">[ '.ROOT.'/'.( $CORE->isAdminka && !$ignoreIsAdminka ? ADMIN_DIR.'/' : '' ).VIEWS_DIR.'/'.$viewName.' ]</span>';

		if($buffer)
			return ob_get_clean();
	}
	
	
	
	
	
	
	#	возвращает массив параметров, у кторых задан ключ (типа "year_2013")
	function getSpecialParams()
	{	
		$params = array();
		foreach($this->params as $key=>$val)
		{
			list($param, $value) = explode(PARAMS_INNER_SEPARATOR, $val);
			if($param && $value)
				$params[$param] = $value;
		}
	
		$this->specialParams = $params;
	}
	
	
	
}