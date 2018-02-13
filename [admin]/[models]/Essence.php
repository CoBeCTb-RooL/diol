<?php
class Essence2
{
	var 	  $id
			, $name
			, $code
			, $jointFields
			, $linear
			, $fields;
			
			
	const TBL = 'slonne__essences';
	
	
	#	маска для названия таблиц сущности
	static $elementsTblMask = 'entity__#ESSENCECODE#LANGPOSTFIX';
	static $blocksTblMask = 'entity__#ESSENCECODE_blocks#LANGPOSTFIX';
	
	
	function getList()
	{
		$sql="SELECT * FROM `".self::TBL."` ORDER BY id";
		$qr = DB::query($sql);
		echo mysql_error();
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
			$ret[] = self::init($next);
		
		return $ret;
	}
	
	
	
	function init($arr)
	{
		//vd($arr);
		$m = new self();
		
		$m->id = $arr['id'];
		$m->name = $arr['name'];
		$m->code = $arr['code'];
		$m->jointFields = $arr['jointFields'] ? true : false;
		$m->linear = $arr['linear'] ? true : false;
		
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
	
	

	
	function add($essence)
	{
		if($essence)
		{
			$sql = "
			INSERT INTO `".self::TBL."` 
			SET 
			  name='".$essence->name."'
			, code = '".$essence->code."'
			, jointFields= '".$essence->jointFields."'
			, `linear` = '".$essence->linear."'
			";
			//vd($sql);
			$qr=DB::query($sql);
			echo mysql_error();
			return mysql_insert_id();
		}
	}
	
	
	
	function update($essence)
	{
		if($essence)
		{
			$sql = "
			UPDATE `".self::TBL."` 
			SET 
			
			name='".$essence->name."'
			, code = '".$essence->code."'
			, jointFields= '".$essence->jiontFields."'
			, `linear` = '".$essence->linear."'
			
			WHERE id=".intval($essence->id)."
			";
			//vd($sql);
			$qr=DB::query($sql);
			echo mysql_error();
		}
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
	
			$tmp=str_replace('#ESSENCECODE', $essenceCode, str_replace('#LANGPOSTFIX', ($lang!=$_CONFIG['default_lang']->code ? '_'.$_CONFIG['langs'][$lang]->code : ''), $mask));
			//vd($lang);
			//vd($tmp);
	
			return $tmp;
		}
	}
	
	
	
	
	
	
	function createTables()
	{
		global $_CONFIG;
		foreach($_CONFIG['langs'] as $lang)
		{
			Essence2::createTable($this->code, Entity2::TYPE_ELEMENTS, $lang->code);
			if(!($this->jointFields || $this->linear))
				Essence2::createTable($this->code, Entity2::TYPE_BLOCKS, $lang->code);
		}
	}
	
	
	
	
	
	function createTable($essenceCode, $type, $lang)
	{
		if($essenceCode && $type && $lang)
		{
			$tbl = strPrepare(Essence2::getTblName($essenceCode, $type, $lang));
			#	проверка - есть ли уже такая таблица
			$sql="SELECT * FROM `".$tbl."`";
			$qr=mysql_query($sql);
			if($e=mysql_error())
			{
				#	надо создавать!
				$sql="
					CREATE TABLE `".$tbl."` (
					`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
					`pid` INT( 11 ) NOT NULL ,
					`idx` INT( 11 ) NOT NULL ,
					`active` INT( 1 ) NOT NULL ,
					`untouchable` INT( 1 ) NOT NULL ,
					PRIMARY KEY ( `id` )
					);";
				//vd($sql);
				mysql_query($sql);
				echo mysql_error();
			}
		}
	}
	
	
	
	
	function dropTables()
	{
		global $_CONFIG;
		
		foreach($_CONFIG['langs'] as $lang)
		{
			Essence2::dropTable($this->code, Entity2::TYPE_ELEMENTS, $lang->code);
			if( !($this->jointFields || $this->linear) )
				Essence2::dropTable($this->code, Entity2::TYPE_BLOCKS, $lang->code);
		}
	}
	
	
	
	
	
	
	function dropTable($essenceCode, $type, $lang)
	{
		if($essenceCode && $type && $lang)
		{
			$tbl = strPrepare(Essence2::getTblName($essenceCode, $type, $lang));
			#	надо создавать!
			$sql="DROP TABLE `".$tbl."`";
			//vd($sql);
			mysql_query($sql);
			echo mysql_error();
			
		}
	}
	
	
	
	
	
	
	
	function initFields()
	{
		$this->fields[Entity2::TYPE_ELEMENTS] = Field2::getList($this->id, Entity2::TYPE_ELEMENTS);
		if(! ($this->linear || $this->jointFields) )
			$this->fields[Entity2::TYPE_BLOCKS] = Field2::getList($this->id, Entity2::TYPE_BLOCKS);
	}
	
	
	
	
	
	
	
} 
?>