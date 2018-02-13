<?php 
class CatBrandCmb{
	
	const TBL = 'adv__cmb_cat_brand';
	
	var   $catId
		, $brandId
		;
		
	
	
	
	function get($catId, $brandId)
	{
		if( ($catId = intval($catId)) && ($brandId = intval($brandId)) )
		{
			$sql = "SELECT * FROM `".mysql_real_escape_string(self::TBL)."` WHERE catId=".$catId." AND brandId=".$brandId." ";
			//vd($sql);
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
		$m->brandId = $arr['brandId'];
        return $m;
	}
	
	
	
	
	
	function getByCatId($catId)
	{
		if($catId = intval($catId))
		{
			$sql = "SELECT * FROM `".mysql_real_escape_string(self::TBL)."` WHERE catId=".$catId."";
			$qr=DB::query($sql);
			echo mysql_error();
			while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				$res[] = self::init($next);
		}
	
		return $res;
	}
	
	
	
	
	function getByBrandId($brandId)
	{
		if($brandId = intval($brandId))
		{
			$sql = "SELECT * FROM `".mysql_real_escape_string(self::TBL)."` WHERE brandId=".$brandId."";
			$qr=DB::query($sql);
			echo mysql_error();
			while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				$res[] = self::init($next);
		}
	
		return $res;
	}
	
	
	
	
	
	function getList($catId, $brandId)
	{
		$sql = "SELECT * FROM `".mysql_real_escape_string(self::TBL)."` ";
		$qr=DB::query($sql);
		echo mysql_error();
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
			$res[] = self::init($next);
		
		return $res;
	}
	
	
	
	
	function insert()
	{
		$sql = "
		INSERT INTO `".self::TBL."`
		SET `catId` = '".intval($this->catId)."', `brandId` = '".intval($this->brandId)."'";
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
	}
	
	
	
	
	
	
	function delete()
	{
		if( ($this->catId = intval($this->catId)) && ($this->brandId = intval($this->brandId)))
		{
			$sql = "DELETE FROM `".self::TBL."` WHERE catId = '".intval($this->catId)."' AND brandId = '".intval($this->brandId)."'";
			//vd($sql);
			$qr=DB::query($sql);
			echo mysql_error();
		}
	}
	
	
	
	
}
?>