<?php 
class AdvPropTableColumnValue{
	
	const TBL = 'adv__props_table_columns_values';
	
	
	
	// catId выпилить  потом надо 
	var   $advId
		, $propId
		, $rowId
		, $columnId
		, $value
		, $status
		, $dateCreated
		
		;
		
	
	function init($arr)
	{
		$m = new self();
	
		$m->advId = $arr['advId'];
		$m->propId = $arr['propId'];
		$m->rowId = $arr['rowId'];
		$m->columnId = $arr['columnId'];
		$m->status = Status::num($arr['status']);
		$m->dateCreated = $arr['dateCreated'];
		$m->value = $arr['value'];
		
	//vd($arr);
		return $m;
	}	
		
	
	
	function getRowsArrByAdvIdAndPropId($advId, $propId)
	{
		$tmp = self::getByAdvIdAndPropId($advId, $propId);
		//vd($tmp);
		foreach($tmp as $val)
		{
			$ret[$val->rowId][$val->columnId]=$val->value;
		}
		//vd($ret);
		return $ret; 
	}
	
	
	/*function get($id, $status)
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
	}*/
	
	
	function getByAdvIdAndPropId($advId, $propId, $status)
	{
		if(($propId = intval($propId)) && ($advId=intval($advId)) )
		{
			$sql = "SELECT * FROM `".self::TBL."` WHERE propId=".$propId." AND advId=".$advId." ";
			if($status)
				$sql.=" AND status='".intval($status->num)."'";
			//vd($sql);
			$qr=DB::query($sql);
			echo mysql_error();
			while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				$ret[] = self::init($next);
		}
		return $ret;
	}
	
	
	
	/*function getList($propId, $status)
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
	}*/
	
	
	
	
	function insert()
	{
		//$this->idx = 9999;
		$sql = "
		INSERT INTO `".self::TBL."` 
		SET dateCreated=NOW(),
		".$this->alterSql()."
		
		";
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		return mysql_insert_id();
	}
	
	
	
	/*function update()
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
	}*/
	
	
	
	
	function alterSql()
	{
		$str.="
		  `status` = '".intval($this->status->num)."'
		, advId=".intval($this->advId)."
		, propId=".intval($this->propId)."
		, `rowId`='".intval($this->rowId)."'
		, `columnId`='".intval($this->columnId)."'
		, `value`='".strPrepare($this->value)."'
		
		";
		
		return $str;
	}
	
	
	
	function deleteByAdvIdAndPropId($advId, $propId)
	{
		//echo "!!!!!!!!!!!!!!!!!!!!!";
		//vd(intval($advId));
		//vd($propId);
		if(($advId = intval($advId)) && ($propId = intval($propId)))
		{
			$sql = "
			DELETE FROM `".self::TBL."` WHERE advId=".$advId." AND propId=".$propId."";
			//vd($sql);
			DB::query($sql);
			echo mysql_error();
		}
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
	
	
	
	
	
	
	
	function validate()
	{
		$problems = null;
		
		if(!$this->name) $problems[] = Slonne::setError('name', 'Введите название!');
		
		return $problems;
	}
	
	
	
	
	
	/*function initProp($status)
	{
		$this->prop = AdvProp::get($this->propId, $status);
	}*/

	
	
	
	
}
?>