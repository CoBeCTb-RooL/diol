<?php 
class City{
	
	const TBL = 'adv__region_cities';
	const ALMATY_ID = 1; 
	
	var   $id
		, $countryId
		, $name
		, $status
		, $dateCreated
		
		, $country
		;
		
	
		
	function init($arr)
	{
		$m = new self();
		
		$m->id = $arr['id'];
		$m->countryId = $arr['countryId'];
		$m->name = $arr['name'];
		$m->status = Status::num($arr['status']);
		$m->dateCreated = $arr['dateCreated'];
		$m->isLarge = $arr['isLarge'] ? true : false;
		
		return $m;
	}
	
	
	function getList($countryId, $status, $orderBy)
	{
		$sql = "SELECT * FROM `".self::TBL."` WHERE 1 ".($countryId?" AND countryId='".intval($countryId)."' ":"")." ".($status ? " AND status='".strPrepare($status->num)."'" : "")."";
		if($orderBy)
			$sql.=" ORDER BY ".strPrepare($orderBy)." ";
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
			$res[$next['id']] = self::init($next);
		
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
	
	
	function getByNameAndCountryId($name, $countryId)
	{
		$sql = "SELECT * FROM `".self::TBL."` WHERE name = '".strPrepare($name)."' ";
		if($countryId = intval($countryId))
			$sql .= " AND countryId=".$countryId." ";
		$qr=DB::query($sql);
		echo mysql_error();
		if($next = mysql_fetch_array($qr, MYSQL_ASSOC))
			return self::init($next);
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
		, `countryId`='".intval($this->countryId)."'
		, `status`='".intval($this->status->num)."'
		, `isLarge`='".($this->isLarge ? 1 : 0)."'
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
		if(!intval($this->name))
			$problems[] = Slonne::setError('countryId', 'Укажите страну!');
		
		return $problems;
	}
	
	
	
	function getByName($name)
	{
		if($name =trim($name))
		{
			$sql = "SELECT * FROM `".self::TBL."` WHERE name = '".strPrepare($name)."'";
			$qr=DB::query($sql);
			echo mysql_error();
			if($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				return self::init($next);
		}
	}
	
	
	
	function initCountry()
	{
		$this->country = Country::get($this->countryId);
	}
	
	
	
	
	function getFromCacheFile()
	{
		$str = file_get_contents(INFO_CACHE_DIR_PATH.'/'.INFO_CACHE_CITIES_FILE);
		if($tmp = json_decode($str))
		{
			foreach($tmp as $key=>$c)
				$tmp2[$key] = Slonne::cast('City', $c);
		}
		return $tmp2;
	}
	
	
	
}
?>