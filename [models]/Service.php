<?php 
class Service{
	
	const TBL = 'entity__services';
	


	public $id;
	public $pid;
	public $name;
	public $idx;
	public $active;



	function init($arr)
	{
		$m = new self();

		$m->id = $arr['id'];
		$m->pid = $arr['pid'];
		$m->name = $arr['name'];
		$m->active = $arr['active'];

		return $m;
	}

	function getList($params)
	{
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

		if(isset($params['active']))
			$sql.="AND active=".($params['active'] ? 1 : 0)."";

		if(isset($params['pid']) && intval($params['pid']))
			$sql.="AND pid=".intval($params['pid'])."";

		if(isset($params['orderBy']) && $params['orderBy'])
			$sql.="orderBy ".strPrepare($params['orderBy'])."";

		if(isset($params['from']) && isset($params['count']))
			$sql.=" LIMIT  ".$params['from'].", ".$params['count']."";

		return $sql;
	}
	

	public function initSubs($params=[])
	{
		$params['pid'] = $this->id;
		$this->subs = self::getList($params);
	}
	
	

	
	function get($id, $active=null)
	{
		if($id =intval($id))
		{
			$sql = "SELECT * FROM `".self::TBL."` WHERE id = ".$id."  
				   ".($active!==null ? " AND active='".($active ? 1 : 0)."' " : "")." 
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
			$tmp = self::get($pid);
			$pid = $tmp->pid;
			$ret[] = $tmp;
		}
		
		$this->elderCats = array_reverse($ret);
	}
	
	
	
	
	function insert()
	{
		$sql = "
		INSERT INTO `".self::TBL."` 
		SET   `idx`= 99999
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
		SET  `idx`='".intval($this->idx)."'
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
		  `pid`='".strPrepare($this->pid)."'
		, `name`='".strPrepare($this->name)."'
		, `active`='".($this->active ? 1 : 0)."'
		
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
				$ret.='|  '.$cat->name;
				$ret.='
				</option>';
		}
		
		#	достаём детей
		$params = array(
						'pid'=>$pid,
					);
		$children = self::getList($params);
		foreach($children as $key=>$child)
		{
			$ret.=self::drawTreeSelect($child->id, $self_id,  $idToBeSelected,  ($level+1));
		} 
	
		return $ret;
	}




	function drawTreeSelect2($services, $pid=0,  $idToBeSelected, $level=0 )
	{
		global $_CONFIG;

		$pid=intval($pid);
		$level=intval($level);


		$items = [];
		foreach ($services as $s)
			if($s->pid == $pid)
				$items[] = $s;

		foreach ($items as $s)
		{
			$children = [];
			foreach ($services as $s2)
				if($s2->pid == $s->id)
					$children[] = $s2;

			$ret.='<option '.($idToBeSelected==$s->id?' selected="selected"  ':'').' value="'.$s->id.'" '.(count($children) ? ' disabled ' : '').'>';
			for($i=1; $i<=$level; $i++)
				$ret.='---| ';
			$ret .= $s->name.'
				</option>';

			foreach ($children as $ch)
				$ret.=self::drawTreeSelect2($services, $s->id, $idToBeSelected,  ($level+1));
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