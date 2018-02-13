<?php 
class Country{
	
	const TBL = 'adv__region_countries';
	
	const KAZAKHSTAN_ID = 1;
	
	var   $id
		, $name
		, $status
		;
		
	
		
	function init($arr)
	{
		$m = new self();
		
		$m->id = $arr['id'];
		$m->name = $arr['name'];
		$m->status = Status::num($arr['status']);
		
		return $m;
	}
	
	
	function getList($status)
	{
		//$sql = "SELECT * FROM `".mysql_real_escape_string(self::TBL)."` WHERE 1  ORDER BY id";
		$sql = "SELECT * FROM `".self::TBL."` WHERE 1 ".($status ? " AND status='".strPrepare($status->num)."'" : "")."";
		$qr=DB::query($sql);
		echo mysql_error();
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
			$res[] = self::init($next);
		
		return $res;
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
		$sql = "
		INSERT INTO `".self::TBL."` 
		SET 
		".$this->alterSql()."
		";
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
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
		  `name`='".strPrepare($this->name)."'
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
	
	
	
	
	
	function validate()
	{
		if(!trim($this->name))
			$problems[] = Slonne::setError('name', 'Введите название!');
		
		return $problems;
	}
	
	
	
	
	
}
?>