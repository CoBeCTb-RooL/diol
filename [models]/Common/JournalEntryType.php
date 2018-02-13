<?php
JournalEntryType::initArr();

class JournalEntryType{
	
	var   $code
		, $name
		;
	# 	общие
	const UNSPECIFIED 						= '';	
	const CREATE 							= 'create';
	const UPDATE 							= 'update';
	const DELETE 							= 'delete';
	const STATUS_CHANGED 					= 'status_changed';
	const APPROVE                           = 'approved';

	const DELETED_BY_USER                   = 'deleted_by_user';

	# 	комменты
	//const COMMENT_SET_STATUS 				= 'comment_set_status';
	//const COMMENT_APPROVE 					= 'comment_approve';
	//const COMMENT_DELETE_BY_USER 			= 'comment_delete_by_user';
	
	# 	юзеры
	//const USER_SET_STATUS 					= 'user_set_status';
	
	# 	единицы обьёма товара
	//const PRODUCT_VOLUME_UNIT_SET_STATUS 	= 'product_volume_unit_set_status';
	
	# 	категории
	const CATEGORY_CREATE_PRODUCT_UNIT_CMB 	= 'category_create_product_unit_cmb';
	const CATEGORY_DELETE_PRODUCT_UNIT_CMB 	= 'category_delete_product_unit_cmb';
	
	# 	объявления
	//const ADV_APPROVE 						= 'adv_approve';
	//const ADV_DELETE 						= 'adv_delete';

	# 	города
	//const CITY_SET_STATUS 					= 'city_set_status';
	
	# 	тулзы
	const TOOL_ADV_QUANS_RECACHE 			= 'tool_adv_quans_recache';
	
	# 	Задачи
	//const TASK_GROUP_SET_STATUS 			= 'task_group_set_status';
	//const TASK_GROUP_DELETE 				= 'task_group_delete';
	
	
	
	static $items;
	
	function  __construct($code, $name)
	{
		$this->code=$code;
		$this->name=$name;
	}	
	
	
	public  function initArr()
	{
		# 	общие
		$arr[self::CREATE]			    = new self( self::CREATE, 'Создание');
		$arr[self::UPDATE]			    = new self( self::UPDATE, 'Изменение');
		$arr[self::DELETE]			    = new self( self::DELETE, 'Удаление');
		$arr[self::STATUS_CHANGED]	    = new self( self::STATUS_CHANGED, 'Изменение статуса');

		$arr[self::APPROVE]	            = new self( self::APPROVE, 'Одобрено');
		$arr[self::DELETED_BY_USER]	    = new self( self::DELETED_BY_USER, 'Удалено пользователем');

		# 	комменты
		//$arr[self::COMMENT_SET_STATUS]	= new self( self::COMMENT_SET_STATUS, 'Смена статуса коммента');
		//$arr[self::COMMENT_APPROVE]	= new self( self::COMMENT_APPROVE, 'Одобрение комментария');
		//$arr[self::DELETED_BY_USER]	= new self( self::DELETED_BY_USER, 'Комментарий удалён пользователем');
		
		# 	юзеры
		//$arr[self::USER_SET_STATUS]	= new self( self::USER_SET_STATUS, 'Смена статуса пользователя');
		
		# 	единицы обьёма товара
		//$arr[self::PRODUCT_VOLUME_UNIT_SET_STATUS]	= new self( self::PRODUCT_VOLUME_UNIT_SET_STATUS, 'Смена статуса единицы объёма товара');
		
		# 	категории
		$arr[self::CATEGORY_CREATE_PRODUCT_UNIT_CMB]	= new self( self::CATEGORY_CREATE_PRODUCT_UNIT_CMB, 'Добавление связи с ед. измерения');
		$arr[self::CATEGORY_DELETE_PRODUCT_UNIT_CMB]	= new self( self::CATEGORY_DELETE_PRODUCT_UNIT_CMB, 'Удаление связи с ед. измерения');
		
		# 	объявления
		//$arr[self::ADV_APPROVE]	= new self( self::ADV_APPROVE, 'Объявление одобрено');
		
		# 	города
		//$arr[self::CITY_SET_STATUS]	= new self( self::CITY_SET_STATUS, 'Смена статуса города');
		
		# 	тулзы
		$arr[self::TOOL_ADV_QUANS_RECACHE]	= new self( self::TOOL_ADV_QUANS_RECACHE, 'Пересчёт количества объявлений');
		
		
		
		self::$items = $arr;
	}
	
	
	
	
	
	
	
	function code($code)
	{
		return self::$items[$code];
	}
	
	
	
}

