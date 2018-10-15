<?php 
class Client{
	
	const TBL = 'clients';
	
	const URL_SIGN = 'category';

	var   $id
		, $surname
		, $name
		, $fathername
		, $statusId
		, $createdAt
		, $updatedAt
		, $status
		;



	function init($arr)
	{
		$m = new self();

		$m->id = $arr['id'];
		$m->surname = $arr['surname'];
		$m->name = $arr['name'];
		$m->fathername = $arr['fathername'];
		$m->phone = $arr['phone'];
		$m->address = $arr['address'];
		$m->createdAt = $arr['createdAt'];
		$m->updatedAt= $arr['updatedAt'];
		$m->statusId = $arr['status'];
		$m->status = Status::num($m->statusId);

		return $m;
	}

	function getList($params)
	{
		$ret = [];
		$sql="SELECT * FROM `".self::TBL."` WHERE 1 ".self::getListInnerSql($params);
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		while($next=mysql_fetch_array($qr, MYSQL_ASSOC))
			$ret[] = self::init($next);

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
		$sql="";

		if(isset($params['status']) && $params['status'])
			$sql.="AND status='".intval($params['status']->num)."'";

		if(isset($params['search']) && $params['search'])
			$sql.=" AND 
				(
					LOWER(surname) LIKE '%".strPrepare(mb_strtolower($params['search']))."%'
					OR  LOWER(name) LIKE '%".strPrepare(mb_strtolower($params['search']))."%'
					OR  LOWER(fathername) LIKE '%".strPrepare(mb_strtolower($params['search']))."%'
					OR  phone LIKE '%".strPrepare($params['search'])."%'
				)";

		if(isset($params['surnameLike']) && $params['surnameLike'])
			$sql .=" AND LOWER(surname) LIKE '%".strPrepare(mb_strtolower($params['surnameLike']))."%'";
		if(isset($params['nameLike']) && $params['nameLike'])
			$sql .=" AND LOWER(name) LIKE '%".strPrepare(mb_strtolower($params['nameLike']))."%'";
		if(isset($params['fatherNameLike']) && $params['fatherNameLike'])
			$sql .=" AND LOWER(fatherName) LIKE '%".strPrepare(mb_strtolower($params['fatherNameLike']))."%'";
		if(isset($params['phoneLike']) && $params['phoneLike'])
			$sql .=" AND phone LIKE '%".strPrepare($params['phoneLike'])."%'";


		if(isset($params['phone']) && $params['phone'])
			$sql.=" AND phone LIKE '%".strPrepare($params['phone'])."%' ";
		if(isset($params['email']) && $params['email'])
			$sql.=" AND email LIKE '%".strPrepare($params['email'])."%' ";

		if(isset($params['from']) && isset($params['count']))
			$sql.=" LIMIT  ".$params['from'].", ".$params['count']."";

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
	
	
	

	
	function fio()
	{
		return trim($this->surname.' '.$this->name.' '.$this->fathername);
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
			$tmp = Client::get($pid);
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
		createdAt=NOW(),
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
		  `surname`='".strPrepare($this->surname)."'
		, `name`='".strPrepare($this->name)."'
		, `fathername`='".strPrepare($this->fathername)."'
		, `phone`='".strPrepare(str_replace(' ', '', $this->phone))."'
		, `address`='".strPrepare($this->address)."'
		, `status` = '".intval($this->status->num)."'
		, `updatedAt` = NOW()
		";
		
		return $str;
	}



	function setData($arr)
	{
		$this->surname = strPrepare($arr['surname']);
		$this->name = strPrepare($arr['name']);
		$this->fathername = strPrepare($arr['fathername']);
		$this->phone = strPrepare($arr['phone']);
		$this->address = strPrepare($arr['address']);
		$this->status = $arr['active'] ? Status::code(Status::ACTIVE) : Status::code(Status::INACTIVE);
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
	
	
	
	function initMedia()
	{
		$arr = [];

		$arr = Media2::getList([
			'objType'=>Object::CLIENT,
			'objId' =>$this->id,
			'orderBy' => 'idx',
		]);

		$this->media = $arr;
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
		if(!trim($this->surname))
			$problems[] = Slonne::setError('surname', 'Введите фамилию!');
		if(!trim($this->name))
			$problems[] = Slonne::setError('name', 'Введите имя!');

		
		return $problems;
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