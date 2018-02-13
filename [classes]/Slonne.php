<?php
class Slonne
{
	static $paramsInnerSeparator = '_';
	
	
	
	function loadDir($path)
	{
		//vd($path);
		$dirsStopList = array(
							'Stock', 
							'VERNUT_SUDA'
						);
		
		$models = scandir($path);
		//vd($models);
		
		foreach($models as $key=>$val)
		{
			if(!is_dir($path.'/'.$val))
				require_once($path.'/'.$val);
			elseif($val!='.' && $val!='..' && !in_array($val, $dirsStopList))
				self::loadDir($path.'/'.$val);
		}
		
	}
	
	
	
	/*
	#	перекидывает модель во вью
	function view($viewName, &$model, $buffer = false, $ignoreIsAdminka=false)
	{
		global $_GLOBALS;
		if($buffer)
			ob_start();

		$view = realpath(ROOT.'/'.(IS_ADMINKA && !$ignoreIsAdminka ? ADMIN_DIR.'/' : '').VIEWS_DIR.'/'.$viewName);
		//vd(ROOT.'/'.(IS_ADMINKA ? ADMIN_DIR.'/' : '').VIEWS_DIR.'/'.$viewName);
		if(file_exists($view))
		{
			foreach($GLOBALS as $key=>$val){$$key = $val;}
			${'MODEL'} = $model;
			require($view);
			//$model=NULL;
		}
		else
			echo 'eRRoR: VieW <b>"'.$viewName.'"</b> NoT FouND.';
		
		if($buffer)
			return ob_get_clean();
	}*/
	
	
	
/*
	function layoutRender($layout)
	{
		global $_GLOBALS, $_CONFIG;
		
		//vd($layout);
		if($layout)
		{
			$layoutPath = (IS_ADMINKA ? ADMIN_DIR.'/' : '').VIEWS_DIR.'/'.LAYOUTS_DIR.'/'.$layout.'.php';
			
			if(file_exists($layoutPath))
				require_once($layoutPath);
			else
			{
				echo 'LaYouT <b>"'.$layout.'"</b> NoT FouND!';
				echo $_GLOBALS['CONTENT'];
			}
		}
			
		else echo $_GLOBALS['CONTENT'];
	}*/
	
	
	
	
	
	
	function redirect($url)
	{
		$a = '/'.(IS_ADMINKA ? ADMIN_URL_SIGN.'/' : '').$url. htmlspecialchars($_SERVER['QUERY_STRING']);
		header("Location: ".$a."");
	}
	
	
	
	
	
	#	тайтл для сайта
	function getTitle($str)
	{
		global $_GLOBALS, $_CONFIG;
		//vd($_CONFIG);
		if($str = trim($str))
			return $str.$_CONFIG['SETTINGS']['title_separator'].$_CONFIG['SETTINGS']['title_postfix'];
		else return $_CONFIG['SETTINGS']['title_postfix'];
	}
	
	
	
	
	
	
	/*
	#	возвращает массив параметров, у кторых задан ключ (типа "year_2013")
	#	ключ - 'year', значение - '2014'
	function getParams()
	{
		global $_PARAMS;
		
		$params = array();
		foreach($_PARAMS as $key=>$val)
		{
			list($param, $value) = explode(PARAMS_INNER_SEPARATOR, $val);
			if($param)
			{
				$params[$param] = $value;
			}
		}
		
		return $params;
	}*/
	
	
	
	function setError($fieldCode, $err)
	{
		return array('field'=>$fieldCode, 'error'=>$err);
	}
	
	
	
	function fixFILES()
	{
		//vd($_FILES);
		$pics = NULL;
		foreach($_FILES as $fieldCode=>$val)
		{
			$pics[$fieldCode] = NULL;
			foreach($val['name'] as $num=>$pic)
			{
				if($_FILES[$fieldCode]['name'][0])
				{
					$tmp['name'] = $_FILES[$fieldCode]['name'][$num];
					$tmp['type'] = $_FILES[$fieldCode]['type'][$num];
					$tmp['tmp_name'] = $_FILES[$fieldCode]['tmp_name'][$num];
					$tmp['error'] = $_FILES[$fieldCode]['error'][$num];
					$tmp['size'] = $_FILES[$fieldCode]['size'][$num];
						
					$pics[$fieldCode][] = $tmp;
				}
			}
		}
	
		$_FILES = $pics;
	}
	
	
	
	function saveFile($file, $destDir, $newFileName)
	{
		$problem = null;
	
		if(!trim($newFileName))
			$newFileName = Funx::generateName();
	
		if($file )
		{
			$dot=strrpos($file['name'], '.');
			$name=(substr($file['name'], 0, $dot));
			$ext=strtolower(substr($file['name'],  $dot+1));
				
			$tmpFile = $file["tmp_name"];
			if(is_uploaded_file($tmpFile))
			{
				//$allowedExts = array();
				mkdir($destDir);

				$destFile=$destDir.'/'.$newFileName/*.'.'.$ext*/;

				if( move_uploaded_file($tmpFile, $destFile))
				{
					#	всё ок, проблем не возвращаем
				}
				else
					$problem = Slonne::setError('', 'Не удалось загрузить файл <b>'.$file['name'].'</b>');
			}
			else
				$problem = Slonne::setError('', 'Файл <b>'.$file['name'].'</b> не загружен..');
		}
		else
			$problem = Slonne::setError('', 'Не удалось загрузить файл <b>'.$file['name'].'</b>');
				
		$result['problem'] = $problem;
		$result['newFileName'] = $newFileName.'.'.$ext;
			
		return $result;
	}
	
	
	
	function cast($className, &$object)
	{
		//vd($object);
		if (!class_exists($className))
			throw new InvalidArgumentException(sprintf('Inexistant class %s.', $className));
	
		$new = new $className();

		foreach($object as $property => &$value)
		{
			$new->$property = &$value;
			unset($object->$property);
		}
		unset($value);
		$object = (unset) $object;
		return $new;
	}
	
	
	
} 
?>