<?php
class Comment
{
	const TBL = 'comments';
	
	var   $id
		, $status
		, $objectType
		, $pid
		, $dateCreated
		, $text
		, $userId
		, $name
		, $email
		
		, $user
	;
		
	
	
	
	
	function init($arr)
	{
		if($arr)
		{
			$u = new self;
			
			$u->id = $arr['id'];
			$u->status = Status::num($arr['status']);
			$u->objectType = Object::code($arr['objectType']);
			$u->pid = $arr['pid'];
			$u->dateCreated = $arr['dateCreated'];
			$u->text = $arr['text'];
			$u->userId = $arr['userId'];
			$u->name = $arr['name'];
			$u->email= $arr['email'];
			
			return $u;
		}
	}
	
	
	
	function get($id, $status)
	{
		if($id = intval($id))
		{
			$sql="SELECT * FROM `".strPrepare(self::TBL)."` WHERE id=".$id." ".($status ? " AND status=".intval($status->num)." " : "")."";
			$qr=DB::query($sql);
			echo mysql_error();
			if($attrs = mysql_fetch_array($qr, MYSQL_ASSOC))
				$user = self::init($attrs);
			
			return $user;
		}
	}
	
	
	
	
	function getList($params)
	{
		$sql="SELECT * FROM `".strPrepare(self::TBL)."` WHERE 1 ".self::getListInnerSql($params);
		
		if($orderBy)
			$sql.=" ORDER BY `".mysql_real_escape_string($orderBy)."` ".($desc?" DESC ":"")."";
			
		if( ($from = intval($from))>=0 && ($count = intval($count))>0)
			$sql.=" LIMIT ".$from.", ".$count." ";
			
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
			$ret[] = self::init($next);
				
		return $ret;
	}
	
	
	
	function getCount($params)
	{
		$sql="SELECT COUNT(*) FROM `".strPrepare(self::TBL)."` WHERE 1 ".self::getListInnerSql($params);
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		$next = mysql_fetch_array($qr);
		return $next[0];
	}
	
	
	function getListInnerSql($params)
	{
		//vd($params);
		$sql = "";
		if($params['pid'])
			$sql.=" AND pid=".intval($params['pid'])." ";
		if($params['status'])
			$sql.=" AND (status=".intval($params['status']->num).") ".($params['showModerationCommentsForUserId'] ? " OR (status='".strPrepare(Status::code(Status::MODERATION)->num)."' AND userId=".intval($params['showModerationCommentsForUserId']).") " : "")." ";
		if($params['objectType'])
			$sql.=" AND objectType='".strPrepare($params['objectType'])."' ";
		
		//vd($sql);
			
		return $sql;
	}
	
	
	
	function insert()
	{
		$sql = "
		INSERT INTO `".self::TBL."` SET
		dateCreated = NOW(),
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
		  status='".intval($this->status->num)."'		
		, objectType = '".strPrepare($this->objectType->code)."'
		, pid = '".strPrepare($this->pid)."'
		, text = '".strPrepare($this->text)."'
		, userId = '".intval($this->userId)."'
		, name = '".strPrepare($this->name)."'
		, email = '".strPrepare($this->email)."'
		
		";
		
		return $str;
	}
	
	
	
	
	
	function validate()
	{
		$errors = null;
		
		if(!$this->objectType->code)
			$errors[] = Slonne::setError('objectType', 'Ошибка ТИПА комментария!!');
		if(!$this->text)
			$errors[] = Slonne::setError('text', 'Пожалуйста, введите текст комментария.');
		
		if(!$this->pid)
			$errors[] = Slonne::setError('pid', 'Ошибка родителя комментария!!');
		/*if (!filter_var($this->email, FILTER_VALIDATE_EMAIL))
			$errors[] = Slonne::setError('email', 'укажите корректный e-mail.');*/
		
		return $errors;
	}
	
	
	
	
	function setStatus($status)
	{
		if($status)
		{
			$sql = "UPDATE `".self::TBL."` SET status='".intval($status->num)."' WHERE id=".$this->id;
			//vd($sql);
			DB::query($sql);
			echo mysql_error();
		}
	}
	
	
} 













?>