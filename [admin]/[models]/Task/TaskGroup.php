<?php
class TaskGroup{
	public $id;
	public $name;
	public $status;
	public $dateCreated;
	public $creatorId;
	
	public $tasks;
	
	const TBL = 'tasks__groups';
	
	
	function __construct()
	{
		
	}
	
	
	
	function init($arr)
	{
		$m = new self();
	
		$m->id = $arr['id'];
		$m->name = $arr['name'];
		$m->status = Status::code($arr['status']);
		$m->dateCreated = $arr['dateCreated'];
		$m->creatorId = $arr['creatorId'];

		return $m;
	}
	
	
	
	function getList($statuses, $statusNot, $orderBy)
	{
		$sql = "SELECT * FROM `".self::TBL."` WHERE 1  ";
		if($statuses)
		{
			$sql .= " AND status IN(-1";
			foreach($statuses as $s)
				$sql .= ", '".$s->code."'";
			$sql.=") ";
		}
		if($orderBy)
			$sql.=" ORDER BY ".strPrepare($orderBy)." ";
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
			$res[$next['id']] = self::init($next);

		return $res;
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
		SET dateCreated=NOW(),
		".$this->alterSql()."
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
		".$this->alterSql()."
		WHERE id=".intval($this->id)."
		";
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
	}
	
	
	
	
	function alterSql()
	{
		$str.="
		  `name`='".strPrepare($this->name)."'
		, `status`='".strPrepare($this->status->code)."'
		, `creatorId`='".intval($this->creatorId)."'
		";
	
		return $str;
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
	
	
	
	
	
	function validate()
	{
		if(!trim($this->name))
			$problems[] = Slonne::setError('name', 'Введите название группы задач!');

		return $problems;
	}
	
	
	
	
	
	
}