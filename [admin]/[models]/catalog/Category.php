<?php 
class Category{
	
	const TBL = 'cat__categories';
	
	var   $id
		, $pid
		, $name
		, $name_en
		, $active
		, $dateCreated
		, $typeId
		, $catTypeCode
		, $classId
		, $idx
		
		, $childBlocksCounts
		, $childElementsCount
		
		, $class
		;
		
	
	const URL_SIGN = 'cat';	
	const TREE_ELEMENTS_PER_PAGE = 10; 
	
	
	
	/*function getList($pid)
	{
		$sql = "SELECT * FROM `".mysql_real_escape_string(self::TBL)."` ".(isset($pid) ? "WHERE pid='".intval(pid)."'" : "")." ORDER BY idx";
		$qr=DB::query($sql);
		echo mysql_error();
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
		{
			$res[] = self::init($next);
		}
		
		return $res;
	}*/
		
	function getList($params, $optCatType)
	{
		//vd($params);

		$ret = array();
		if(gettype($params) != 'array' )
			$params = array('pid'=>$params, 'catType'=>$optCatType);

		$lang = $params['lang'] ? $params['lang'] : LANG;
		$tbl=Category::TBL;		
		
		$sql="SELECT * FROM `".$tbl."` WHERE 1 ".self::getListInnerSql($params);
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		while($next=mysql_fetch_array($qr, MYSQL_ASSOC))
		{
			$e = Category::init($next);
			$ret[] = $e; 
		}
		
		return $ret;
	}
	
	
	
	
	function getListTree($params)
	{
		$ret = self::getList($params);
		if($ret)
		{
			foreach($ret as $key=>$cat)
			{
				$params['pid'] = $cat->id;
				$cat->subs = self::getList($params); 
			}
		}
		
		return $ret; 
	}
	
	
	
	
	function getCount($params)
	{
		$tbl=Category::TBL;			
		
		$sql="SELECT COUNT(*) FROM `".$tbl."` WHERE 1 ".self::getListInnerSql($params);
		$qr=DB::query($sql);
		echo mysql_error();
		$next = mysql_fetch_array($qr);
		//vd($next);
		
		return $next[0];
	}
	
	
	
	
	
	
	function getChildBlocksCount($catType, $id)
	{
		$sql="SELECT COUNT(*) FROM `".Category::TBL."` WHERE 1 AND catTypeCode='".strPrepare($catType)."' AND pid=".intval($id)."";
		$qr=DB::query($sql);
		echo mysql_error();
		$next = mysql_fetch_array($qr);
		//vd($next);
		
		return $next[0];
	}
	
	
	function getChildElementsCount( $id)
	{
		//return 4;
		$sql="SELECT COUNT(*) FROM `".CatItem::TBL."` WHERE active > 0  AND pid=".intval($id)."";
		$qr=DB::query($sql);
		echo mysql_error();
		$next = mysql_fetch_array($qr);
		//vd($next);
		
		return $next[0];
	}
	
	
	
	
	
	function getListInnerSql($params)
	{
		//vd($params);
		$params['lang'] = $params['lang'] ? $params['lang'] : LANG;
		$params['order'] = $params['order'] ? $params['order'] : "  idx";
		if(!isset($params['activeOnly']) )
			$params['activeOnly'] = 1;
		
		$sql.=" 
		".(isset($params['pid']) ? "AND (pid=".intval($params['pid']).")" : "")." 
		".(isset($params['activeOnly']) ? ($params['activeOnly'] ? "AND active='1'" : "" ) : "")."
		".(isset($params['catType']) ? "AND (catTypeCode='".strPrepare($params['catType'])."')" : "")."  
		".($params['additionalClauses'])." 
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
		$m->name = $arr['name'];
		$m->name_en = $arr['name_en'];
		$m->dateCreated = $arr['dateCreated'];
		$m->catTypeCode = $arr['catTypeCode'];
		$m->classId= $arr['classId'];
		$m->typeId = $arr['typeId'];
		$m->idx = $arr['idx'];
		$m->active = $arr['active'];
		
        return $m;
	}
	
	
	

	
	
	function get($id, $activeOnly)
	{
		if($id =intval($id))
		{
			$sql = "SELECT * FROM `".self::TBL."` WHERE id = ".$id."  ".($activeOnly ? " AND active=1 " : "")." ";
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
		, idx=".intval($this->idx)."
		, `pid`='".intval($this->pid)."'
		, `name`='".strPrepare($this->name)."'
		, `typeId`='".intval($this->typeId)."'
		, `catTypeCode`='".strPrepare($this->catTypeCode)."'
		, `classId`='".strPrepare($this->classId)."'
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
	
	
	
	
	# 	пока что только для 2 уровней
	function getFullCatsTree($catType, $activeOnly)
	{
		$additionalClause = CatItem::$types[$_REQUEST['type']] ? " AND type='".strPrepare($_REQUEST['type'])."' " : "";
		
		$ret = Category::getList(0, $catType);
		foreach($ret as $key=>$cat)
		{	
			$cat->subs = Category::getList($cat->id, $catType);
			foreach($cat->subs as $key2=>$subcat)
				$subcat->childElementsCount = CatItem::getCount(array('pid'=>$subcat->id, 'catType'=>$catType, 'type'=>$type, 'active'=>1, 'archived'=>0, 'additionalClause'=>$additionalClause )); 
		}
		
		return $ret;
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
	
	
	
	function initClass()
	{
		if($this->classId)
			$this->class = CatClass::get($this->classId);
	}
	
	
	
	
	function url()
	{
		return '/'.LANG.'/' . Catalog::URL_SIGN .'/'.self::URL_SIGN.'/'.$this->urlPiece() ;
	}
	
	
	function urlPiece()
	{
		return ''.$this->id.'_'.str2url($this->name);
	}
}
?>