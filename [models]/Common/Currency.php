<?php
Currency::initArr();

class Currency{
	
	var   $code
		, $sign
		;
			
	const USD 	= 'usd';
	const KZT 	= 'kzt';
	
	
	static $items;

	
	function  __construct($code, $sign)
	{
		$this->code = $code;
		$this->sign = $sign;
	}	
	
	
	public  function initArr()
	{
		$arr[self::USD] 	= new self(self::USD,  '$');
		$arr[self::KZT] 	= new self(self::KZT,  '&#8376;');
		
		self::$items = $arr;
	}
	
	
	function code($code)
	{
		foreach(self::$items as $key=>$val)
			if($val->code == $code)
				return $val;
	}
	
	
	
}

