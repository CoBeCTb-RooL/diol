<?php 
class AdvItem{
	
	const TBL = 'adv__items';
	
	const COMMENTS_PER_PAGE = 10;
	
	//const URL_SIGN = 'item';
	const TREE_ELEMENTS_PER_PAGE = 10;
	const ADMIN_ELEMENTS_PER_PAGE = 15;
	
	const DAYS_TILL_EXPIRATION = 60; 
	
	/*const DEAL_TYPE_SELL = 'sell';
	const DEAL_TYPE_BUY = 'buy';*/
	
	
	# 	длины нескрываемых частей контактов
	const VISIBLE_PHONE_LENGTH = 5;
	const VISIBLE_EMAIL_LENGTH = 3;
	const VISIBLE_NAME_LENGTH = 2;
	# 	длина соли перед кусками возвращаемых контактов
	const SALT_LENGTH = 21;
	
	
	
	public    $id
			, $catId
			, $brandId
			, $artnumId
			, $artnumOther
			, $name
			, $descr
			, $price
			, $productVolumeUnitId
			, $statusId
			, $dateCreated
			, $dateEdited
			, $dateExpired
			, $dealTypeId
			, $idx
			, $userId
			, $cityId
			, $contactName
			, $phone
			, $email
			, $views
			, $currency
			
			, $status
			, $dealType
			, $propValues
			, $propValuesObjs
			, $cat
			, $user
			, $media
			, $city
			, $brand
			;
		
		
		
	
	
	/*# 	типы объявлений : КУПЛЮ / ПРОДАМ / что-то ещё..
	static $types = array(
		self::DEAL_TYPE_BUY=>'Куплю',
		self::DEAL_TYPE_SELL=>'Продам',
	) ;*/
	
	
	static $statusesToShow = array(
			Status::ACTIVE,
			Status::MODERATION,
			Status::INACTIVE,
			//Status::DELETED,
	);
	
	
	
	
	function init($arr)
	{
		$m = new self();
	
		$m->id = $arr['id'];
		$m->catId = $arr['catId'];
		$m->brandId = $arr['brandId'];
		$m->artnumId = $arr['artnumId'];
		$m->artnumOther = $arr['artnumOther'];
		$m->name = $arr['name'];
		$m->descr = $arr['descr'];
		$m->price = $arr['price'];
		$m->productVolumeUnitId = $arr['productVolumeUnitId'];
		$m->dateCreated = $arr['dateCreated'];
		$m->dateEdited = $arr['dateEdited'];
		$m->dateExpired = $arr['dateExpired'];
		$m->dealTypeId = $arr['dealTypeId'];
		$m->dealType = DealType::num($m->dealTypeId);
		$m->idx = $arr['idx'];
		$m->userId = $arr['userId'];
		$m->cityId = $arr['cityId'];
		$m->statusId = $arr['status'];
		$m->contactName = $arr['contactName'];
		$m->phone = $arr['phone'];
		$m->email = $arr['email'];
		$m->status = Status::num($m->statusId);
        $m->views = $arr['views'];
		$m->currency = Currency::code($arr['currency']);
        $m->reason = $arr['reason'];
	
		return $m;
	}
	
	
		
	function getList($params, $statuses, $dealType)
	{
		if(gettype($params) != 'array' )
			$params = array('catId'=>$params);
		
		$params['statuses'] = $params['statuses'] ? $params['statuses'] : $statuses;
		$params['dealType'] = $params['dealType'] ? $params['dealType'] : $dealType;
		
		$sql="SELECT * FROM `".self::TBL."` WHERE 1 ".self::getListInnerSql($params);
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		while($next=mysql_fetch_array($qr, MYSQL_ASSOC))
		{
			$e = self::init($next);
			$ret[] = $e; 
		}
		
		return $ret;
	}
	
	
	
	
	function getCount($params, $statuses, $dealType, $cityId)
	{
		if(gettype($params) != 'array' )
			$params = array('catId'=>$params);
		
		$params['statuses'] = $params['statuses'] ? $params['statuses'] : $statuses;
		$params['dealType'] = $params['dealType'] ? $params['dealType'] : $dealType;
		$params['cityId'] = $params['cityId'] ? $params['cityId'] : $cityId;
		
		$sql="SELECT COUNT(*) FROM `".self::TBL."` WHERE 1 ".self::getListInnerSql($params);
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		$next = mysql_fetch_array($qr);
		
		return $next[0];
	}
	
	
	
	function getCountGroupByStatus($params)
	{
		if(gettype($params) != 'array' )
			$params = array('catId'=>$params);
		
		$sql="SELECT status, COUNT(*) as count FROM `".self::TBL."` WHERE 1 ".self::getListInnerSql($params)." GROUP BY status";
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		while($next=mysql_fetch_array($qr, MYSQL_ASSOC))
			$ret[$next['status']] = $next['count'];
		
		return $ret;
	}
	
	
	
	
	function getListInnerSql($params)
	{
		//vd($params);
		$sql.="
		".($params['catId'] ? "AND (catId=".intval($params['catId']).")" : "")."
		".($params['userId'] ? "AND (userId=".intval($params['userId']).")" : "")."
		".($params['cityId'] ? " AND cityId='".intval($params['cityId'])."' " : "")." 
		".($params['brandId'] ? " AND brandId='".intval($params['brandId'])."' " : "")." 
		".($params['artnumId'] ? " AND artnumId='".intval($params['artnumId'])."' " : "")." 
		".($params['additionalClause'])."
		";
		
		if(count($params['statuses']))
		{
			$sql .= " AND status IN(-1";
			foreach($params['statuses'] as $s)
				$sql .= ", '".$s->num."'";
			$sql.=") ";
		}
		
		$sql.="
		".($params['dealType'] ? " AND dealTypeId='".intval($params['dealType']->num)."' " : "")."
				
		".($params['dateFrom'] ? " AND dateCreated>='".strPrepare($params['dateFrom'])."' " : "")."
		".($params['dateTo'] ? " AND dateCreated<='".strPrepare($params['dateTo'])."' " : "")."
		
		".($params['id'] ? " AND id='".intval($params['id'])."' " : "")."
		".($params['userId'] ? " AND userId='".intval($params['userId'])."' " : "")."";
		
		if($params['searchText'])
			$sql.=" AND CONCAT(name, descr) LIKE('%".strPrepare($params['searchText'])."%') ";
		
		$sql.="		
		".($params['orderBy'] ? " ORDER BY ".($params['orderBy'] ? strPrepare($params['orderBy']) : 'id') : "")."
		".strPrepare($params['limit'])."
		";
		
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
		$this->idx = 9999;
		$sql = "
		INSERT INTO `".self::TBL."` 
		SET 
		dateCreated=NOW(),
		dateExpired=NOW() + INTERVAL ".intval(self::DAYS_TILL_EXPIRATION)." DAY,
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
		dateEdited=NOW(),
		dateExpired = '".strPrepare($this->dateExpired)."', 
		".$this->alterSql()."
		WHERE id=".intval($this->id)."
		";
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
	}
	
	
	
	
	function alterSql()
	{
		//vd($this->status);
		$str.="
		  `status` = '".intval($this->status->num)."'
		, `idx` = ".intval($this->idx)."
		, `catId`='".intval($this->catId)."'
		, `brandId`='".intval($this->brandId)."'
		, `artnumId`='".intval($this->artnumId)."'
		, `artnumOther`='".strPrepare($this->artnumOther)."'
		, `name`='".strPrepare($this->name)."'
		, `descr`='".strPrepare($this->descr)."'
		, `price`='".strPrepare($this->price)."'
		, `productVolumeUnitId`='".intval($this->productVolumeUnitId)."'
		, `dealTypeId`='".intval($this->dealType->num)."'
		, `userId`='".intval($this->userId)."'
		, `cityId`='".intval($this->cityId)."'
		, `contactName`='".strPrepare($this->contactName)."'
		, `phone`='".strPrepare($this->phone)."'
		, `email`='".strPrepare($this->email)."'
		, `views`='".intval($this->views)."'
		, `currency`='".strPrepare($this->currency->code)."'
		, `reason`='".strPrepare($this->reason)."'
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
	
	
	
	
	
	
	
	function setIdx($id, $val)
	{
		if($id=intval($id))
		{
			$sql = "UPDATE `".self::TBL."` SET idx='".intval($val)."' WHERE id=".$id;
			DB::query($sql);
			echo mysql_error();
		}
	}
	
	
	
	
	
	
	
	function getExpiredList()
	{
		
		$sql="SELECT * FROM `".self::TBL."` WHERE status='".intval(Status::code(Status::ACTIVE)->num)."'  AND dateExpired < NOW()";
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		while($next=mysql_fetch_array($qr, MYSQL_ASSOC))
		{
			$e = self::init($next);
			$ret[] = $e;
		}

		return $ret;
	}
	
	
	
	
	
	
	
	
	function validate()
	{
		//echo "!";
		$errors = array();
		//vd($this->propValues);
		
		if(!DealType::code($this->dealType->code))
			$errors[] = Slonne::setError('type', 'Выберите, Вы собираетесь <b>купить</b>, или <b>продать</b>?');
		if(!$this->name = trim($this->name))
			$errors[] = Slonne::setError("name", 'Введите <b>название</b>!');
		if(!$this->phone = trim($this->phone))
			$errors[] = Slonne::setError("phone", 'Введите <b>контактные телефоны</b>!');
		if(!$this->cityId = intval($this->cityId))
			$errors[] = Slonne::setError("city", 'Выберите <b>город</b>!');
		//vd($this->cat->class->props);
		//vd($this->cat->class);
		
		#	валидация свойств
		if($this->dealType->code == DealType::SELL)
		{
			foreach($this->cat->class->props as $key=>$prop)
			{
				if($tmp = $prop->validateValue($this->propValues[$prop->code]))
					$errors[] = $tmp;
			}
		}
		
		if($this->price && !is_numeric($this->price))
			$errors[] = Slonne::setError("price", 'Пожалуйста, укажите корректную цену, или не указывайте совсем.');
		
		
		return $errors;
	}
	
	
	
	function setStatus($status)
	{
		$sql="UPDATE `".self::TBL."` SET status='".intval($status->num)."' where id=".intval($this->id);
		//vd($sql);
		DB::query($sql);
		echo mysql_error();
	}
	
	
	function setViews($views)
	{
		$sql="UPDATE `".self::TBL."` SET views='".intval($views)."' where id=".intval($this->id);
		//vd($sql);
		DB::query($sql);
		echo mysql_error();
	}
	
	
	function increaseViews()
	{
		$this->setViews($this->views+1);
	}
	
	
	function initValues()
	{
		#	достаём все значения итема! все, включая толпу мультиселектов
		$propValues = AdvPropValue::getListByItemId($this->id);
		
		#	В ЭТОМ МАССИВЕ БУДУТ ЛЕЖАТ ВСЕ ЗНАЧЕНИЯ, КРОМЕ МУЛЬТИСЕЛЕКТОВ!
		$valuesArr = null;
		#	расладываем значения в ассоц массив, где ключ - код (чтобы взять значения всех, кроме МУЛЬТИСЕЛЕКТОВ)
		foreach($propValues as $key=>$prop)
		{
			$valuesArr[$prop->propCode] = $prop->value;
			$valuesObjsArr[$prop->propCode] = $prop;
		}
		
		foreach($this->cat->class->props as $key=>$prop)
		{
			$value = null;
			$value = $propValues[$prop->id]->value;
			
			if($prop->type=='select' && $prop->multiple)	#	раскладываем мультиселекты
			{
				foreach($propValues as $key=>$propValue)
				{
					if($prop->code == $propValue->propCode)
					{
						$this->propValues[$prop->code][] = $propValue->value;
						$this->propValuesObjs[$prop->code][] = $propValue;
					}
				}
			}
			elseif($prop->type=='table')	# 	табличный тип
			{
				$this->propValues[$prop->code] = AdvPropTableColumnValue::getRowsArrByAdvIdAndPropId($this->id, $prop->id);
				$prop->value = $this->propValues[$prop->code];
				$this->propValuesObjs[$prop->code] = $this->propValues[$prop->code];
			}
			else	#	все остальные
			{
				$this->propValues[$prop->code] = $valuesArr[$prop->code];
				$this->propValuesObjs[$prop->code] = $valuesObjsArr[$prop->code];
			}
			
		}

	}

	
	function initUser($status)
	{
		$this->user = User::get($this->userId, $status);
	}
	
	
	function initCat($status)
	{
		$this->cat = AdvCat::get($this->catId, $status);
	}
	
	function initBrand($status)
	{
		$this->brand = Brand::get($this->brandId, $status);
	}
	
	function initArtNum($status)
	{
		$this->artnum = ArtNum::get($this->artnumId, $status);
	}
	
	function initCity()
	{
		global $_GLOBALS;
		$this->city = $_GLOBALS['cities'][$this->cityId] ? $_GLOBALS['cities'][$this->cityId] : City::get($this->cityId);
	}
	
	function initProductVolumeUnit()
	{
		$this->productVolumeUnit = ProductVolumeUnit::get($this->productVolumeUnitId);
	}
	
	
	function setValuesFromArray($arr)
	{
		//vd($arr['name']);
		//vd($_REQUEST);
		if(!$this->cat)
			$this->initCat();
		if($this->cat && !$this->cat->class)
			$this->cat->initClass(Status::code(Status::ACTIVE));
		if($this->cat && $this->cat->class &&!$this->cat->class->props)
			$this->cat->class->initProps(Status::code(Status::ACTIVE));
		
		//$this->active = $arr['active'] ? 1 : 0;
		$this->catId = $_REQUEST['pid'] ? $_REQUEST['pid'] : $_REQUEST['catId'];
		$this->name = $arr['name'];
		$this->descr = $arr['descr'];
		$this->dealType = DealType::num($arr['type']);
		$this->contactName = $arr['contactName'];
		$this->phone = $arr['phone'];
		$this->email = $arr['email'];
		$this->brandId = $arr['brand'];
		$this->artnumId = $arr['artnumId'];
		$this->artnumOther = $arr['artnumOther'];
		if($this->cat->class)
		{
			foreach($this->cat->class->props as $key=>$prop)
			{
				$this->propValues[$prop->code] = $arr[$prop->code];
				/*vd($prop->code);
				vd($this->propValues[$prop->code]);
				echo '<hr>';*/
			}
		}
		$this->price = str_replace(' ', '', str_replace(',', '.', $arr['price']));
		$this->productVolumeUnitId = $arr['productVolumeUnitId'];
		$this->currency = Currency::code($arr['currency']);
	}
	
	
	
	
	
	
	function tryToInsertOrUpdateItemAndProps()
	{
		DB::transactionStart();
		
		# 	сперва сохраняем просто модель, свойства - после
		if(!$this->id)
			$this->id = $this->insert();
		else
			$this->update();
		
		if(!$e = mysql_error())
		{
			#	работа с пропсами
			foreach($this->cat->class->props as $propId=>$prop)
			{
				$value = $this->propValues[$prop->code];
		
				#	обработка МУЛЬТИСЕЛЕКТА
				if($prop->type=='select' && $prop->multiple)
				{
					//vd($value);
					AdvPropValue::saveMultipleSelectOptionsByItemAndProp($this, $prop, $value);
				}
				# 	обработка ТАБЛИЧНОГО СВОЙСТВА
				elseif($prop->type=='table')
				{
					# 	удаляем ВСЕ данные таблицы, чтобы сохранить заново
					AdvPropTableColumnValue::deleteByAdvIdAndPropId($this->id, $prop->id);
					
					foreach($this->propValues[$prop->code] as $rowId=>$val)
					{
						foreach($val as $columnId=>$val2)
						{
							if($val2)
							{
								$tmp = new AdvPropTableColumnValue();
								$tmp->advId=$this->id;
								$tmp->columnId=$columnId;
								$tmp->propId=$prop->id;
								$tmp->rowId = $rowId;
								$tmp->value = $val2;
								$tmp->status = Status::code(Status::ACTIVE);
								
								$tmp->insert();
							}
						}
					}
				}
				else 	#	ВСЕ ОСТАЛЬНЫЕ
				{
					$newProp = false;
					$propValue = AdvPropValue::getByItemIdAndPropId($this->id, $prop->id);
					//vd($propValue);
					if(!$propValue)
					{
						$propValue = new AdvPropValue();
						$newProp = true;
					}
					
					$propValue->propId = $prop->id;
					$propValue->propCode = $prop->code;
					$propValue->itemId = $this->id;
					$propValue->value = $value;
					//vd($newProp);
					if($newProp)
						$propValue->insert();
					else
						$propValue->update();
				}
					
			}
				
			if(!$e = mysql_error())
				DB::commit();
			else
			{
				DB::rollback();
				$error = $e;
			}
		}
		else
		{
			DB::rollback();
			$error = $e;
		}
		
		return $error;
	}
	
	
	
	function initMedia($active)
	{
		$this->media = AdvMedia::getList($this->id, $active);
	}
	
	
	function url()
	{
		//return '/'.URL_ADV_VIEW.'/'.$this->urlPiece();
		global $CORE, $_CONFIG;
		
		$route = Route::getByName(Route::KARTOCHKA_OBYAVLENIYA);
		//vd($route);
		$ret = $route->url($this->urlPiece());
		return $ret;
		//return '/'.LANG.'/'.Adv::FRONTEND_CONTROLLER.'/'.Adv::FRONTEND_ITEM_ACTION.'/'.$this->urlPiece();
	}
	
	function editUrl()
	{
		global $CORE, $_CONFIG;
		
		$route = Route::getByName(Route::CABINET_ADV_EDIT);
		$ret = $route->url($this->urlPiece());
		return $ret;
		//return '/'.LANG.'/cabinet/advs/edit/'.$this->urlPiece();
	}
	
	
	function urlPiece()
	{
		return ''.$this->id.'_'.str2url($this->name);
	}
	
	
}
?>