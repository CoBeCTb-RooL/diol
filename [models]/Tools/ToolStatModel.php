<?php 
class ToolStat{
	
	const TBL = 'tools_stats';
	
	public $id;
	public $toolType;
	public $dateStart;
	public $dateEnd;
	public $execType;
	public $result;
	public $text;
	public $ip;
	public $url;
	public $param1;
	
	
	
		
	function init($arr)
	{
		$m = new self();
		
		$m->id = $arr['id'];
		$m->toolType = ToolType::code($arr['toolType']);
		$m->dateStart = $arr['dateStart'];
		$m->dateEnd = $arr['dateEnd'];
		$m->execType = ToolExecType::code($arr['execType']);
		$m->result = ToolResult::code($arr['result']);
		$m->text = $arr['text'];
		$m->ip = $arr['ip'];
		$m->url = $arr['url'];
		$m->param1 = $arr['param1'];
		
		return $m;
	}
	
	
	function getList($params)
	{
		//vd($params);
		$sql="SELECT * FROM `".strPrepare(self::TBL)."` WHERE 1 ".self::getListInnerSql($params);
		
		if($params['orderBy'])
			$sql.=" ORDER BY ".mysql_real_escape_string($params['orderBy'])." ";
			
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
		if($params['toolType'])
			$sql.=" AND toolType='".strPrepare($params['toolType']->code)."' ";
		if($params['execType'])
			$sql.=" AND execType='".strPrepare($params['execType']->code)."' ";
		if($params['result'])
			$sql.=" AND result='".strPrepare($params['result']->code)."' ";
		
		if($params['dateStart'])
			$sql.=" AND DATE(dateStart)=DATE('".strPrepare($params['dateStart'])."') ";
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
		SET 
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
		  `toolType` = '".strPrepare($this->toolType->code)."'
		, `dateStart`='".strPrepare($this->dateStart)."'
		, `dateEnd`='".strPrepare($this->dateEnd)."'
		, `execType`='".strPrepare($this->execType->code)."'
		, `result`='".strPrepare($this->result->code)."'
		, `text`='".strPrepare($this->text)."'
		, `ip`='".strPrepare($this->ip)."'
		, `url`='".strPrepare($this->url)."'
		, `param1`='".strPrepare($this->param1)."'
		
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
		
		return $problems;
	}
	
	
	
	
	
	
	function begin($toolType, $execType)
	{
		$this->result = ToolResult::code(ToolResult::FAILED);
		$this->dateStart = date('Y-m-d H:i:s');
		$this->toolType = $toolType;
		$this->execType = $execType;
		$this->ip = $_SERVER['REMOTE_ADDR'];
		$this->url = $_SERVER['REQUEST_URI'];
		
		$this->id = $this->insert();
	}
	
	
	# 	обзая функция завершения статы
	function end($text)
	{
		$this->dateEnd = date('Y-m-d H:i:s');
		if(trim($text))
			$this->text = $text;
		$this->update();
	}
	
	
	function success($text)
	{
		$this->result = ToolResult::code(ToolResult::OK);
		$this->end($text);
	}
	function endWithErrors($text)
	{
		$this->result = ToolResult::code(ToolResult::ERRORS);
		$this->end($text);
	}
	function fail($text)
	{
		$this->result = ToolResult::code(ToolResult::FAILED);
		$this->end($text);
	}
	
	
	
	
	
	
	
}
?>