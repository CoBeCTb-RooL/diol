<?php 
class Suggestion{
	
	const TBL = 'suggestions';
	
	
	public $id;
	public $dateCreated;
	public $name;
	public $email;
	public $phone;
	public $text;
	public $answer;
	public $status;
	public $userId;
	public $suggestionType; 
	
	
	
	
		
	function init($arr)
	{
		$m = new self();
		
		$m->id = $arr['id'];
		$m->dateCreated = $arr['dateCreated'];
		$m->status = Status::num($arr['status']);
		$m->name = $arr['name'];
		$m->email = $arr['email'];
		$m->phone = $arr['phone'];
		$m->text = $arr['text'];
		$m->answer = $arr['answer'];
		$m->userId = $arr['userId'];
		$m->suggestionType = SuggestionType::code($arr['suggestionType']);
		
		
		return $m;
	}
	
	
	function getList($params)
	{
		//vd($params);
		$sql="SELECT * FROM `".strPrepare(self::TBL)."` WHERE 1 ".self::getListInnerSql($params);
		
		if($params['orderBy'])
			$sql.=" ORDER BY `".mysql_real_escape_string($params['orderBy'])."` ".($params['desc']?" DESC ":"")."";
			
		if( ($params['from'] = intval($params['from']))>=0 && ($params['count'] = intval($params['count']))>0)
			$sql.=" LIMIT ".$params['from'].", ".$params['count']." ";
		
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
		if($params['status'])
			$sql.=" AND status=".intval($params['status']->num)." ";
		if($params['suggestionType'])
			$sql.=" AND suggestionType='".strPrepare($params['suggestionType']->code)."' ";
		if($params['userId'])
			$sql.=" AND userId='".strPrepare($params['userId'])."' ";
		//vd($sql);
			
		return $sql;
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
		  `status` = '".intval($this->status->num)."'
		, `name`='".strPrepare($this->name)."'
		, `email`='".strPrepare($this->email)."'
		, `phone`='".strPrepare($this->phone)."'
		, `text`='".strPrepare($this->text)."'
		, `answer`='".strPrepare($this->answer)."'
		, `userId`=".(intval($this->userId) ? intval($this->userId) : 'NULL')."
		, `suggestionType`='".strPrepare($this->suggestionType->code)."'
		
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
		/*if(!trim($this->name))
			$problems[] = Slonne::setError('name', 'Введите название!');*/
		if(!trim($this->text))
			$problems[] = Slonne::setError('text', 'Введите Ваше сообщение!');
		if(!$this->suggestionType->code)
			$problems[] = Slonne::setError('---', 'Ошибка типа! Обратитесь к разработчикам..');
		
		
		return $problems;
	}
	
	
	
	
	
}
?>