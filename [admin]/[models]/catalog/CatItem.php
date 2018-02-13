<?php 
class CatItem{
	
	const TBL = 'cat__items';
	
	var   $id
		, $pid
		, $brandId
		, $artnumId
		, $name
		, $descr
		, $active
		, $archived
		, $dateCreated
		, $catTypeCode
		, $type
		, $idx
		, $userId
		
		, $propValues
		, $propValuesObjs
		, $cat
		, $user
		, $media
		;
		
	const URL_SIGN = 'item';
	const TREE_ELEMENTS_PER_PAGE = 10;

	const TYPE_SELL = 'sell';
	const TYPE_BUY = 'buy';
	
	# 	типы объявлений : КУПЛЮ / ПРОДАМ / что-то ещё..
	static $types = array(
		self::TYPE_BUY=>'Куплю',
		self::TYPE_SELL=>'Продам',
	) ;
	
	
		
	function getList($params)
	{
		//vd($params);
		$ret = array();
		
		$lang = $params['lang'] ? $params['lang'] : LANG;
		$tbl=CatItem::TBL;		
		
		$sql="SELECT * FROM `".$tbl."` WHERE 1 ".self::getListInnerSql($params);
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		while($next=mysql_fetch_array($qr, MYSQL_ASSOC))
		{
			$e = self::init($next);
			$ret[] = $e; 
		}
		
		return $ret;
	}
	
	
	
	
	function getCount($params)
	{
		$tbl=self::TBL;			
		
		$sql="SELECT COUNT(*) FROM `".$tbl."` WHERE 1 ".self::getListInnerSql($params);
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		$next = mysql_fetch_array($qr);
		//vd($next);
		
		return $next[0];
	}
	
	
	
	
	
	
	function getChildElementsCount($catType, $id)
	{
		$sql="SELECT COUNT(*) FROM `".self::TBL."` WHERE 1 AND catTypeCode='".strPrepare($catType)."' AND pid=".intval($id)."";
		$qr=DB::query($sql);
		echo mysql_error();
		$next = mysql_fetch_array($qr);
		//vd($next);
		
		return $next[0];
	}
	
	
	
	
	
	function getListInnerSql($params)
	{
		$params['lang'] = $params['lang'] ? $params['lang'] : LANG;
		$params['order'] = $params['order'] ? $params['order'] : "  idx";
		
		$sql.=" 
		".(isset($params['pid']) ? "AND (pid=".strPrepare($params['pid']).")" : "")." 
		".(isset($params['userId']) ? "AND (userId=".intval($params['userId']).")" : "")." 
				
		".(isset($params['activeOnly']) ? ($params['activeOnly'] ? "AND active='1'" : "" ) : "")."

		".(is_numeric($params['active']) ? " AND active=".$params['active']."" : "")."
		".(is_numeric($params['archived']) ? " AND archived=".$params['archived']."" : "")."
		
		".(isset($params['catType']) ? "AND (catTypeCode='".strPrepare($params['catType'])."')" : "")." 
		".(($params['type']) ? "AND (type='".strPrepare($params['type'])."')" : "")."  
		".(($params['additionalClause']))." 
		".($params['order'] ? " ORDER BY ".$params['order'] : "")." 
		".strPrepare($params['limit'])."
		";
		
		return $sql;
	}
	
	
	
	
	function init($arr)
	{
		$m = new self();
		
		$m->id = $arr['id'];
		$m->pid = $arr['pid'];
		$m->brandId = $arr['brandId'];
		$m->artnumId = $arr['artnumId'];
		$m->name = $arr['name'];
		$m->descr = $arr['descr'];
		$m->dateCreated = $arr['dateCreated'];
		$m->catTypeCode = $arr['catTypeCode'];
		$m->type = $arr['type'];
		$m->idx = $arr['idx'];
		$m->active = $arr['active'];
		$m->archived = $arr['archived'];
		$m->userId = $arr['userId'];
		
        return $m;
	}
	
	
	

	
	
	function get($id)
	{
		if($id =intval($id))
		{
			$sql = "SELECT * FROM `".self::TBL."` WHERE id = ".$id;
			$qr=DB::query($sql);
			echo mysql_error();
			if($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				return self::init($next);
		}
	}
	
	
	
	
	
	function insert()
	{
		$this->idx = 9999;
		$sql = "
		INSERT INTO `".self::TBL."` 
		SET 
		dateCreated=NOW(),
		".$this->alterSql()."
		
		";
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		return mysql_insert_id();
	}
	
	
	
	function update()
	{
		$sql = "
		UPDATE `".self::TBL."` 
		SET 
		".$this->alterSql()."
		WHERE id=".intval($this->id)."
		";
		vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
	}
	
	
	
	
	function alterSql()
	{
		$str.="
		`active`='".($this->active ? 1 : 0)."'
		, `archived`='".($this->archived ? 1 : 0)."'
		, idx=".intval($this->idx)."
		, `pid`='".intval($this->pid)."'
		, `brandId`='".intval($this->brandId)."'
		, `artnumId`='".intval($this->artnumId)."'
		, `name`='".strPrepare($this->name)."'
		, `descr`='".strPrepare($this->descr)."'
		, `catTypeCode`='".strPrepare($this->catTypeCode)."'
		, `type`='".strPrepare($this->type)."'
		, `userId`='".intval($this->userId)."'
		";
		
		return $str;
	}
	
	
	
	
	function delete($id)
	{
		if($id = intval($id))
		{
			$sql = "
			DELETE FROM `".self::TBL."` WHERE id=".$id;
			DB::query($sql);
			echo mysql_error(); 
		}
	}
	
	
	
	
	
	
	
	function setIdx($id, $val)
	{
		if($id=intval($id))
		{
			$sql = "UPDATE `".self::TBL."` SET idx='".intval($val)."' WHERE id=".$id;
			DB::query($sql);
			echo mysql_error();
		}
	}
	
	
	
	function drawTreeSelect($catType, $pid/*чьих детей отображать*/, $self_id, $idToBeSelected, $level=0 )
	{
		global $_CONFIG;
		
		$lang = $_CONFIG['LANGS'][$lang] ? $lang : LANG;
		$pid=intval($pid);
		$level=intval($level);
		//$type = $essence->jointFields ? Entity2::TYPE_ELEMENTS : Entity2::TYPE_BLOCKS;
		
		$cat = Category::get($pid);	
		if($cat->id == $self_id && $self_id)
			return $ret;
		
		if($cat->id )
		{
			$ret.='
				<option '.($idToBeSelected==$cat->id?' selected="selected"  ':'').' value="'.$cat->id.'">';
				for($i=1; $i<$level; $i++)
				{
					$ret.='------';
				}
				$ret.='| ('.$cat->id.') '.$cat->name;
				$ret.='
				</option>';
		}
		
		#	достаём детей
		$params = array(
						'catType'=>$catType,
						'pid'=>$pid,
						'limit'=>'',
						'type'=>$type, 
						'order'=>'', 
						'lang'=>$lang, 
						'additionalClauses'=>'and 1',
						'activeOnly'=>false,
					);
		$children = Category::getList($params);
		foreach($children as $key=>$child)
		{
			$ret.=self::drawTreeSelect($catType, $child->id, $self_id,  $idToBeSelected,  ($level+1));
		} 
	
		return $ret;
	}
	
	
	
	
	function validate()
	{
		$problems = array();
		//vd($this->propValues);
		if(!$this->name = strPrepare($this->name))
			$problems[] = Slonne::setError("name", 'Введите <b>название</b>!');
		
		
		#	валидация свойств
		foreach($this->cat->class->props as $key=>$prop)
		{
			if($tmp = $prop->validateValue($this->propValues[$prop->code]))
				$problems[] = $tmp;
		}
		
		return $problems;
	}
	
	
	
	
	
	function initValues()
	{
		#	достаём все значения итема! все, включая толпу мультиселектов
		$propValues = CatPropValue::getListByItemId($this->id);
		
		#	В ЭТОМ МАССИВЕ БУДУТ ЛЕЖАТ ВСЕ ЗНАЧЕНИЯ, КРОМЕ МУЛЬТИСЕЛЕКТОВ!
		$valuesArr = null;
		#	расладываем значения в ассоц массив, где ключ - код (чтобы взять значения всех, кроме МУЛЬТИСЕЛЕКТОВ)
		foreach($propValues as $key=>$prop)
		{
			$valuesArr[$prop->propCode] = $prop->value;
			$valuesObjsArr[$prop->propCode] = $prop;
		}
		
		foreach($this->cat->class->props as $key=>$prop)
		{
			$value = null;
			$value = $propValues[$prop->id]->value;
			
			if($prop->type=='select' && $prop->multiple)	#	раскладываем мультиселекты
			{
				foreach($propValues as $key=>$propValue)
				{
					if($prop->code == $propValue->propCode)
					{
						$this->propValues[$prop->code][] = $propValue->value;
						$this->propValuesObjs[$prop->code][] = $propValue;
					}
				}
			}
			else	#	все остальные
			{
				$this->propValues[$prop->code] = $valuesArr[$prop->code];
				$this->propValuesObjs[$prop->code] = $valuesObjsArr[$prop->code];
			}
			
		}

	}

	
	
	
	function initCat()
	{
		$this->cat = Category::get($this->pid);
	}
	
	
	
	
	
	function url()
	{
		return '/'.LANG.'/'.Catalog::URL_SIGN.'/' . self::URL_SIGN .'/'.$this->urlPiece() ;
	}
	
	
	function urlPiece()
	{
		return ''.$this->id.'_'.str2url($this->name);
	}
	
	
	
	
	function setValuesFromArray($arr)
	{
		if(!$this->cat)
			$this->initCat();
		if(!$this->cat->class)
			$this->cat->initClass();
		if(!$this->cat->class->props)
			$this->cat->class->initProps($activeOnly=true);
		
		$this->active = $arr['active'] ? 1 : 0;
		$this->pid = $_REQUEST['pid'] ? $_REQUEST['pid'] : $_REQUEST['catId'];
		$this->name = $arr['name'];
		$this->descr = $arr['descr'];
		$this->type = $arr['type'];
		$this->catTypeCode = $this->cat->catTypeCode;
		
		foreach($this->cat->class->props as $key=>$prop)
		{
			$this->propValues[$prop->code] = $arr[$prop->code];
		}
	}
	
	
	
	
	
	
	function tryToInsertOrUpdateItemAndProps()
	{
		DB::transactionStart();
		
		# 	сперва сохраняем просто модель, освойства - после
		if(!$this->id)
			$this->id = $this->insert();
		else
			$this->update();
		
		if(!$e = mysql_error())
		{
			#	работа с пропсами
			foreach($this->cat->class->props as $propId=>$prop)
			{
				$value = $this->propValues[$prop->code];
		
				#	обработка МУЛЬТИСЕЛЕКТА
				if($prop->type=='select' && $prop->multiple)
				{
					//vd($value);
					CatPropValue::saveMultipleSelectOptionsByItemAndProp($this, $prop, $value, $lang);
				}
				else 	#	ВСЕ ОСТАЛЬНЫЕ
				{
					$newProp = false;
					$propValue = CatPropValue::getByItemIdAndPropId($this->id, $prop->id);
						
					if(!$propValue)
					{
						$propValue = new CatPropValue();
						$newProp = true;
					}
						
					$propValue->propId = $prop->id;
					$propValue->propCode = $prop->code;
					$propValue->itemId = $this->id;
					$propValue->catTypeCode = $this->cat->catTypeCode;
					$propValue->lang = $lang;
					$propValue->value = $value;
					//vd($prop);
					if($newProp)
						$propValue->insert();
					else
						$propValue->update();
				}
					
			}
				
			if(!$e = mysql_error())
				DB::commit();
			else
			{
				DB::rollback();
				$error = $e;
			}
		}
		else
		{
			DB::rollback();
			$error = $e;
		}
		
		return $error;
	}
	
	
	
	
	
	function setActive($value)
	{
		$sql = "UPDATE `".self::TBL."` SET active='".($value ? 1 : 0)."' WHERE id=".intval($this->id);
		//vd($sql);
		DB::query($sql);
		echo mysql_error();
		
		$this->active = $value; 
	}
	
	
	
	
	function setArchived($value)
	{
		$sql = "UPDATE `".self::TBL."` SET archived='".($value ? 1 : 0)."' WHERE id=".intval($this->id);
		//vd($sql);
		DB::query($sql);
		echo mysql_error();
	
		$this->archived = $value;
	}
	
	
	
	
	function initMedia($active)
	{
		$this->media = AdvMedia::getList($this->id, $active);
	}
	
	
	
}
?>