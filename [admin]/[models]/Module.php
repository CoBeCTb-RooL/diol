<?php 
class Module{
	
	const TBL = 'slonne__modules';
	
	var   $id
		, $pid
		, $idx
		, $active
		, $icon
		, $name
		, $path
		, $actions
		, $actionsArr;
		
	
	
	
	function getList($onlyActive, $pid)
	{
		$sql = "SELECT * FROM `".mysql_real_escape_string(self::TBL)."` WHERE 1 ".($onlyActive ? " AND active=1 ":"")." ".(isset($pid) ? "AND pid=".intval($pid) : "")." ORDER BY idx";
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
		$props = get_object_vars($m);
		
		foreach ($arr as $key => $value)
		{
            if(property_exists($m, $key ))
                $m->{$key} = $value;
        }
        $m->initActionsArr();
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
	
	
	
	
	
	function add($module)
	{
		if($module)
		{
			$sql = "
			INSERT INTO `".self::TBL."` 
			SET active='".$module->active."'
			, name='".$module->name."'
			, icon='".$module->icon."'
			, path='".$module->path."'
			, actions='".$module->actions."'
			, idx=99999
			";
			
			$qr=DB::query($sql);
			echo mysql_error();
		}
	}
	
	
	
	function update($module)
	{
		if($module)
		{
			$sql = "
			UPDATE `".self::TBL."` 
			SET active='".$module->active."'
			, name='".$module->name."'
			, icon='".$module->icon."'
			, path='".$module->path."'
			, actions='".$module->actions."'
			WHERE id=".intval($module->id)."
			";
			vd($sql);
			$qr=DB::query($sql);
			echo mysql_error();
		}
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
			DB::query($sql);
			echo mysql_error();
		}
	}
	
	
	
	function getModuleByPath($path)
	{
		$sql = "SELECT * FROM `".self::TBL."` WHERE path LIKE '".strPrepare($path)."%'";
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		$next = mysql_fetch_array($qr, MYSQL_ASSOC);
		return self::init($next);
	}
	
	
	
}
?>