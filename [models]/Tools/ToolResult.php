<?php
ToolResult::initArr();

class ToolResult{
	
	static $items;
	
	public $code;
	public $name;
	public $color;
		
	const OK 			= 'ok';
	const ERRORS 		= 'errors';
	const FAILED 		= 'failed';
	
	
	
	public  function initArr()
	{
		$arr[self::OK] 			= new self( self::OK, 		'Ок', '#0CAD03');
		$arr[self::ERRORS] 		= new self( self::ERRORS, 	'Выполнено с ошибками', 'orange');
		$arr[self::FAILED] 		= new self( self::FAILED, 	'Не выполнено', 'red');
		
		
		self::$items = $arr;
	}
	
	
	
	function  __construct($code, $name, $color)
	{
		$this->code=$code;
		$this->name=$name;
		$this->color=$color;
	}
	
	
	
	
	function code($code)
	{
		return self::$items[$code];
	}
	
	
	
}

