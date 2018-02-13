<?php 
class Entity2{
	
	const TYPE_ELEMENTS = 'elements';
	const TYPE_BLOCKS = 'blocks';
	
	const LIST_ELEMENTS_PER_PAGE = 15; 
	const TREE_ELEMENTS_PER_PAGE = 10; 
	
	const IDX_STEP = 10;
	

	var   $id
		, $pid
		, $active
		, $untouchable
		, $type
		, $lang
		, $essenceCode
		, $attrs
		, $essence
		, $idx
		
		, $childBlocksCount
		, $childElementsCount;
	
	

	
	#	адаптирует  класс Энтити в детей
	function adapt($className, $e)
	{
		//vd($className);
		$a = new $className;
		$props = get_object_vars($e);
		foreach ($props as $key => $value)
            if(property_exists($a, $key ))
                $a->{$key} = $e->{$key};
                
		return $a;
	}
	
	
	
	
	function get($essenceCode, $id, $type, $lang)
	{
		global $_CONFIG; 
		
		$lang = $lang ? $lang : LANG;
		
		if($id=intval($id) )
		{
			$tbl = Essence2::getTblName($essenceCode, $type, $lang);
			$sql="SELECT * FROM `".$tbl."` WHERE id=".$id;
			$qr=mysql_query($sql);
			echo mysql_error();
			if(mysql_num_rows($qr))
			{
				$next=mysql_fetch_array($qr, MYSQL_ASSOC);
				return  self::init($essenceCode, $next, $type, $lang);
				
			}
		}
	}
	
	
	
	
	
	
	function getList($params)
	{
		global $_CONFIG;
		
		$ret = array();
		//vd($params['essenceCode']);
		//vd($params['type']);
		$lang = $params['lang'] ? $params['lang'] : $_CONFIG['default_lang']->code;
		//vd($lang);
		$tbl=Essence2::getTblName($params['essenceCode'], $params['type'], $lang);		

		$sql="SELECT * FROM `".$tbl."` WHERE 1 ".self::getEntitiesInnerSql($params);
//		vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		while($next=mysql_fetch_array($qr, MYSQL_ASSOC))
		{
			$e = Entity2::init($params['essenceCode'], $next, $params['type'], $lang);
			
			$e->initMedia();
			$ret[] = $e; 
		}
		
		return $ret;
	}
	
	
	
	
	function init($essenceCode, $attrs, $type, $lang)
	{
		$e = new Entity2();
		$e->id = $attrs['id'];
		$e->pid = $attrs['pid'];
		$e->active = $attrs['active'];
		$e->idx = $attrs['idx'];
		$e->untouchable = $attrs['untouchable'];
		
		unset($attrs['id']);
		unset($attrs['pid']);
		unset($attrs['idx']);
		unset($attrs['active']);
		unset($attrs['untouchable']);
		
		$e->attrs = $attrs;
		$e->lang = $lang;
		$e->type = $type;
		$e->essenceCode = $essenceCode;
		
		
		
		return $e; 
	}
	
	
	
	
	
	
	function getCount($params)
	{
		$tbl=Essence2::getTblName($params['essenceCode'], $params['type'], $params['lang']);		
		
		$sql="SELECT COUNT(*) FROM `".$tbl."` WHERE 1 ".self::getEntitiesInnerSql($params);
		$qr=DB::query($sql);
		echo mysql_error();
		$next = mysql_fetch_array($qr);
		//vd($next);
		
		return $next[0];
	}
	
	
	
	function getEntitiesInnerSql($params)
	{
		$params['lang'] = $params['lang'] ? $params['lang'] : LANG;
		$params['order'] = $params['order'] ? $params['order'] : "  idx";
		
		$sql.=" 
		".(isset($params['pid']) ? "AND (pid=".strPrepare($params['pid']).")" : "")." 
		".(isset($params['activeOnly']) ? ($params['activeOnly'] ? "AND active='1'" : "" ) : "")." 
		".($params['additionalClauses'])." 
		".($params['order'] ? " ORDER BY ".$params['order'] : "")." 
		".strPrepare($params['limit'])."
		";
		
		return $sql;
	}
	
	

	
	function getChildBlocksCount($essenceCode, $pid)
	{
		return Entity2::getChildrenCount($essenceCode, $pid, Entity2::TYPE_BLOCKS);
	}	
	function getChildElementsCount($essenceCode, $pid)
	{
		return Entity2::getChildrenCount($essenceCode, $pid, Entity2::TYPE_ELEMENTS);
	}
	function getChildrenCount($essenceCode, $pid, $type, $lang)
	{
		global $_CONFIG;
		
		if(!$lang) 
			$lang = $_CONFIG['default_lang']->code;
		
		if($type != Entity2::TYPE_BLOCKS && $type != Entity2::TYPE_ELEMENTS)
			return;
		if(!$essenceCode)	
			return;

		$tbl=Essence2::getTblName($essenceCode, $type, $lang);
			
		$sql="SELECT COUNT(*) FROM `".strPrepare($tbl)."` WHERE pid = ".intval($pid);
		$qr=mysql_query($sql);
		echo mysql_error();
		$next=mysql_fetch_array($qr);
		return $next[0];
	}
	
	
	
	
	function getNextIdx($essenceCode, $type, $lang, $pid)
	{
		$tbl=Essence2::getTblName($essenceCode, $type, $lang);
			
		$sql="SELECT MAX(idx) FROM `".strPrepare($tbl)."` WHERE pid = ".intval($pid);
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		$next = mysql_fetch_array($qr);
		//vd($next);
		return floor($next[0]/10)*10 + self::IDX_STEP;
	}
	
	
	
	
	
	function initMedia()
	{
		$media = Media::getList($this->essenceCode, $this->type, $this->id, $fieldCode=null);

		foreach($media as $key=>$m)
			$this->attrs[$m->fieldCode][] = $m;
	}
	
	
	
	function initEssence($essence)
	{
		if(get_class($essence) == 'Essence2' )
			$this->essence = $essence;
		else $this->essence = Essence2::get($this->essenceCode);
	}
	
	

	
	function validate()
	{
		$problems = NULL;
		
		#	валидация pid
		if(trim($this->pid) == '')
			$problems[] = Field2::setProblem('pid', '<b>Ошибка!</b> Не передан </b>pid</b> '.($this->pid ? '['.$this->pid.']' : '').'');
			
		#	валидация аттрибутов КРОМЕ КАРТИНОК
		foreach($this->essence->fields[$this->type] as $key=>$field)
		{
			if($field->required || in_array($field->type, array('pic', 'file')))
			{
				if($fieldValidationProblem = $field->validateValue($this->attrs[$field->code]))
					$problems[] = $fieldValidationProblem;
			}
		}
		vd($problems);
		return $problems;
	}
	
	
	
	
	
	
	
	
	
	
	
	function update()
	{
		echo 'update';
		
		$sql = "
		UPDATE `".Essence2::getTblName($this->essenceCode, $this->type, $this->lang)."`
		SET active = '".($this->active ? 1 : 0)."'
		, untouchable = '".($this->untouchable ? 1 : 0)."'
		, pid='".intval($this->pid)."'
		, idx = '".intval($this->idx)."'
		".$this->getAttrsSQL()."
		WHERE id=".intval($this->id)."";

		DB::query($sql);
		#	тут специально нет mysql_error(), потому как ошибка вытягивается в контроллере
	}
	
	
	
	
	function insert()
	{
		$sql = "
		INSERT INTO `".Essence2::getTblName($this->essenceCode, $this->type, $this->lang)."`
		SET active = '".($this->active ? 1 : 0)."'
		, pid='".intval($this->pid)."'
		, idx = '".intval($this->idx)."'
		".$this->getAttrsSQL()."
		";
		
		//vd($sql);
		//vd($this);
		DB::query($sql);
		return mysql_insert_id();
		#	тут специально нет mysql_error(), потому как ошибка вытягивается в контроллере
	}
	
	
	
	
	function getAttrsSQL()
	{
		foreach($this->essence->fields[$this->type] as $key=>$field)
		{
			switch($field->type)
			{
				case 'checkbox':
					$sql .= "\n, `".$field->code."`='".($this->attrs[$field->code] ? 1 : 0)."'";
					break;
					
				default: 
					$sql .= "\n, `".$field->code."`='".strPrepare($this->attrs[$field->code])."'";
					break;
			}
		}
		
		return $sql;
	}
	
	
	
	
	
	function setIdx($essenceCode, $type, $lang, $id, $idx)
	{
		$tbl = Essence2::getTblName($essenceCode, $type, $lang);
		
		$sql = "UPDATE `".$tbl."` SET idx='".intval($idx)."' WHERE id=".intval($id);
		//vd($sql);
		DB::query($sql);
		echo mysql_error();
	}
	
	
	
	function setUntouchable($value)
	{
		$tbl = Essence2::getTblName($this->essenceCode, $this->type, $this->lang);
		
		$sql = "UPDATE `".$tbl."` SET untouchable='".($value ? 1 : 0)."' WHERE id=".intval($this->id);
		//vd($sql);
		DB::query($sql);
		echo mysql_error();
	}
	
	
	
	
	
	
	function delete()
	{
		global $_CONFIG;
		
		$langs = $_CONFIG['langs'];
		$this->initEssence();
		$this->essence->initFields();
		
		//echo "!";
		foreach($langs as $lang=>$val)
		{
			#	достаём медиа
			$mediaFilesToDelete = array();
			$mediaFields = array();
			//vd($this->type);
			//vd($this->essence);
			foreach($this->essence->fields[$this->type] as $key=>$field)
			{
				if($field->type == 'pic')
					$mediaFields[] = $field->code;
			}
			//vd($mediaFields);
			foreach($mediaFields as $key=>$code)
			{
				$value = $this->attrs[$code];
				//vd($code);
				if(is_array($value))
					foreach($value as $key=>$val)	
						$val->delete();
				else	
					if($value)
					{
						$file=ROOT.'/'.UPLOAD_IMAGES_REL_DIR.$value;
						unlink($file);
					}
			}

			#	удаление файлов
			//echo "!";
			foreach($mediaFilesToDelete as $key=>$file)
			{
				vd($file);
				$file = ROOT.'/'.UPLOAD_IMAGES_REL_DIR.$file;
				unlink($file);
			}
			//echo "!";
			#	УДАЛЯЕМ САМ ОБЪЕКТ
			
			$tbl = Essence2::getTblName($this->essence->code, $this->type, $lang);
			$sql = "DELETE  FROM `".$tbl."` WHERE id=".intval($this->id);
			
			//vd($sql); 
			DB::query($sql);
			echo mysql_error();
			

			#	УДАЛЕНИЕ ДЕТЕЙ
			if(!$this->essence->linear)
			{
				$params = array(
								'essenceCode'=>$this->essence->code,
								'pid'=>$this->id,
								'limit'=>'',
								'type'=>Entity2::TYPE_ELEMENTS, 
								'order'=>'', 
								'lang'=>$lang, 
								'additionalClauses'=>'and 1',
								'activeOnly'=>false,
							);
				#	дочерние ЭЛЕМЕНТЫ
				if($this->essence->jointFields || $this->type == Entity2::TYPE_ELEMENTS)
				{
					$tmp = Entity2::getList($params);
					//vd($tmp);
					foreach($tmp as $key=>$entity)
						$entity->delete();
				}	

				#	дочерние БЛОКИ
				elseif($this->type == Entity2::TYPE_BLOCKS)
				{
					$params['type'] = Entity2::TYPE_ELEMENTS;
					$tmp = Entity2::getList($params);
					//vd($tmp);
					foreach($tmp as $key=>$entity)
						$entity->delete();
				}
			}
			
		}
		
		
	}
	
	
	
	
	function setActive($value)
	{
		//$this->initEssence();
			  
		$tbl = Essence2::getTblName($this->essenceCode, $this->type, $this->lang);
		$sql = "UPDATE `".$tbl."` SET active='".($value ? 1 : 0)."' WHERE id=".intval($this->id);
		//vd($sql);
		DB::query($sql);
		echo mysql_error();
	}
	
	
	
	
	
	function url($module, $lang)
	{
		$lang = $lang ? $lang: LANG;
		$module = $module ? $module : $this->essenceCode;
		return self::moduleUrl($module, $lang).'/'.$this->urlPiece();
	}
	
	
	function moduleUrl($module, $lang)
	{
		global $_CONFIG;
		$lang = $lang ? $lang: LANG;
		
		#	раскоментить то что ниже, чтоб для русского языка префикс не передавался
		#$lang = ($_SESSION['lang'] == $_CONFIG['DEFAULT_LANG'] ) ? '' : $_SESSION['lang'];
		
		return '/'. ($lang ? $lang.'/': '') . ($module ? $module : '');
	}
	
	
	function urlPiece()
	{
		return ''.$this->id.'_'.str2url($this->attrs['name']);
	}
	
	
	function a($className)
	{
		vd($className);
	}
	
	
}
?>