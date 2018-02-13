<?php
class AdvClass
{
	const TBL = 'adv__classes';
	
	
	var 	  $id
			, $statusId
			, $name
			, $dateCreated
			
			, $status
			, $props
			;
			
			
	
	
	
	
	function getList($status)
	{
		$sql="SELECT * FROM `".self::TBL."` WHERE 1 ".($status ? " AND status=".intval($status->num)." " : "")." ORDER BY id";
		$qr = DB::query($sql);
		echo mysql_error();
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
			$ret[] = self::init($next);
		//vd($ret);
		return $ret;
	}
	
	
	
	function init($arr)
	{
		//vd($arr);
		$m = new self();
		
		$m->id = $arr['id'];
		$m->statusId = $arr['status'];
		$m->name = $arr['name'];
		$m->dateCreated = $arr['dateCreated'];
		
		$m->status = Status::num($m->statusId);
		//vd($m);
        return $m;
	}
	
	
	
	function get($id, $status)
	{		
		if($id = intval($id))
		{
			$sql = "SELECT * FROM `".self::TBL."` WHERE id='".$id."' ".($status ? " AND status='".intval($status->num)."' " : "")." ";
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
		  dateCreated = NOW()
		, name='".strPrepare($this->name)."'
		, status=".intval($this->status->num)."
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
		, status=".intval($this->status->num)."
		
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
		$problems = null;
		
		if(!$this->name) $problems[] = Slonne::setError('name', 'Введите название!');
		
		#	проверка на сществование подобных полей
		if(!count($problems))
		{
			$byName = AdvClass::getByName($this->name);
			if( $byName && $byName->id != $this->id)
				$problems[] = Slonne::setError('name', 'Класс с таким названием уже есть!');
		}
		
		return $problems;
	}
	
	
	
	
	function initProps($status)
	{
		$this->props = AdvProp::getList($this->id, $status);
		foreach($this->props as $key=>$prop)
			$prop->initOptions($status);
	}
	
	
	
	
	
} 
?>