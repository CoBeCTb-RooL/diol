<?php

class Admin
{
	public $id;
	public $email;
	public $password;
	public $name;
	public $regTime;
	public $lastAuth;
	public $groupId;
	public $group;
	public $speciality;
	public $status;
	
			
	//const  ROLE_SITE_OVERVIEW  = 2 * 1;
			
	const TBL = 'slonne__admins';
	
	#	число попыток, после которых будет delay
	const AUTH_TRIES_LIMIT = 30;
	
	#	шаг в секундах между попытками авторизации 
	const SECONDS_DELAY_STEP = 2;
	
	#	клёч
	const PASS_KEY = 'vn9n(^%$VNY73nv7t(^B*vn38yv48vwh7t^&';
	
	
	function init($arr)
	{
		$m = new self();
	
		$m->id = $arr['id'];
		$m->email = $arr['email'];
		$m->password = $arr['password'];
		$m->name = $arr['name'];
		$m->regTime = $arr['regTime'];
		$m->lastAuth = $arr['lastAuth'];
		$m->groupId = $arr['groupId'];
		$m->speciality = $arr['speciality'];
		$m->status = Status::num($arr['status']);

		return $m;
	}
	
	
	
	
	
	function getList($status, $statusesNotIn)
	{
		$sql="SELECT * FROM `".self::TBL."` WHERE 1 ";
		if($status)
			$sql.=" AND status='".intval($status->num)."' ";
		if($statusesNotIn)
		{
			$sql.=" AND status NOT IN(-1 ";
			foreach($statusesNotIn as $s)
				$sql.=", ".intval($s->num)."";
			$sql.=") ";
		}
		$sql .= "ORDER BY id";
	//	vd($sql);
		$qr = DB::query($sql);
		echo mysql_error();
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
			$ret[$next['id']] = self::init($next);
		
		return $ret;
	}
	
	
	
	
	
	
	
	function get($id, $status)
	{
		if($id =intval($id))
		{
			$sql = "SELECT * FROM `".self::TBL."` WHERE id = ".$id." ";
			if($status)
				$sql .= " AND status=".intval($status->num)." ";
			//vd($sql);
			$qr=DB::query($sql);
			echo mysql_error();
			if($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				return self::init($next);
		}
	}
	
	
	function getByEmail($email)
	{
		if($email = strPrepare($email))
		{
			$sql = "SELECT * FROM `".self::TBL."` WHERE email = '".$email."' AND status!=".intval(Status::code(Status::DELETED)->num)."";
			$qr=DB::query($sql);
			echo mysql_error();
			if($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				return self::init($next);
		}
	}
	
	
	
	function getByEmailAndPassword($email, $password, $status)
	{
		if(($email = strPrepare($email)) && ($password = strPrepare($password)) )
		{
			$sql = "SELECT * FROM `".self::TBL."` WHERE email = '".$email."' AND password='".strPrepare(self::encryptPassword($password))."' ";
			if($status)
				$sql .= " AND status=".intval($status->num)." ";
			//vd($sql);
			$qr=DB::query($sql);
			echo mysql_error();
			if($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				return self::init($next);
		}
	}
	
	
	
	
	function encryptPassword($a)
	{
		//return $a;
		return md5($a.self::PASS_KEY);
	}
	
	
	function initGroup($status)
	{
		$this->group = AdminGroup::get($this->groupId, $status);
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
		return mysql_insert_id();
	}
	
	
	
	
	function update()
	{
		$sql = "
		UPDATE `".self::TBL."` SET
		".$this->innerAlterSql()."
		WHERE id=".$this->id."
		";
		//vd($sql);
		DB::query($sql);
		echo mysql_error();
		//vd($sql);
	}
	
	
	
	
	function innerAlterSql()
	{
		$str="
		  status='".intval($this->status->num)."'		
		, name = '".strPrepare($this->name)."'
		, groupId = '".strPrepare($this->groupId)."'
		, email = '".strPrepare($this->email)."'
		";
		
		return $str;
	}
	
	
	
	
	
	
	function isAdmin()
	{
		return $_SESSION['admin']['id'] ? true : false;
	}
	
	
	
	
	
	
	function setLastAuth()
	{
		$sql = "UPDATE `".strPrepare(self::TBL)."` SET lastAuth = NOW() WHERE id=".intval($this->id)." ";
		$qr=DB::query($sql);
		echo mysql_error();
	}
	
	
	
	function setPassword($password)
	{
		$sql = "UPDATE `".strPrepare(self::TBL)."` SET password = '".self::encryptPassword($password)."' WHERE id=".intval($this->id)." ";
		vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
	}
	
	
	
	
	
	function validate()
	{
		if(!trim($this->name))
			$errors[] = new Error('Введите имя!', 'name');
		if(!trim($this->email))
			$errors[] = new Error('Введите e-mail!', 'email');
		
		return $errors;
	}
	
	
	
	
	
	function hasPrivilege($moduleId, $action)
	{
		return true; 
		//vd($this->group->privilegesArr);
		/*if($action)
		{
			if($this->group->privilegesArr[$moduleId][$action])
				return true;
		}
		else
			if($this->group->privilegesArr[$moduleId])
				return true;
		return false;*/
	}
	
	
	
	
	/*
	function checkAndForbid($moduleId, $action)
	{
		//vd($action);
		if(!$this->hasPrivilege($moduleId, $action))
		{
			header('HTTP/1.0 403 Forbidden');
			die;
		}
	}*/
	
	
	function hasRole($role)
	{
		return ($this->group->role & $role) ? true : false;
	}




	function getActiveDoctors()
	{
		$ret = [];

		$tmp = self::getList();
		$doctors = [];
		foreach ($tmp as $v)
		{
			$v->initGroup();
			if($v->hasRole(Role::DOCTOR) && $v->status->code == Status::ACTIVE && $v->id!=7)
				$doctors[] = $v;
		}
		$ret = $doctors;

		return $ret;
	}
	
	
} 
?>