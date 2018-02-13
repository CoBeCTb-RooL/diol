<?php 
class AdvCat{
	
	const TBL = 'adv__categories';
	
	const TREE_ELEMENTS_PER_PAGE = 10;
	
	const URL_SIGN = 'category';
	
	
	
	var   $id
		, $pid
		, $name
		, $statusId
		, $dateCreated
		, $classId
		, $idx
		
		, $class
		, $status
		, $elderCats
		, $productVolumeUnits
		;
		
		

	function getList($params, $status)
	{
		if(gettype($params) != 'array' )
			$params = array('pid'=>$params);
		
		$params['status'] = $params['status']? $params['status'] :  $status;

		$sql="SELECT * FROM `".self::TBL."` WHERE 1 ".self::getListInnerSql($params);
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		while($next=mysql_fetch_array($qr, MYSQL_ASSOC))
		{
			$ret[] = self::init($next);
		}
		
		return $ret;
	}
	
	
	
	
	function getListTree($params, $status)
	{
		//vd($params);
		if(gettype($params) != 'array' )
			$params = array('pid'=>$params);
		
		$params['status'] = $params['status']? $params['status'] :  $status;
		$ret = self::getList($params, $status);
		if($ret)
		{
			foreach($ret as $key=>$cat)
			{
				$params['pid'] = $cat->id;
				$cat->subs = self::getList($params, $status); 
			}
		}
		
		return $ret; 
	}
	
	
	
	
	function getCount($params, $status)
	{	
		if(gettype($params) != 'array' )
			$params = array('pid'=>$params, 'status'=>$status);
		
		$sql="SELECT COUNT(*) FROM `".self::TBL."` WHERE 1 ".self::getListInnerSql($params);
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		$next = mysql_fetch_array($qr);
		//vd($next);
		
		return $next[0];
	}
	

	
	
	
	
	function getListInnerSql($params)
	{
		$sql.=" 
		".(isset($params['pid']) ? "AND (pid=".intval($params['pid']).")" : "")." 
		".($params['additionalClauses'])." 
		".($params['status'] ? " AND status='".intval($params['status']->num)."' " : "")." 
		".strPrepare($params['limit'])."
		ORDER BY ".($params['order'] ? $params['order'] : 'idx')." 
		";
		
		return $sql;
	}
	
	
	
	function getByIdsList($ids, $status)
	{
		if($ids)
		{
			foreach($ids as $key=>$val)
				$ids[$key] = intval($val);
					
			$sql="SELECT * FROM `".strPrepare(self::TBL)."` WHERE 1  AND id IN (".join(', ', $ids).") ";
			if($status)
				$sql.=" AND status='".intval($status->num)."' ";
				//vd($sql);
			$qr=DB::query($sql);
			echo mysql_error();
			while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				$ret[$next['id']] = self::init($next);
		}
		return $ret;
	}
	
	
	
	function init($arr)
	{
		$m = new self();
		
		$m->id = $arr['id'];
		$m->pid = $arr['pid'];
		$m->name = $arr['name'];
		$m->dateCreated = $arr['dateCreated'];
		$m->classId= $arr['classId'];
		$m->idx = $arr['idx'];
		$m->statusId = $arr['status'];
		$m->status = Status::num($m->statusId);
		
        return $m;
	}
	
	
	

	
	
	function get($id, $status)
	{
		if($id =intval($id))
		{
			$sql = "SELECT * FROM `".self::TBL."` WHERE id = ".$id."  
				   ".($status ? " AND status='".intval($status->num)."' " : "")." 
				   ";
			$qr=DB::query($sql);
			echo mysql_error();
			if($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				return self::init($next);
		}
	}
	
	
	
	
	function getElderCats()
	{
		$pid = $this->pid;
		while($pid)
		{
			$tmp = AdvCat::get($pid);
			$pid = $tmp->pid;
			$ret[] = $tmp;
		}
		
		$this->elderCats = array_reverse($ret);
	}
	
	
	
	
	function insert()
	{
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
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
	}
	
	
	
	
	function alterSql()
	{
		$str.="
		  `idx`=".intval($this->idx)."
		, `pid`='".intval($this->pid)."'
		, `name`='".strPrepare($this->name)."'
		, `status` = '".intval($this->status->num)."'
		, `classId`='".intval($this->classId)."'
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
	function getFullCatsTree($status)
	{
		$additionalClause = "";
		
		$ret = AdvCat::getList(0, $status);
		foreach($ret as $key=>$cat)
		{	
			$cat->subCats = AdvCat::getList($cat->id, $status); 
		}
		
		return $ret;
	}
	
	
	
	
	function setIdx($id, $val)
	{
		if($id=intval($id))
		{
			$sql = "UPDATE `".self::TBL."` SET idx='".intval($val)."' WHERE id=".$id;
			vd($sql);
			DB::query($sql);
			echo mysql_error();
		}
	}
	
	
	
	function drawTreeSelect($pid/*чьих детей отображать*/, $self_id, $idToBeSelected, $level=0 )
	{
		global $_CONFIG;
		
		$pid=intval($pid);
		$level=intval($level);
		
		$cat = self::get($pid);	
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
						'pid'=>$pid,
						'limit'=>'', 
						'order'=>'',  
						'additionalClauses'=>'and 1',
						'status'=>null,
					);
		$children = self::getList($params);
		foreach($children as $key=>$child)
		{
			$ret.=self::drawTreeSelect($child->id, $self_id,  $idToBeSelected,  ($level+1));
		} 
	
		return $ret;
	}
	
	
	
	
	function validate()
	{
		if(!trim($this->name))
			$problems[] = Slonne::setError('name', 'Введите название!');
		if(!is_numeric($this->pid))
			$problems[] = Slonne::setError('pid', 'Укажите категорию!');
		
	
		return $problems;
	}
	
	
	
	function initClass($status)
	{
		if($this->classId)
			$this->class = AdvClass::get($this->classId, $status);
	}
	function initProductVolumeUnits($status)
	{
		$this->productVolumeUnits = ProductVolumeUnit::getList( $status, $catId=$this->id);
	}
	
	
	function url()
	{
		global $CORE, $_CONFIG;
		
		$route = Route::getByName(Route::SPISOK_OBYAVLENIY_KATEGORII);
		$ret = $route->url($this->urlPiece());
		return $ret; 
		//return ($CORE->lang->code != $_CONFIG['default_lang']->code?'/'.$CORE->lang->code.'':'') . '/'.URL_ADVS_LIST.'/'.$this->urlPiece();
	}
	
	
	
	function urlPiece()
	{
		return ''.$this->id.'_'.str2url($this->name);
	}
	
	
	function urlAdmin()
	{
		return '/'.ADMIN_URL_SIGN.'/categories/?catId='.$this->id;
	}
	
	
	function getNextIdx($catId)
	{
		$sql = "SELECT MAX(idx) as res  FROM `".mysql_real_escape_string(self::TBL)."` WHERE 1 ".($catId ? " AND pid=".intval($catId)." " : "")."";
		$qr = DB::query($sql);
		echo mysql_error();
	
		$next = mysql_fetch_array($qr, MYSQL_ASSOC);
		$res = $next['res'];
	
		$res = $res % 10 ? $res + (10-$res%10) : $res+10;
	
		return $res;
	}
	
	
}
?>