<?php 
class BrandArtnumCmb{
	
	const TBL = 'adv__cmb_brand_artnum';
	
	var   $brandId
		, $artnumId
		;
		
	
	
	
	function get($brandId, $artnumId)
	{
		if($brandId = intval($brandId) && $artnumId = intval($artnumId))
		{
			$sql = "SELECT * FROM `".mysql_real_escape_string(self::TBL)."` WHERE brandId=".$brandId." AND artnumId=".$artnumId." ";
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
		$m->brandId = $arr['brandId'];
		$m->artnumId = $arr['artnumId'];
        return $m;
	}
	
	

	
	
	function getByBrandId($brandId)
	{
		if($brandId = intval($brandId))
		{
			$sql = "SELECT * FROM `".mysql_real_escape_string(self::TBL)."` WHERE brandId=".$brandId."";
			//vd($sql);
			$qr=DB::query($sql);
			echo mysql_error();
			while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				$res[] = self::init($next);
		}
	
		return $res;
	}
	
	
	
	function getByArtnumId($artnumId)
	{
		if($artnumId = intval($artnumId))
		{
			$sql = "SELECT * FROM `".mysql_real_escape_string(self::TBL)."` WHERE artnumId=".$artnumId."";
			$qr=DB::query($sql);
			echo mysql_error();
			while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				$res[] = self::init($next);
		}
	
		return $res;
	}
	
	
	
	
	function getList($brandId, $artnumId)
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
		SET `brandId` = '".intval($this->brandId)."', `artnumId` = '".intval($this->artnumId)."'";
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
	}
	
	
	
	
	
	
	function delete()
	{
		if(  ($this->brandId = intval($this->brandId)) && ($this->artnumId = intval($this->artnumId))  )
		{
			$sql = "DELETE FROM `".self::TBL."` WHERE brandId = '".intval($this->brandId)."' AND artnumId = '".intval($this->artnumId)."'";
			//vd($sql);
			$qr=DB::query($sql);
			echo mysql_error();
		}
	}
	
	
	
	
}
?>