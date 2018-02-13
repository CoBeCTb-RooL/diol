<?php
class CatType
{
	var 	  $id
			, $name
			, $code;
			
			
	const TBL = 'cat__types';
	
	
	#	маска для названия таблиц сущности
	/*static $elementsTblMask = 'entity__#ESSENCECODE#LANGPOSTFIX';
	static $blocksTblMask = 'entity__#ESSENCECODE_blocks#LANGPOSTFIX';*/
	
	
	function getList()
	{
		$sql="SELECT * FROM `".self::TBL."` ORDER BY id";
		//vd($sql);
		$qr = DB::query($sql);
		echo mysql_error();
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
		{
			//vd($next);
			$ret[] = self::init($next);
		}		
		//vd($ret);
		return $ret;
	}
	
	
	
	function init($arr)
	{
		//vd($arr);
		$m = new self();
		
		$m->id = $arr['id'];
		$m->name = $arr['name'];
		$m->code = $arr['code'];
		//vd($m);
        return $m;
	}
	
	
	
	function get($id)
	{
		//vd($id);
		if(gettype($id) == 'array')
		{
			$ok = true;
			$clause = $id['clause'];
		}
		else
		{
			if(is_numeric($id))
			{
				$clause = " AND id = ".intval($id);
				$ok = true;
				
			}
			else
			{
				$clause = " AND code = '".strPrepare($id)."'";
				$ok = true;
			}
		}
		
		if($ok)
		{
			$sql = "SELECT * FROM `".self::TBL."` WHERE 1 ".($clause)."";
			//vd(htmlspecialchars($sql));
			$qr=DB::query($sql);
			echo mysql_error();
			$next = mysql_fetch_array($qr, MYSQL_ASSOC);
			//vd($next);
			if($next)
				return self::init($next);
		}
	}
	
	
	
	
	function getByCode($code)
	{
		if($code = strPrepare($code))
		{
			$sql = "SELECT * FROM `".self::TBL."` WHERE code = '".$code."'";
			
			$qr=DB::query($sql);
			echo mysql_error();
			if($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				return self::init($next);
		}
	}
	
	
	function getByName($name)
	{
		if($name = strPrepare($name))
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
			SET 
			  name='".$this->name."'
			, code = '".$this->code."'
			
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
		name='".$this->name."'
		, code = '".$this->code."'
		
		WHERE id=".intval($this->id)."
		";
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
	}
	
	
	
	
	
	function delete($id)
	{
		global $_CONFIG;
		
		if($id = intval($id))
		{
			$sql = "
			DELETE FROM `".self::TBL."` WHERE id=".$id;
			DB::query($sql);
			echo mysql_error(); 
		}
		
		
	}
	
	
	
	
	function getTblName($essenceCode, $type, $lang)
	{
		global $_CONFIG;
		//vd($essenceCode);
		//vd($type);
		//vd($lang);
		if(trim($essenceCode) && trim($type) && trim($lang) && ($type == Entity2::TYPE_BLOCKS || $type == Entity2::TYPE_ELEMENTS))
		{
			$mask=Essence2::$elementsTblMask;
			if($type==Entity2::TYPE_BLOCKS)
				$mask=Essence2::$blocksTblMask;
	
			$tmp=str_replace('#ESSENCECODE', $essenceCode, str_replace('#LANGPOSTFIX', $_CONFIG['LANGS'][$lang]['postfix'], $mask));
	
			return $tmp;
		}
	}
	
	
	
	
	
	
	
	
	
	
	
} 
?>