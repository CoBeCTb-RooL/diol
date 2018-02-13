<?php 
class ProductVolumeUnit{
	
	const TBL = 'adv__product_volume_units';
	
	var   $id
		, $name
		, $status
		, $dateCreated
		;
		
	
		
	function init($arr)
	{
		$m = new self();
		
		$m->id = $arr['id'];
		$m->name = $arr['name'];
		$m->status = Status::num($arr['status']);
		$m->dateCreated = $arr['dateCreated'];
		
		return $m;
	}
	
	
	function getList($status, $catId)
	{
		$sql = "SELECT prime.* FROM `".self::TBL."` AS prime";
		
		if($catId = intval($catId))
			$sql .= " LEFT JOIN `".CatProductVolumeUnitCmb::TBL."` as cpvuc on prime.id=cpvuc.unitId ";
		
		$sql .= " WHERE 1 ";
		
		if($catId)
			$sql.=" AND cpvuc.catId='".$catId."' ";
		
		if($status)
			$sql.=" AND status='".intval($status->num)."' ";
		
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
			$res[$next['id']] = self::init($next);
		//vd($res);
		return $res;
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
		SET dateCreated=NOW(),
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
		  `name`='".strPrepare($this->name)."'
		, `status`='".intval($this->status->num)."'
		";
		
		return $str;
	}
	
	
	
	function validate()
	{
		if(!trim($this->name))
			$problems[] = Slonne::setError('name', 'Введите название!');
		
		return $problems;
	}
	
	
	
	
}
?>