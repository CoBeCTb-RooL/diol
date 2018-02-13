<?php 
class AdvPropTableColumn{
	
	const TBL = 'adv__props_table_columns';
	
	
	
	// catId выпилить  потом надо 
	var   $id
		, $propId
		, $name
		, $idx
		, $status
		, $dateCreated
		
		, $prop
		;
		
	
	function init($arr)
	{
		$m = new self();
	
		$m->id = $arr['id'];
		$m->propId = $arr['propId'];
		$m->name = $arr['name'];
		$m->status = Status::num($arr['status']);
		$m->idx = $arr['idx'];
		$m->dateCreated = $arr['dateCreated'];
		
	//vd($arr);
		return $m;
	}	
		
	
	

	
	function get($id, $status)
	{
		if($id = intval($id))
		{
			$sql = "SELECT * FROM `".self::TBL."` WHERE id=".$id." ".($status ? " AND status='".intval($status->num)."' " : "")."";
			//vd($sql);
			$qr=DB::query($sql);
			echo mysql_error();
			if($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				return self::init($next);
		}
	}
	
	
	function getByPropIdAndName($propId, $name, $columnId)
	{
		if($propId = intval($propId))
		{
			$sql = "SELECT * FROM `".self::TBL."` WHERE propId=".$propId." AND name='".strPrepare($name)."' ";
			if($columnId = intval($columnId))
				$sql.=" AND id!='".$columnId."' ";
			//vd($sql);
			$qr=DB::query($sql);
			echo mysql_error();
			if($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				return self::init($next);
		}
	}
	
	
	
	function getList($propId, $status)
	{
		$sql = "SELECT * FROM `".self::TBL."` WHERE 1 
				".($propId ? " AND propId=".intval($propId)." " : "")." 
				".($status ? " AND status='".intval($status->num)."' " : "")."
				ORDER BY idx";
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
			$ret[] = self::init($next);
		
		return $ret;
	}
	
	
	
	
	function insert()
	{
		//$this->idx = 9999;
		$sql = "
		INSERT INTO `".self::TBL."` 
		SET dateCreated=NOW(),
		".$this->alterSql()."
		
		";
		vd($sql);
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
		  `status` = '".intval($this->status->num)."'
		, idx=".intval($this->idx)."
		, `name`='".strPrepare($this->name)."'
		, `propId`='".intval($this->propId)."'
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
		$problems = null;
		
		if(!$this->name) $problems[] = Slonne::setError('name', 'Введите название!');
		
		return $problems;
	}
	
	
	
	
	
	function initProp($status)
	{
		$this->prop = AdvProp::get($this->propId, $status);
	}

	
	
	
	
}
?>