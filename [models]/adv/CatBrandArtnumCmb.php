<?php 
class CatBrandArtnumCmb{
	
	const TBL = 'adv__cmb_cat_brand_artnum';
	
	var   $catId
		, $brandId
		, $artnumId
		;
		
	
	
	
	function get($catId, $brandId, $artnumId)
	{
		if($catId = intval($catId) && $brandId = intval($brandId) && $artnumId = intval($artnumId) )
		{
			$sql = "SELECT * FROM `".mysql_real_escape_string(self::TBL)."` WHERE catId=".$catId." AND brandId=".$brandId." AND artnumId=".$artnumId." ";
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
		$m->artnumId = $arr['artnumId'];
        return $m;
	}
	
	
	
	
	
	function getByCatIdAndBrandId($catId, $brandId)
	{
		if( ($catId = intval($catId)) && ($brandId = intval($brandId)) )
		{
			$sql = "SELECT * FROM `".mysql_real_escape_string(self::TBL)."` WHERE catId=".$catId." AND brandId=".$brandId."";
			//vd($sql);
			$qr=DB::query($sql);
			echo mysql_error();
			while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				$res[] = self::init($next);
		}
	
		return $res;
	}
	
	
	
	
	
	
	
	/*
	
	function getList($catId, $brandId)
	{
		$sql = "SELECT * FROM `".mysql_real_escape_string(self::TBL)."` ";
		$qr=DB::query($sql);
		echo mysql_error();
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
			$res[] = self::init($next);
		
		return $res;
	}
	
	*/
	
	
	function insert()
	{
		$sql = "
		INSERT INTO `".self::TBL."`
		SET `catId` = '".intval($this->catId)."', `brandId` = '".intval($this->brandId)."', `artnumId` = '".intval($this->artnumId)."'";
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
	}
	
	
	
	
	
	
	function delete()
	{
		if( ($this->catId = intval($this->catId)) && ($this->brandId = intval($this->brandId)) && ($this->artnumId = intval($this->artnumId)) )
		{
			$sql = "DELETE FROM `".self::TBL."` WHERE catId = '".intval($this->catId)."' AND brandId = '".intval($this->brandId)."' AND artnumId = '".intval($this->artnumId)."'";
			//vd($sql);
			$qr=DB::query($sql);
			echo mysql_error();
		}
	}
	
	
	
	
}
?>