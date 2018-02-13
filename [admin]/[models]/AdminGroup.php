<?php
class AdminGroup
{
	public $id;
	public $name;
	public $status;
	public $dateCreated;
	public $role;

			
	const TBL = 'slonne__admin_groups';
	
	
	
	
	function init($arr)
	{
		if($arr)
		{
			$u = new self;
				
			$u->id = $arr['id'];
			$u->name = $arr['name'];
			$u->dateCreated = $arr['dateCreated'];
			$u->role = intval($arr['role']);
			$u->status = Status::num($arr['status']);
				
			return $u;
		}
	}
	
	
	
	
	function getList($status, $statusesNotIn)
	{
		$sql="SELECT * FROM `".self::TBL."` WHERE 1 ";
		if($status)
			$sql.=" AND status='".intval($status->num)."' ";
		if($statusesNotIn)
		{
			$sql.=" AND status NOT IN(-1 ";
			foreach($statusesNotIn as $s)
				$sql.=", ".intval($s->num)."";
			$sql.=") ";
		}
		$sql .= "ORDER BY id";
	//	vd($sql);
		$qr = DB::query($sql);
		echo mysql_error();
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
			$ret[] = self::init($next);
		
		return $ret;
	}
	
	
	
	
	
	function get($id, $status)
	{
		if($id =intval($id))
		{
			$sql = "SELECT * FROM `".self::TBL."` WHERE id = ".$id;
			if($status)
				$sql.=" AND status='".intval($status->num)."' ";
			
			$qr=DB::query($sql);
			echo mysql_error();
			if($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				return self::init($next);
		}
	}
	
	
	
	function insert()
	{
		$sql = "
		INSERT INTO `".self::TBL."` SET
		  dateCreated = NOW(), 		
		".$this->innerAlterSql()."
		";
		//vd($sql);
		DB::query($sql);
		echo mysql_error();
		//vd($sql);
		return mysql_insert_id();
	}
	
	
	
	
	function update()
	{
		$sql = "
		UPDATE `".self::TBL."` SET
		".$this->innerAlterSql()."
		WHERE id=".$this->id."
		";
		//vd($sql);
		DB::query($sql);
		echo mysql_error();
		//vd($sql);
	}
	
	
	
	
	function innerAlterSql()
	{
		$str="
		  status='".intval($this->status->num)."'		
		, name = '".strPrepare($this->name)."'
		, role = '".strPrepare($this->role)."'
		";
		
		return $str;
	}
	
	
	
	function validate()
	{
		if(!trim($this->name))
			$errors[] = new Error('Введите название!', 'name');
	
		return $errors;
	}
	
	
	/*function delete($id)
	{
		if($id = intval($id))
		{
			$sql = "
			DELETE FROM `".self::TBL."` WHERE id=".$id;
			DB::query($sql);
			echo mysql_error(); 
		}
	}*/
	
	
	
	
	
	/*function initPrivilegesArr()
	{
		//vd($this->privileges);
		if($this->privileges)
		{
			$tmp = explode('|', $this->privileges);
			//vd($tmp);
			foreach($tmp as $key=>$val)
			{
				$tmp2 = explode(':', $val);
				//$result[$tmp2[0]] = array();
				//vd($tmp2);
				$tmp3 = explode(',', $tmp2[1]);
				foreach($tmp3 as $key2=>$val2)
					$result[$tmp2[0]][$val2] = 1;
				//vd($tmp3);
				//$result[$tmp2[0]] = explode(',', $tmp2[1]);
				
			}
		}
		//echo '<hr>';
		//vd($result);
		$this->privilegesArr = $result;
	}*/
	
	
	
	
	function adminUrl()
	{
		return '/'.ADMIN_URL_SIGN.'/adminGroup/?id='.$this->id;
	}
	
	
	
} 
?>