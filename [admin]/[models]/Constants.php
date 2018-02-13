<?php 
class Constants{
	
	const TBL = 'slonne__constants';
	
	var   $id
		, $name
		, $valueJson
		, $value;
		
	function getList()
	{
		$sql = "SELECT * FROM `".mysql_real_escape_string(self::TBL)."` ORDER BY id";
		$qr=DB::query($sql);
		echo mysql_error();
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
		{
			$res[] = self::init($next);
		}
		
		return $res;
	}
	
	
	
	function init($arr)
	{
		$m = new self();
		$m->id = $arr['id'];
		$m->name = $arr['name'];
		$m->valueJson = $arr['value'];
		$m->value = json_decode($m->valueJson, true);
		
        return $m;
	}
	
	
	
	
	function initActionsArr()
	{
		//vd($this->actions);
		if(trim($this->actions))
		{
			$tmp = explode("\r\n", $this->actions);
			foreach($tmp as $key=>$val)
			{
				$tmp2 = explode('=', $val);
				$result[$tmp2[0]] = $tmp2[1];
			}
			$this->actionsArr = $result;
		}
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
	
	
	
	
	
	function insert()
	{
		$sql = "
		INSERT INTO `".self::TBL."` 
		SET name='".$this->name."'
		  , value='".strPrepare(json_encode($this->value))."'
		";
		vd($sql);
		
		$qr=DB::query($sql);
		
		
	}
	
	
	
	function update()
	{
		$sql = "
		UPDATE `".self::TBL."` 
		SET name='".$this->name."'
		  , value='".strPrepare(json_encode($this->value))."'
		WHERE id=".intval($this->id)."
		";
		vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
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
	
	
	
	
	
	function getByName($name, $selfId)
	{
		$sql = "SELECT * FROM `".self::TBL."` WHERE name = '".$name."' ".(intval($selfId) ? " AND id!=".intval($selfId)."" : "")."";
		$qr=DB::query($sql);
		echo mysql_error();
		if($next = mysql_fetch_array($qr, MYSQL_ASSOC))
			return self::init($next);
	}
	
	
	
	
	
	#	инициализация констант 
	function assemble()
	{
		global $_CONST, $_CONFIG;
		$list = self::getList();
		
		foreach($list as $const)
			$_CONST[$const->name] = $const->value[LANG];
	}
	
	
	
}
?>