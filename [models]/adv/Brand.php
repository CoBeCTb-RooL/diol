<?php 
class Brand{
	
	const TBL = 'adv__brands';
	const MEDIA_SUBDIR = 'brands';
	
	var   $id
		, $statusId
		, $dateCreated
		, $idx
		, $name
		, $pic
		
		, $status
		;
		
	
	
	
	function init($arr)
	{
		$m = new self();
		$props = get_object_vars($m);
	
		foreach ($arr as $key => $value)
		{
			if(property_exists($m, $key ))
				$m->{$key} = $value;
		}
		$m->pic = $m->pic ? self::MEDIA_SUBDIR.'/'.$arr['pic'] : '';
		$m->statusId = $arr['status'];
		$m->status = Status::num($m->statusId);
		return $m;
	}	
		
	
	function getList($status, $catId)
	{
		$catId = intval($catId);
		
		$sql = "SELECT brands.* FROM `".mysql_real_escape_string(self::TBL)."` AS brands ";
		if($catId) 
			$sql.=" INNER JOIN  `".CatBrandCmb::TBL."` AS cmb  ON cmb.brandId=brands.id ";
		
		$sql.=" WHERE 1 ";
		
		if($catId) 
			$sql.=" AND catId=".$catId." ";
		
		if($status) 
			$sql.=" AND status='".strPrepare($status->num)."' ";
		
		//vd($sql);
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
	
	
	
	function getByName($name)
	{
		if($name =strPrepare($name))
		{
			$sql = "SELECT * FROM `".self::TBL."` WHERE name = '".$name."'";
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
		SET `dateCreated` = NOW(), 
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
		  `status` = '".intval($this->status->num)."'
		, `idx`='".intval($this->idx)."'
		, `name`='".strPrepare($this->name)."'
		, `pic`='".strPrepare($this->pic)."'
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
			//vd($sql);
			DB::query($sql);
			echo mysql_error();
		}
	}
	
	
	
	
	
	function validate()
	{
		if(!trim($this->name))
			$problems[] = new Error('Заполните все необходимые поля!', 'name');
		
		if(($byName = $this->getByName($this->name)) && $byName->id!=$this->id)
			$problems[] = new Error('Бренд с таким названием уже существует!', 'name');
		
		return $problems;
	}
	
	
	
	
	function getNextIdx()
	{
		$sql = "SELECT MAX(idx) as res  FROM `".mysql_real_escape_string(self::TBL)."`";
		$qr = DB::query($sql);
		echo mysql_error();
		
		$next = mysql_fetch_array($qr, MYSQL_ASSOC);
		$res = $next['res'];
		
		$res = $res % 10 ? $res + (10-$res%10) : $res+10;
		
		return $res;
	}
	
	
	
}
?>