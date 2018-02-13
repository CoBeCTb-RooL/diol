<?php 
class AdvCatSelectOption{
	
	const TBL = 'adv__select_options';
	
	var   $id
		, $propId
		, $statusId
		, $value
		
		, $status
		;
		
	
	
		
		
	function init($arr)
	{
		$m = new self();
		$m->id = $arr['id'];
		$m->propId = $arr['propId'];
		$m->statusId = $arr['status'];
		$m->value = $arr['value'];
	
		$m->status = Status::num($m->statusId);
	
		return $m;
	}
	
	
	function getList($propId, $status)
	{
		$sql = "SELECT * FROM `".mysql_real_escape_string(self::TBL)."` WHERE 1 
				".($propId=intval($propId) ? " AND propId=".$propId." " : "")." 
				".($status ? " AND status='".intval($status->num)."' " : "" )." 
				ORDER BY value";
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
			$ret[$next['id']] = self::init($next);
		
		return $ret;
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
	
	
	function getSimilarByValue()
	{ 
		$sql = "SELECT * FROM `".self::TBL."` WHERE propId = ".intval($this->propId)." AND value='".strPrepare($this->value)."' ".($this->id ? " AND id!='".intval($this->id)."' " : "" )." ";
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		if($next = mysql_fetch_array($qr, MYSQL_ASSOC))
			return self::init($next);
	}
	
	
	
	
	function insert()
	{
		$sql = "
		INSERT INTO `".self::TBL."` 
		SET `value`='".$this->value."'
		, `propId` = '".intval($this->propId)."'
		, `status` = '".intval($this->status->num)."'
		";
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
	}
	
	
	
	function update()
	{
		$sql = "
		UPDATE `".self::TBL."` 
		SET `value` = '".strPrepare($this->value)."'
		, `propId` = '".intval($this->propId)."'
		, `status` = '".intval($this->status->num)."'
		WHERE id=".intval($this->id)."
		";
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
	}
	
	
	
	function delete($id)
	{
		if($id = intval($id))
		{
			$sql = "
			DELETE FROM `".self::TBL."` WHERE id=".$id;
			//vd($sql);
			DB::query($sql);
			echo mysql_error();
		} 
	}
	
	
	
	
	
	
	/*function getOptions($propId, $activeOnly=true)
	{
		//vd($propId);
		if($propId = intval($propId))
		{
			$sql="SELECT * FROM `".self::TBL."` WHERE propId='".$propId."' ".($activeOnly ? "AND `active`=1" : "" )."";
			//vd($sql);
			$qr = DB::query($sql);
			echo mysql_error();
			while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
			{
				$ret[$next['id']] = self::init($next);
			}
		}
		
		return $ret;
	}*/
	
	
	
}
?>