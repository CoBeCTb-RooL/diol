<?php
class User 
{
	const TBL = 'users';
	const CABINET_CONTROLLER = 'cabinet';
	
	var   $id
		, $status
		, $surname
		, $name
		, $fathername
		, $fullName
		, $password
		, $birthdate
		, $email
		, $phone
		, $cityId
		, $salt
		, $registrationDate
		, $registrationIp
		, $lastActivity
		, $lastIp
		//, $sex
		
		, $city
	;
		
	
	
	
	
	function init($arr)
	{
		if($arr)
		{
			$u = new self;
			
			$u->id = $arr['id'];
			$u->surname = $arr['surname'];
			$u->name = $arr['name'];
			$u->fathername = $arr['fathername'];
			$u->birthdate = $arr['birthdate'];
			$u->email = $arr['email'];
			//$u->sex = Sex::num($arr['sex']);
			$u->phone= $arr['phone'];
			$u->registrationDate = $arr['registrationDate'];
			$u->registrationIp = $arr['registrationIp'];
			$u->lastIp = $arr['ip'];
			$u->password = $arr['password'];
			$u->salt = $arr['salt'];
			$u->cityId = $arr['cityId'];
			$u->lastActivity = $arr['lastActivity'];
			
			$u->fullName = trim($u->surname.' '.$u->name.' '.$u->fathername);
			
			$u->status = Status::num($arr['status']);
			
			return $u;
		}
	}
	
	
	
	function get($id, $status)
	{
		if($id = intval($id))
		{
			$sql="SELECT * FROM users WHERE id=".$id." ".($status ? " AND status=".intval($status->num)." " : "")."";
			$qr=DB::query($sql);
			echo mysql_error();
			if($attrs = mysql_fetch_array($qr, MYSQL_ASSOC))
				$user = User::init($attrs);
			
			return $user;
		}
	}
	
	
	
	
	function getList($params)
	{
		$sql="SELECT * FROM users WHERE 1 ".self::getListInnerSql($params);
		
		if($params['orderBy'])
			$sql.=" ORDER BY `".mysql_real_escape_string($params['orderBy'])."` ".($params['desc']?" DESC ":"")."";
			
		if( ($params['from'] = intval($params['from']))>=0 && ($params['count'] = intval($params['count']))>0)
			$sql.=" LIMIT ".$params['from'].", ".$params['count']." ";
		
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
			$ret[] = User::init($next);
				
		return $ret;
	}
	
	
	
	function getCount($params)
	{
		$sql="SELECT COUNT(*) FROM users WHERE 1 ".self::getListInnerSql($params);
		
		$qr=DB::query($sql);
		echo mysql_error();
		$next = mysql_fetch_array($qr);
		return $next[0];
	}
	
	
	function getByIdsList($ids, $status)
	{
		if($ids)
		{
			foreach($ids as $key=>$val)
				$ids[$key] = intval($val);
			
			$sql="SELECT * FROM users WHERE 1  AND id IN (".join(', ', $ids).") ";
			if($status)
				$sql.=" AND status='".intval($status->num)."' ";
			//vd($sql);
			$qr=DB::query($sql);
			echo mysql_error();
			while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				$ret[$next['id']] = User::init($next);
		}
		return $ret;
	}
	
	
	function getListInnerSql($params)
	{
		//vd($params);
		$sql = "";
		if($params['status'])
			$sql.=" AND status=".intval($params['status']->num)." ";
		if($params['email'])
			$sql.=" AND email='".strPrepare($params['email'])."' ";
		if($params['emailLike'])
			$sql.=" AND email LIKE '%".strPrepare($params['emailLike'])."%' ";
		//vd($sql);
			
		return $sql;
	}
	
	
	
	function insert()
	{
		$sql = "
		INSERT INTO `".self::TBL."` SET
		  registrationDate = NOW()
		, registrationIp = '".strPrepare($_SERVER['REMOTE_ADDR'])."',		
		".$this->innerAlterSql()."
		";
		vd($sql);
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
		, surname = '".strPrepare($this->surname)."'
		, name = '".strPrepare($this->name)."'
		, fathername = '".strPrepare($this->fathername)."'
		, password = '".strPrepare($this->password)."'
		, birthdate = '".strPrepare($this->birthdate)."'
		, email = '".strPrepare($this->email)."'
		, phone= '".strPrepare($this->phone)."'
		, cityId= '".strPrepare($this->cityId)."'
		
		, lastActivity = NOW()
		, lastIp = '".strPrepare($_SERVER['REMOTE_ADDR'])."'
		/*, sex = '".intval($this->sex->num)."'*/
		, salt= '".strPrepare($this->salt)."'";
		
		return $str;
	}
	
	
	
	
	function getByEmailAndPassword($email, $password, $status)
	{
		$sql="SELECT * FROM `".self::TBL."` WHERE email='".mysql_real_escape_string($email)."' AND password = '".mysql_real_escape_string($password)."' ".($status ? " AND status='".intval($status->num)."' " : "")."";
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		$usr=mysql_fetch_array($qr, MYSQL_ASSOC);
		//vd($usr);
		return User::init($usr);
	}
	
	
	
	function getByEmail($email, $status)
	{
		$sql="SELECT * FROM `".self::TBL."` WHERE email='".strPrepare($email)."' AND status='".intval($status->num)."'";
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		$usr=mysql_fetch_array($qr, MYSQL_ASSOC);
		//vd($usr);
		return User::init($usr);
	}
	
	
	
	
	
	
	
	
	
	
	
	function getBySalt($salt, $status)
	{
		if($salt = strPrepare(trim($salt)))
		{
			$sql="SELECT * FROM `".self::TBL."` WHERE salt='".$salt."'";
			if($status)
				$sql.="  AND status='".intval($status->num)."'";
			
			$qr=DB::query($sql);
			echo mysql_error();
			if($attrs = mysql_fetch_array($qr, MYSQL_ASSOC))
				$user = User::init($attrs);
			return $user;
		}
	}
	
	
	
	
	
	function validate()
	{
		$errors = null;
		
		/*if(!$this->surname)
			$errors[] = Slonne::setError('surname', 'Пожалуйста, введите фамилию.');*/
		if(!$this->name)
			$errors[] = new Error('Пожалуйста, введите имя.', 'name');
		if(!$this->phone)
			$errors[] = new Error('Пожалуйста, введите контакты.', 'phone');
		
		if(!$this->email)
			$errors[] = new Error('Пожалуйста, введите e-mail.', 'email');
		if (!filter_var($this->email, FILTER_VALIDATE_EMAIL))
			$errors[] = new Error('укажите корректный e-mail.', 'email');
		
		if(!$this->cityId)
			$errors[] = new Error('Пожалуйста, укажите город.', 'city');
		
		# 	постор emailа
		$tmp = self::getByEmail($this->email, Status::code(Status::ACTIVE));
		//vd($tmp);
		if($tmp && $tmp->id!=$this->id)
			$errors[] = new Error('Этот E-mail уже занят.', 'email');
			
		# 	пароли
		if(!$this->id)
		{
			if(!$this->password)
				$errors[] = new Error('Пожалуйста, введите пароль.', 'pass');
			if(!$this->password2)
				$errors[] = new Error('Пожалуйста, введите подтверждение пароля.', 'pass2');
			if($this->password != $this->password2 && $this->password != '')
			{
				$errors[] = new Error('Пароли не совпадают.', 'pass');
				$errors[] = new Error('', 'pass2');
			}
		}
		
		return $errors;
	}
	
	
	
	
	
	function initCity($cities)
	{
		if(!$cities)
			$this->city = City::get($this->cityId);
		
		else $this->city = $cities[$this->cityId];
	}
	
	
	
	function setStatus($status)
	{
		if($status)
		{
			$sql="UPDATE `".self::TBL."` SET status='".intval($status->num)."' WHERE id='".intval($this->id)."'";
			DB::query($sql);
			echo mysql_error();
		}
	}
	
	
	
	
	function encryptPassword($str)
	{
		return md5(md5($str.'_i374V58w74H8s7dtV8tw826Qq'));
	}
	
	
} 













?>