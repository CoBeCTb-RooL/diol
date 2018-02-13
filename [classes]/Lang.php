<?php
Lang::initArr();

class Lang{
	
	public $code;
	public $name;
		
	static $items;

	const RU = 'ru';
	const EN = 'en';
	const KZ = 'kz';
	
	
	function  __construct($code, $name)
	{
		$this->code = $code;
		$this->name = $name;
	}	
	
	public  function initArr()
	{
		$arr[self::RU] 	= new self(self::RU, 'Русский');
		$arr[self::EN] 	= new self(self::EN, 'English');
		$arr[self::KZ] 	= new self(self::KZ, 'Қазақ');
		
		self::$items = $arr;
	}
	
	
	function code($code)
	{
		return self::$items[$code];
	}
	
	
	
}

