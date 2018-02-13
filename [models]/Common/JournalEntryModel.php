<?php
class JournalEntry
{
	const TBL = 'journal';
	
	var   $id
		, $objectType
		, $objectId
		, $journalEntryType
		, $dateCreated
		, $comment
		, $userId
		, $adminId
		, $ip
		, $param1
		, $param2
		, $param3
	;
		
	
	
	
	
	function init($arr)
	{
		if($arr)
		{
			$u = new self;
			
			$u->id = $arr['id'];
			$u->objectType = Object::code($arr['objectType']);
			$u->objectId = $arr['objectId'];
			$u->journalEntryType = JournalEntryType::code($arr['journalEntryType']);
			$u->dateCreated = $arr['dateCreated'];
			$u->comment = $arr['comment'];
			$u->userId = $arr['userId'];
			$u->adminId = $arr['adminId'];
			$u->ip = $arr['ip'];
			$u->param1 = $arr['param1'];
			$u->param2 = $arr['param2'];
			$u->param3 = $arr['param3'];
			
			return $u;
		}
	}



	public function initAdmin($admins)
    {
        if(!$admins)
            $admins = Admin::getList();

        $this->admin = $admins[$this->adminId];
    }
	
	
	
	
	function getList($params)
	{
		$sql="SELECT * FROM `".strPrepare(self::TBL)."` WHERE 1 ";

        if($params['objectType'])
            $sq.=" AND ObjectType='".strPrepare($params['objectId']->code)."'";
        if($params['adminId'])
            $sql.=" AND adminId='".intval($params['adminId'])."'";
        if($params['objectType'])
            $sql.=" AND objectType='".strPrepare($params['objectType']->code)."'";
        if($params['journalEntryType'])
            $sql.=" AND journalEntryType='".strPrepare($params['journalEntryType']->code)."'";

		if($params['orderBy'])
			$sql.=" ORDER BY ".strPrepare($params['orderBy'])." ";
			
		if( ($from = intval($params['from']))>=0 && ($count = intval($params['count']))>0)
			$sql.=" LIMIT ".$from.", ".$count." ";
			
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
			$ret[] = self::init($next);
				
		return $ret;
	}
	
	
	
	
	
	function insert()
	{
		$sql = "
		INSERT INTO `".self::TBL."` SET
		dateCreated = NOW(),
		ip='".strPrepare($_SERVER['REMOTE_ADDR'])."',
		".$this->innerAlterSql()."
		";
		//vd($sql);
		DB::query($sql);
		echo mysql_error();
		//vd($sql);
	}
	
	
	
	
	function update()
	{
		$sql = "
		UPDATE `".self::TBL."` SET
		".$this->innerAlterSql()."
		WHERE id=".$this->id."
		";
		DB::query($sql);
		echo mysql_error();
		//vd($sql);
	}
	
	
	
	
	function innerAlterSql()
	{
		$str="
		  objectType = '".strPrepare($this->objectType->code)."'
		, objectId = '".strPrepare($this->objectId)."'
		, journalEntryType = '".strPrepare($this->journalEntryType->code)."'
		, comment = '".strPrepare($this->comment)."'
		, userId = '".intval($this->userId)."'
		, adminId = '".intval($this->adminId)."'
		, param1 = '".strPrepare($this->param1)."'
		, param2 = '".strPrepare($this->param2)."'
		, param3 = '".strPrepare($this->param3)."'
		
		";
		
		return $str;
	}
	
	
	
	
	
	function validate()
	{
		$errors = null;
		
		if(!$this->objectType->code)
			$errors[] = Slonne::setError('objectType', 'Ошибка ТИПА комментария!!');
		if(!$this->objectId)
			$errors[] = Slonne::setError('objectId', 'Ошибка id объекта.');
		
		return $errors;
	}
	
	
	
	
	
	
	
	
} 













?>