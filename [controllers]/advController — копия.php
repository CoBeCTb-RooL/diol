<?php 
class AdvController extends MainController{
	
	const CAT_TYPE = 'adv';
	const CAT_ADVS_LIST_ELEMENTS_PER_PAGE = 10;
	
	
	function routifyAction()
	{
		global $CORE;
		$section = $CORE->params[0];
		$p = $CORE->params[1];
		
		if($section == Adv::FRONTEND_CAT_ACTION)
		{
			/*if(!$p)
				$action = 'catsList';
			else*/
				$action = 'catView';
		}
		
		elseif($section == 'item')
		{
			$action = 'advView';
			if($p == 'contactInfoAjax')
				$action = 'advContactInfoAjax';
		}
		elseif($section == 'artnums_list_ajax')
			$action = 'artnumsListAjax';

		elseif($section == 'comment')
		{
			if($p == 'add')
				$action = 'commentAdd';
			if($p == 'delete')
				$action = 'commentDelete';
		}
		
		if($action)
			$CORE->action = $action;
	}
	
	
	function index()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		self::catsList();
	}
	
	
	function catsList()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);		
		Startup::execute(Startup::FRONTEND);
		
		$CONTENT->setTitle('Категории');
		
		$MODEL['chosenCityId'] = $_REQUEST['cityId'];
		//vd($_GLOBALS['city']);
		$MODEL['city'] = isset($_REQUEST['cityId']) ? $_GLOBALS['cities'][$_REQUEST['cityId']] : $_GLOBALS['city'];
		
		$MODEL['dealType'] = DealType::code($_REQUEST['type']);
		$MODEL['cats'] = AdvCat::getFullCatsTree($status=Status::code(Status::ACTIVE));

		$advsQuanList = AdvQuan::getListByCityAndDealType($MODEL['city']->id, $MODEL['dealType']);
		//vd($advsQuanList);
		foreach($MODEL['cats'] as $cat)
		{
			$cat->advsCount = $advsQuanList[$cat->id]->quan;
			
			foreach($cat->subCats as $subCat)
			{
				$subCat->advsCount = $advsQuanList[$subCat->id]->quan;
				//$subCat->advsCount = AdvItem::getCount($subCat->id, Status::code(Status::ACTIVE), $MODEL['dealType'], $_REQUEST['city']);
			}
		}
		
		$MODEL['cities'] = $_GLOBALS['cities'];
		
		//vd($MODEL['cats']);
		$crumbs = array();
		$crumbs[] = '<a href="'.Route::getByName(Route::MAIN)->url().'/">Главная</a>';
		$crumbs[] = 'Объявления';
		$MODEL['crumbs'] = $crumbs;
		
		
		
		Core::renderView('adv/catsList.php', $MODEL);
	}
	
	
	
	
	function catView()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		
		$CONTENT->setTitle('Категории');
		
		$elPP = self::CAT_ADVS_LIST_ELEMENTS_PER_PAGE;
		$page = intval($CORE->specialParams['p']) ? intval($CORE->specialParams['p'])-1 : 0;
		$limit=" LIMIT ".$page*$elPP.", ".$elPP."";
		//vd($limit);
		$MODEL['elPP'] = $elPP;
		$MODEL['page'] = $page;

		# 	тип объявления - КУПЛЮ / ПРОДАМ...
		$MODEL['dealType'] = DealType::code($_REQUEST['type']);
		
		$cat = AdvCat::get($CORE->params[1]);

		if($cat)
		{
			$CONTENT->setTitle($cat->name);
			
			$cat->initClass();
			if($cat->class)
				$cat->class->initProps(Status::code(Status::ACTIVE));
			
			# 	бренды категории
			$MODEL['brands'] = Brand::getList(Status::code(Status::ACTIVE), $cat->id);
			
			# 	формируем запрос с учётом фильтров
			if($_REQUEST['go'])
			{
				$additionalClause = " ";
				
				# 	выбранный бренд
				$MODEL['chosenBrand'] = Brand::get($_REQUEST['brand']);
				# 	список арт. номеров
				if($MODEL['brands'] && $MODEL['chosenBrand'] )
				{
					$MODEL['artnums'] = ArtNum::getList(Status::$items[Status::ACTIVE], $MODEL['chosenBrand']->id, $cat->id);
					//vd($cat);
					# 	выбранный арт.номер
					$MODEL['chosenArtnum'] = ArtNum::get($_REQUEST['artnumId']);
					//vd($MODEL['artnums']);
					//vd($MODEL['chosenArtnum']);
				}
				
				# 	обработка выбранных свойств
				foreach($cat->class->props as $key=>$prop)
				{
					if($_REQUEST[$prop->code])
					{
						if($prop->type!='select')
							$additionalClause.=" \nAND id IN(".AdvPropValue::getItemIdSqlByPropCodeAndValue($prop->code, $_REQUEST[$prop->code]).") ";
						else # 	ЕСЛИ СЕЛЕКТ
						{
							if($_REQUEST[$prop->code][0]) 	# 	если значение не пустое
							{
								$tmpSql = null; 
								foreach($_REQUEST[$prop->code] as $value)
									$tmpSql[] = " \n id IN(".AdvPropValue::getItemIdSqlByPropCodeAndValue($prop->code, $value).") ";
								$additionalClause.=" AND \n(".join("\n OR ", $tmpSql)."\n)";
							}
						}
					}
				}
			}
			
			$MODEL['cities'] = $_GLOBALS['cities'];
			$MODEL['city'] = isset($_REQUEST['cityId']) ? $_GLOBALS['cities'][$_REQUEST['cityId']] : $_GLOBALS['city'];

			
			$params=array(
					'catId'=>$cat->id, 
					'statuses'=>array(Status::code(Status::ACTIVE), ), 
					'additionalClause'=>$additionalClause, 
					'dealType'=>$MODEL['dealType'], 
					'brandId'=>$MODEL['chosenBrand']->id,
					'artnumId'=>$MODEL['chosenArtnum']->id,
					'cityId'=>$MODEL['city']->id,
					'orderBy'=>'id desc',
					'limit'=>$limit,
			);			
			
			$MODEL['items'] = AdvItem::getList($params);
			$params['limit'] = '';
			$MODEL['totalCount'] = AdvItem::getCount($params);
			
			# 	инициализируем категорию
			foreach($MODEL['items'] as $item)
			{
				$item->cat = $cat;
				$item->initBrand();
				$item->initMedia();
				$unitsIds[] = $item->productVolumeUnitId;
			}
			// 	инитим юзеров
			$units = ProductVolumeUnit::getByIdsList(array_unique($unitsIds));
			foreach($MODEL['items'] as $item)
				$item->productVolumeUnit = $units[$item->productVolumeUnitId];
			
		}
		else 
		{
			//echo 'NO CAT!';
		}
		
		$MODEL['cat'] = $cat;
		
		$crumbs = array();
		$crumbs[] = '<a href="'.Route::getByName(Route::MAIN)->url().'">Главная</a>';
		$crumbs[] = '<a href="'.Route::getByName(Route::SPISOK_KATEGORIY)->url().'">Объявления</a>';
		$crumbs[] = $MODEL['cat']->name;
		$MODEL['crumbs'] = $crumbs;
		
		Core::renderView('adv/catView.php', $MODEL);
	}
	
	
	
	
	
	
	function advView()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		
		$CONTENT->setTitle('Объявления');
		 
		$viewAccess = false;
		//$MODEL['cat'] = Category::get($_PARAMS[1]);
		
		$item = AdvItem::get($CORE->params[1]);
		if($item)
		{
			# 	проверка статуса
			if($item->status->code==Status::code(Status::ACTIVE)->code || $item->userId == $USER->id || Admin::isAdmin())
				$viewAccess = true;
				
			if($viewAccess)
			{
				$CONTENT->setTitle($item->name);
				
				$item->initCat();
				if($item->cat)
				{
					$item->cat->initClass();
					if($item->cat->class)
					{
						$item->cat->class->initProps(Status::code(Status::ACTIVE));
						foreach($item->cat->class->props as $pr)
							$pr->initTableColumns(Status::code(Status::ACTIVE));
					}
				}
				
				$item->initUser();
				$item->initValues();
				$item->initMedia(Status::code(Status::ACTIVE));
				$item->initBrand();
				$item->initArtNum();
				$item->initCity(); 
				$item->initProductVolumeUnit();
				//vd($item);
				
				$MODEL['crumbs'] = array();
				$MODEL['crumbs'][] = '<a href="'.Route::getByName(Route::MAIN)->url().'">Главная</a>';
				$MODEL['crumbs'][] = '<a href="'.Route::getByName(Route::SPISOK_KATEGORIY)->url().'">Объявления</a>';
				$MODEL['crumbs'][] = '<a href="'.$item->cat->url().'"><b>'.$item->cat->name.'</b></a>';
				$MODEL['crumbs'][] = 'Объявление';
			}
			
			$item->increaseViews();
			$item->views++;
			
			// 	комменты 
			$params = array(
					'status' => Admin::isAdmin() ? '' : Status::code(Status::ACTIVE),
					'objectType'=>Object::ADV,
					'pid'=>$item->id, 
					'from' => 0,
					'count' => /*AdvItem::COMMENTS_PER_PAGE*/'',
					'orderBy' => '',
					'desc' =>'',
					'showModerationCommentsForUserId'=>$USER->id,
			);
			$MODEL['comments'] = Comment::getList($params);
			foreach($MODEL['comments'] as $c)
				$userIdsToTake[] = $c->userId;
			$userIdsToTake = array_unique($userIdsToTake);
			//vd($userIdsToTake);
			$users = User::getByIdsList($userIdsToTake);
			foreach($MODEL['comments'] as $c)
				$c->user = $users[$c->userId];
		}
		
		if(!$viewAccess)
			$error='Объявление не найдено.';
		//vd($item);
		
		$MODEL['item'] = $item;
		$MODEL['error'] = $error;
		
		Core::renderView('adv/itemView.php', $MODEL);
	}
	
	
	
	
	
	function advContactInfoAjax()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		//Startup::execute(Startup::FRONTEND);
		
		$CORE->setLayout(null);

		$error = '';
		$item = AdvItem::get($_REQUEST['id'], Status::code(Status::ACTIVE));
		$res['error'] = $error;
		$res['name'] = Funx::getSalt(AdvItem::SALT_LENGTH).mb_substr($item->contactName, AdvItem::VISIBLE_NAME_LENGTH, 1000, 'UTF-8');
		$res['phone'] = Funx::getSalt(AdvItem::SALT_LENGTH).mb_substr($item->phone, AdvItem::VISIBLE_PHONE_LENGTH, 1000, 'UTF-8'); 
		$res['email'] = Funx::getSalt(AdvItem::SALT_LENGTH).mb_substr($item->email, AdvItem::VISIBLE_EMAIL_LENGTH, 1000, 'UTF-8');
		//$res['html'] = Core::renderView(Adv::FRONTEND_CONTROLLER.'/contactInfo.php', $MODEL, $buffer=true);
		echo json_encode($res);
	}
	
	
	
	
	
	
	function artnumsListAjax()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		//Startup::execute(Startup::FRONTEND);
		$CORE->setLayout(null);

		$cat = AdvCat::get($_REQUEST['cat']);
		$brand = Brand::get($_REQUEST['brand']);
		$MODEL['artnums'] = ArtNum::getList(Status::$items[Status::ACTIVE], $brand->id, $cat->id);
		$MODEL['otherOption'] = $_REQUEST['otherOption'];
		
		ob_start();
		Core::renderPartial(Adv::FRONTEND_CONTROLLER.'/filtersPartial/selectArtnumsPartial.php', $MODEL);
		$res['count'] = count($MODEL['artnums']);
		$res['html'] = ob_get_clean();
		
		echo json_encode($res);
	}
	
	
	
	
	
	function commentAdd()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		$CORE->setLayout(null);
		//usleep(200000);
		//vd($_REQUEST);
		//vd($_GLOBALS);
		//vd($_SESSION['captcha_keystring']);
		$errors = null; 
		
		$item = AdvItem::get($_REQUEST['id'], Status::code(Status::ACTIVE));
		if($item)
		{
			$text = trim(htmlspecialchars($_REQUEST['text']));
			if($text)
			{
				if($USER)
				{
					if($_REQUEST['captcha'] == $_SESSION['captcha_keystring'])
					{
						$c = new Comment();
						$c->objectType = Object::code(Object::ADV);
						$c->pid = $item->id;
						$c->status = Status::code(Status::MODERATION);
						$c->text = $text;
						$c->userId = $USER->id;
						$c->insert();
					}
					else
						$errors[] = Slonne::setError('captcha', 'Вы ввели неправильный код подтверждения.');
				}
				else
					$errors[] = Slonne::setError('text', 'Необходимо авторизоваться, чтобы оставить комментарий.');
			}
			else
				$errors[] = Slonne::setError('text', 'Введите комментарий!');
		}
		else 
			$errors[] = Slonne::setError('qqq', 'Ошибка! Объявление не найдено.');
		
		$res['errors'] = $errors;
		echo json_encode($res);
	}
	
	
	
	
	
	
	function commentDelete()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		$CORE->setLayout(null);
		
		$errors = null;
	
		$comment = Comment::get($_REQUEST['id']);
		if($comment)
		{
			//vd($comment->userId);
			//vd($USER->id);
			if($comment->userId == $USER->id)
			{
				$comment->setStatus(Status::code(Status::DELETED));
				
				$je = new JournalEntry();
				$je->objectType = Object::code(Object::COMMENT);
				$je->objectId = $comment->id;
				$je->journalEntryType = JournalEntryType::code(JournalEntryType::COMMENT_DELETE_BY_USER);
				$je->comment = 'Комментарий удалён пользователем.';
				$je->userId = $USER->id;
					
				$je->insert();
			}
			else
				$errors[] = Slonne::setError('qqq', 'Ошибка! Вы не можете удалить этот комментарий.');
		}
		else
			$errors[] = Slonne::setError('qqq', 'Ошибка! Комментарий не найден.');
	
		$res['errors'] = $errors;
		echo json_encode($res);
	}
	
	
	
	
	
	
}


?>