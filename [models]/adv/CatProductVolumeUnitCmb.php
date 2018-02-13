<?php 
class CatProductVolumeUnitCmb{
	
	const TBL = 'adv__cmb_cat_product_volume_unit';
	
	var   $catId
		, $unitId
		;
		
	
	
	
	function get($catId, $unitId)
	{
		if(($catId = intval($catId)) && ($unitId = intval($unitId)))
		{
			$sql = "SELECT * FROM `".mysql_real_escape_string(self::TBL)."` WHERE catId=".$catId." AND unitId=".$unitId." ";
			$qr=DB::query($sql);
			echo mysql_error();
			if($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				$res = self::init($next);
		}
		
		return $res;
	}
	
	
	
	function init($arr)
	{
		$m = new self();
		$m->catId = $arr['catId'];
		$m->unitId = $arr['unitId'];
        return $m;
	}
	
	
/*
	function getList($catId, $unitId)
	{
		$sql = "SELECT * FROM `".mysql_real_escape_string(self::TBL)."` ";
		$qr=DB::query($sql);
		echo mysql_error();
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
			$res[] = self::init($next);
		
		return $res;
	}*/
	
	
	
	
	function insert()
	{
		$sql = "
		INSERT INTO `".self::TBL."`
		SET `catId` = '".intval($this->catId)."', `unitId` = '".intval($this->unitId)."'";
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
	}
	
	
	
	
	
	
	function delete()
	{
		if(  ($this->catId = intval($this->catId)) && ($this->unitId = intval($this->unitId))  )
		{
			$sql = "DELETE FROM `".self::TBL."` WHERE catId = '".intval($this->catId)."' AND unitId = '".intval($this->unitId)."'";
			//vd($sql);
			$qr=DB::query($sql);
			echo mysql_error();
		}
	}
	
	
	
	
}
?>