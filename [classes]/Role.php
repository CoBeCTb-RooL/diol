<?php
Role::initArr();

class Role{
	
	static $items;
	
	public $code;
	public $num;
	public $name;
	
		
	const UNSPECIFIED 				= 0;
	const SUPER_ADMIN 				= 2;
	const SYSTEM_ADMINISTRATOR		= 4;
	const ADV_MODERATOR 			= 8;
	const COMMENT_MODERATOR 		= 16;
	const ADMINS_MODERATOR 			= 32;
	const ADMIN_GROUPS_MODERATOR 	= 64; //2^6
	const NEWS_MODERATOR 			= 128;
	const USERS_MODERATOR 			= 256;
	
	
	
	
	public  function initArr()
	{
		$arr[self::SUPER_ADMIN]				= new self(self::SUPER_ADMIN, 'Сверх-администратор', 'super_admin');
		$arr[self::SYSTEM_ADMINISTRATOR] 	= new self(self::SYSTEM_ADMINISTRATOR, 'Администратор сайта', 'admin');
		$arr[self::ADV_MODERATOR]			= new self(self::ADV_MODERATOR, 'Модератор объявлений', 'adv_moderator');
		$arr[self::COMMENT_MODERATOR] 		= new self(self::COMMENT_MODERATOR, 'Модератор коментариев', 'comment_moderator');
		$arr[self::ADMINS_MODERATOR] 		= new self(self::ADMINS_MODERATOR, 'Модератор админов', 'admins_moderator');
		$arr[self::ADMIN_GROUPS_MODERATOR] 	= new self(self::ADMIN_GROUPS_MODERATOR, 'Модератор групп админов', 'admins_groups_moderator');
		$arr[self::NEWS_MODERATOR] 			= new self(self::NEWS_MODERATOR, 'Модератор новостей', 'news_moderator');
		$arr[self::USERS_MODERATOR] 		= new self(self::USERS_MODERATOR, 'Модератор пользователей', 'users_moderator');
		
		
		self::$items = $arr;
	}
	
	
	
	function  __construct($num, $name, $code)
	{
		$this->code=$code;
		$this->name=$name;
		$this->num = $num;
	}
	
	
	
	
	function num($num)
	{
		return self::$items[$num];
	}
	
	
	function getListByRole($role)
	{
		foreach(self::$items as $r) 
			if($role & $r->num )
				$ret[] = $r;
		return $ret;

	}
	
	
}

