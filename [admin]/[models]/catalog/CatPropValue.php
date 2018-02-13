<?php 
class CatPropValue{
	
	const TBL = 'cat__prop_values';
	
	var   $propId
		, $propCode
		, $itemId
		, $catTypeCode
		, $value
		, $lang
		;
		
	
	
	
	function getListByItemId($itemId)
	{
		if($itemId = intval($itemId))
		{
			$sql = "SELECT * FROM `".mysql_real_escape_string(self::TBL)."` WHERE itemId=".$itemId." ORDER BY propId";
			$qr=DB::query($sql);
			echo mysql_error();
			while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
			{
				#	для множественных списков 
				$res[] = self::init($next);
			}
		}
		return $res;
	}
	
	
	
	function init($arr)
	{
		$m = new self();
		
		//$m->id = $arr['id'];
		$m->propId = $arr['propId'];
		$m->propCode = $arr['propCode'];
		$m->itemId = $arr['itemId'];
		$m->catTypeCode = $arr['catTypeCode'];
		$m->value = $arr['value'];
		$m->lang = $arr['lang'];
		
        return $m;
	}
	
	
	

	
	
	function getByItemIdAndPropId($itemId, $propId, $lang)
	{
		$lang = $lang ? $lang : LANG;
		
		if($itemId && $propId)
		{
			$sql = "SELECT * FROM `".self::TBL."` WHERE itemId = ".intval($itemId)." AND propId=".intval($propId);
			vd($sql);
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
		SET 
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
		WHERE itemId=".intval($this->itemId)." AND propId='".strPrepare($this->propId)."'
		";
		vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
	}
	
	
	
	
	function alterSql()
	{
		$str.="
		  `propId`='".intval($this->propId)."'
		, `propCode`='".strPrepare($this->propCode)."'
		, `itemId`='".intval($this->itemId)."'
		, `catTypeCode`='".strPrepare($this->catTypeCode)."'
		, `value`='".strPrepare($this->value)."'
		, `lang`='".strPrepare($this->lang)."'
		";
		
		return $str;
	}
	
	
	
	
	function deleteByItemIdAndPropId($itemId, $propId)
	{
		if($itemId = intval($itemId))
		{
			$sql = "
			DELETE FROM `".self::TBL."` WHERE itemId=".$itemId." AND propId=".$propId;
			DB::query($sql);
			echo mysql_error(); 
		}
	}
	
	
	
	
	
	
	
	function saveMultipleSelectOptionsByItemAndProp($item, $prop, $optsArr, $lang)
	{
		self::deleteByItemIdAndPropId($item->id, $prop->id);
		
		foreach($optsArr as $key=>$val)
		{
			$propValue = new CatPropValue();
							
			$propValue->propId = $prop->id;
			$propValue->propCode = $prop->code;
			$propValue->itemId = $item->id;
			$propValue->catTypeCode = $item->cat->catTypeCode; 
			$propValue->lang = $lang;
			$propValue->value = $val;
			
			$propValue->insert();
		}
	}
	
	
	
	# 	для фильтров, чтобы генерить additionalClause
	function getItemIdSqlByPropCodeAndValue($propId, $value)
	{
		$sql="SELECT itemId FROM `".self::TBL."` WHERE propCode='".strPrepare($propId)."' AND value='".strPrepare($value)."'";
		return $sql;
	}
	
	
	
	
}
?>