<?php 
class CatUnit{
	
	const TBL = 'cat__units';
	
	var   $id
		, $name
		;
		
	
	
	
	function getList()
	{
		$sql = "SELECT * FROM `".mysql_real_escape_string(self::TBL)."` ORDER BY name";
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
		$m->name= $arr['name'];
		
        return $m;
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
		SET name='".$module->name."'
		";
		
		$qr=DB::query($sql);
		echo mysql_error();
	}
	
	
	
	function update()
	{
		$sql = "
		UPDATE `".self::TBL."` 
		SET name='".$module->name."'
		WHERE id=".intval($module->id)."
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
	
	
	
	
	
	
}
?>