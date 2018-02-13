<?php
class Task{
	public $id;
	public $title;
	public $descr;
	public $groupId;
	public $status;
	public $dateCreated;
	public $creatorId;
	public $priority;
	
	const TBL = 'tasks';
	
	
	function __construct()
	{
		
	}
	
	
	
	function init($arr)
	{
		$m = new self();
	
		$m->id = $arr['id'];
		$m->groupId = $arr['groupId'];
		$m->title = $arr['title'];
		$m->descr = $arr['descr'];
		$m->status = Status::code($arr['status']);
		$m->dateCreated = $arr['dateCreated'];
		$m->creatorId = $arr['creatorId'];
		$m->priority = TaskPriority::code($arr['priority']);
	
		return $m;
	}
	
	
	
	function getList($groupId, $statuses, $orderBy = ' id ASC ')
	{
		$sql = "SELECT * FROM `".self::TBL."` WHERE 1 ".($groupId?" AND groupId='".intval($groupId)."' ":"")." ";
		if($groupId === null)
			$sql .= " AND groupId IS NULL ";
		elseif($groupId)
			$sql .= " AND groupId='".intval($groupId)."' ";
			
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
		  `title`='".strPrepare($this->title)."'
		, `descr`='".strPrepare($this->descr)."'
		, `groupId`=". (intval($this->groupId)!=0 ? intval($this->groupId) : 'null') ."
		, `creatorId`='".intval($this->creatorId)."'
		, `status`='".strPrepare($this->status->code)."'
		, `priority`='".intval($this->priority->code)."'
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
		if(!trim($this->title))
			$problems[] = Slonne::setError('title', 'Введите Задачу!');

		return $problems;
	}
	
	
	
	
	
	
}