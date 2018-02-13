<?php
TaskPriority::initArr();

class TaskPriority{

	public $code;
	public $name;
	public $icon;

		
	const LOW 		= 1;
	const MEDIUM 	= 2;
	const HIGH 		= 3;


	static $items;


	function  __construct($code, $name, $icon)
	{
		$this->code = $code;
		$this->name = $name;
		$this->icon = $icon;
	}


	public  function initArr()
	{
		$arr[self::LOW] 	= new self(self::LOW, 		'Низкий');
		$arr[self::MEDIUM] 	= new self(self::MEDIUM, 	'Средний');
		$arr[self::HIGH] 	= new self(self::HIGH,		'Высокий');

		self::$items = $arr;
	}


	function code($code)
	{
		return self::$items[$code];
	}



}

