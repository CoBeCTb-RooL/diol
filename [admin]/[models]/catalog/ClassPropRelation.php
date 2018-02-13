<?php 
class ClassPropRelation{
	
	const TBL = 'cat__props_classes';
	
	
	
	// catId выпилить  потом надо 
	var   $classId
		, $propId
		;
		
	
	
	function getList()
	{
		$sql = "SELECT * FROM `".mysql_real_escape_string(self::TBL)."` WHERE 1 ";
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
		
		$m->classId = $arr['classId'];
		$m->propId = $arr['propId'];
		
        return $m;
	}
	
	
	

	
	function get($id)
	{
		
		if($id = intval($id))
		{
			$sql = "SELECT * FROM `".self::TBL."` WHERE id=".$id;
			//vd($sql);
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
		 `classId`='".intval($this->classId)."'
		, `propId`='".intval($this->propId)."'
		";
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
	}
	

	
	
	function delete()
	{
		$sql = "
		DELETE FROM `".self::TBL."` WHERE classId=".intval($this->classId)." AND propId=".intval($this->propId)."";
		DB::query($sql);
		echo mysql_error(); 
	
	}
	
	
	
	
	function deleteRelationsOfClass($classId)
	{
		if($classId = intval($classId))
		{
			$sql = "DELETE FROM `".self::TBL."` WHERE classId=".$classId."";
			DB::query($sql);
			echo mysql_error();
		}
	}
	
	
	
	
	
}
?>