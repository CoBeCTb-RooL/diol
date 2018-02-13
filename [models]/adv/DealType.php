<?php
DealType::initArr();

class DealType{
	
	var   $num
		, $code
		, $title
		, $titleInf
		, $icon
		, $adminBackgroundColor
		;
			
	const BUY = 'buy';
	const SELL = 'sell';
		
	static $items;

	
	function  __construct($num, $code, $title, $titleInf, $icon)
	{
		$this->num=$num;
		$this->code=$code;
		$this->title=$title;
		$this->titleInf=$titleInf;
		$this->icon=$icon;
		
	}	
	
	
	public  function initArr()
	{
		$arr[self::BUY] 		= new DealType(1, self::BUY, 'Куплю', 'Купить', '<i class="fa fa-download"></i>');
		$arr[self::SELL] 		= new DealType(2, self::SELL, 'Продам', 'Продать', '<i class="fa fa-upload"></i>');
		
		self::$items = $arr;
	}
	
	
	function code($code)
	{
		foreach(self::$items as $key=>$val)
			if($val->code == $code)
				return $val;
	}
	
	
	function num($num)
	{
		foreach(self::$items as $key=>$val)
			if($val->num == $num)
				return $val;
	}
	
	
}

