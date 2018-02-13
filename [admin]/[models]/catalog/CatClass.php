<?php
class CatClass
{
	var 	  $id
			, $active
			, $name
			, $props
			;
			
			
	const TBL = 'cat__classes';
	
	
	
	function getList($activeOnly=false, $params)
	{
		$sql="SELECT * FROM `".self::TBL."` WHERE 1 ".($activeOnly ? " AND active=1 " : "")." ".($params['additionalClause'] ? strPrepare($params['additionalClause']) : '')." ORDER BY id";
		//vd($sql);
		$qr = DB::query($sql);
		echo mysql_error();
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
		{
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
		$m->active = $arr['active'] ? 1 : 0;
		$m->name = $arr['name'];
		//vd($m);
        return $m;
	}
	
	
	
	function get($id)
	{		
		if($id = intval($id))
		{
			$sql = "SELECT * FROM `".self::TBL."` WHERE id='".$id."' ";
			$qr=DB::query($sql);
			echo mysql_error();
			$next = mysql_fetch_array($qr, MYSQL_ASSOC);

			if($next)
				return self::init($next);
		}
	}
	
	
	
	
	
	
	
	function getByName($name)
	{		
		if($name)
		{
			$sql = "SELECT * FROM `".self::TBL."` WHERE name='".strPrepare($name)."' ";
			//vd($sql);
			$qr=DB::query($sql);
			echo mysql_error();
			$next = mysql_fetch_array($qr, MYSQL_ASSOC);
			
			if($next)
				return self::init($next);
		}
	}
	
	
	
	
	

	
	function insert()
	{
		$sql = "
		INSERT INTO `".self::TBL."` 
		SET 
		  name='".strPrepare($this->name)."'
		, active = 1
		
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
		name='".strPrepare($this->name)."'
		, active = ".($this->active ? 1 : 0)."
		
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
	
	
	
	
	function validate()
	{
		$problems = array();
		
		if(!$this->name) $problems[] = Slonne::setError('name', 'Введите название!');
		
		#	проверка на сществование подобных полей
		if(!count($problems))
		{
			$byName = CatClass::getByName($this->name);
			if( $byName && $byName->id != $this->id)
				$problems[] = Slonne::setError('name', 'Класс с таким названием уже есть!');
		}
		
		return $problems;
	}
	
	
	
	
	function initProps($activeOnly=true)
	{
		$this->props = Prop::getPropsOfClass($this->id, $activeOnly);
		foreach($this->props as $key=>$prop)
			$prop->initOptions(true);
	}
	
	
	
	
	
} 
?>