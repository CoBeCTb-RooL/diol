<?php
Status::initArr();

class Status{
	
	var   $num
		, $code
		, $name
		, $icon
		
		;
		
	const UNSPECIFIED 	= '';	
	const ACTIVE 		= 'active';
	const INACTIVE 		= 'inactive';
	const MODERATION 	= 'moderation';
	const REJECTED  	= 'rejected';
	const DELETED 		= 'deleted';
	const ARCHIVED 		= 'archived';
	const DONE 			= 'done';
	const EXPIRED		= 'expired';

	
	static $items;

	
	function  __construct($num, $code, $name, $icon)
	{
		$this->num=$num;
		$this->code=$code;
		$this->name=$name;
		$this->icon=$icon;
	}	
	
	
	public  function initArr()
	{
		$arr[self::ACTIVE] 			= new self(1, self::ACTIVE, 	'Активно', '<i class="fa fa-toggle-on"></i>');
		$arr[self::INACTIVE] 		= new self(2, self::INACTIVE, 	'Неактивно', '<i class="fa fa-toggle-off"></i>');
		$arr[self::MODERATION] 		= new self(3, self::MODERATION, 'Модерация', '<i class="fa fa-clock-o"></i>');
		$arr[self::DELETED] 		= new self(4, self::DELETED, 	'Удалено', '<i class="fa fa-times"></i>');
		$arr[self::ARCHIVED] 		= new self(5, self::ARCHIVED, 	'Архив', '');
		$arr[self::DONE] 			= new self(6, self::DONE, 		'Выполнено', '');
		$arr[self::EXPIRED]			= new self(7, self::EXPIRED, 	'Просрочено', '<i class="fa fa-exclamation-circle"></i>');
		
		self::$items = $arr;
	}
	
	
	function code($code)
	{
		return self::$items[$code];
	}
	
	
	function num($num)
	{
		foreach(self::$items as $key=>$val)
			if($val->num == $num)
				return $val;
	}
	
	
}

