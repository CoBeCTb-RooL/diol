<?php
ToolExecType::initArr();

class ToolExecType{
	
	static $items;
	
	public $code;
	public $name;
		
	const CRON 		= 'cron';
	const MANUAL 	= 'manual';
	
	
	
	public  function initArr()
	{
		$arr[self::CRON] 	= new self( self::CRON, 	'Крон');
		$arr[self::MANUAL] 	= new self( self::MANUAL, 	'Ручной запуск');
		
		
		self::$items = $arr;
	}
	
	
	
	function  __construct($code, $name)
	{
		$this->code=$code;
		$this->name=$name;
	}
	
	
	
	
	function code($code)
	{
		return self::$items[$code];
	}
	
	
	
}

