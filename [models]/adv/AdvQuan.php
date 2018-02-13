<?php 
class AdvQuan{
	
	const TBL = 'adv__items_quan';
	
	var   $catId
		, $cityId
		, $dealTypeCode
		, $quan
		, $dateCreated
		
		, $dealType
		;
		
	
	
	
		
	function init($arr)
	{
		$m = new self();
	
		$m->catId = $arr['catId'];
		$m->cityId = $arr['cityId'];
		$m->dealTypeCode = $arr['dealType'];
		$m->quan = $arr['quan'];
		$m->dateCreated = $arr['dateCreated'];
	
		$m->dealType = DealType::code($m->dealTypeCode);
		
		return $m;
	}
		
		
		
	function getListByCity($cityId)
	{
		$cityId = intval($cityId);
		
		$sql = "SELECT * FROM `".mysql_real_escape_string(self::TBL)."` WHERE 1 AND cityId=".$cityId." AND dealType='' ";
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
			$res[$next['catId']] = self::init($next);
		
		return $res;
	}
	
	
	
	
	
	function getListByCityAndDealType($cityId, $dealType)
	{
		
		$sql = "SELECT * FROM `".mysql_real_escape_string(self::TBL)."` WHERE 1 ";
		$sql.=" AND cityId=".intval($cityId)." ";
		$sql.=" AND dealType='".strPrepare($dealType->code)."' ";
		
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
			$res[$next['catId']] = self::init($next);
		
		return $res;
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
	

	
	
	
	function alterSql()
	{
		$str.="
		  `cityId`='".intval($this->cityId)."'
		, `catId`='".intval($this->catId)."'
		, `dealType`='".strPrepare($this->dealType->code)."'
		, `quan`='".intval($this->quan)."'
		";
		
		return $str;
	}
	
	
	
	
	
	function deleteAll()
	{
		$sql="TRUNCATE TABLE `".self::TBL."`";
		DB::query($sql);
		echo mysql_error();
	}
	
	
	
	
	function recacheAll()
	{
		self::deleteAll();
		//return;
		
		# 	по категориям всего
		self::recacheCat();
		
		$dealTypes = DealType::$items;
		
		# 	по типам
		foreach($dealTypes as $dt)
		{
			self::recacheCatDealType($dt);
		}
	
		# 	по городам
		$cities = City::getList(Status::code(Status::ACTIVE));
		foreach($cities as $city)
		{
			self::recacheCatCity($city->id);
			
			# 	города и типы
			foreach($dealTypes as $dt)
				self::recacheCatCityDealType($city->id, $dt);
		}
	}
	
	
	function recacheCatCity($cityId)
	{
		$sql="SELECT catId AS catId,  COUNT(*) AS quan  FROM `".mysql_real_escape_string(AdvItem::TBL)."` WHERE status='".intval(Status::code(Status::ACTIVE)->num)."' AND cityId='".intval($cityId)."' GROUP BY catId ";
		$qr = DB::query($sql);
		echo mysql_error();
		
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
		{
			//vd($next);
			$a = new AdvQuan();
			$a->cityId = $cityId;
			$a->quan = intval($next['quan']);
			$a->catId = strPrepare($next['catId']);
			
			$a->insert();
		}
		
	}
	
	
	
	
	function recacheCatCityDealType($cityId, $dealType)
	{
		$sql="SELECT catId AS catId,  COUNT(*) AS quan  FROM `".mysql_real_escape_string(AdvItem::TBL)."` 
		WHERE status='".intval(Status::code(Status::ACTIVE)->num)."' 
		AND cityId='".intval($cityId)."' 
		AND dealTypeId='".intval($dealType->num)."'
		GROUP BY catId ";
		//vd($sql);
		$qr = DB::query($sql);
		echo mysql_error();
	
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
		{
			$a = new AdvQuan();
			$a->cityId = $cityId;
			$a->quan = intval($next['quan']);
			$a->catId = strPrepare($next['catId']);
			$a->dealType = $dealType;
				
			$a->insert();
		}
	
	}
	
	
	
	
	
	function recacheCatDealType($dealType)
	{
		$sql="SELECT catId AS catId,  COUNT(*) AS quan  FROM `".mysql_real_escape_string(AdvItem::TBL)."`
		WHERE status='".intval(Status::code(Status::ACTIVE)->num)."'
		AND dealTypeId='".intval($dealType->num)."'
		GROUP BY catId ";
		//vd($sql);
		$qr = DB::query($sql);
		echo mysql_error();
	
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
		{
			$a = new AdvQuan();
			$a->cityId = $cityId;
			$a->quan = intval($next['quan']);
			$a->catId = strPrepare($next['catId']);
			$a->dealType = $dealType;
	
			$a->insert();
		}
	
	}
	
	
	
	
	function recacheCat()
	{
		$sql="SELECT catId AS catId,  COUNT(*) AS quan  FROM `".mysql_real_escape_string(AdvItem::TBL)."`
		WHERE status='".intval(Status::code(Status::ACTIVE)->num)."'
		GROUP BY catId ";
		//vd($sql);
		$qr = DB::query($sql);
		echo mysql_error();
	
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
		{
			$a = new AdvQuan();
			$a->cityId = $cityId;
			$a->quan = intval($next['quan']);
			$a->catId = strPrepare($next['catId']);
			$a->dealType = $dealType;
	
			$a->insert();
		}
	
	}
	
	
	
	
	
	
	function deleteByCityId($cityId)
	{
		$sql="DELETE FROM `".mysql_real_escape_string(self::TBL)."` WHERE cityId='".intval($cityId)."'";
		DB::query($sql);
		echo mysql_error();
	}
	
	
}
?>