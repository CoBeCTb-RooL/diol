<?php
class CabinetController extends MainController{

	const CAT_TYPE = 'adv';
	const CABINET_ADVS_LIST_ELEMENTS_PER_PAGE = 10;
	
	
	function routifyAction()
	{
		global $CORE;
		$section = $CORE->params[0];
		$p = $CORE->params[1];

		if($section == 'profile')
		{
			$action = 'advs';
			if($p == 'edit')
				$action = 'profileEdit';
			if($p == 'profileEditSubmit')
				$action = 'profileEditSubmit';
			if($p == 'activate')
				$action = 'profileActivate';
			if($p == 'logout')
				$action = 'profileLogout';
			if($p == 'authSubmit')
				$action = 'profileAuthSubmit';
			if($p == 'password_change')
				$action = 'profilePasswordChange';
			if($p == 'passwordChangeSubmit')
				$action = 'profilePasswordChangeSubmit';
			if($p == 'password_reset_claim')
				$action = 'profilePasswordResetClaim';
			if($p == 'passwordResetSendClaimAjax')
				$action = 'passwordResetSendClaimAjax';
			if($p == 'password_reset')
				$action = 'profilePasswordReset';
		}
		//vd($action);
		
		# 	ОБЪЯВЛЕНИЯ
		if($section == 'advs')
		{
			if($p == 'edit')
				$action = 'advEdit';
			if($p == 'advEditSubmit')
				$action = 'advEditSubmit';
			if($p == 'switchActive')
				$action = 'switchActive';
			if($p == 'deleteAdvMediaItem')
				$action = 'deleteAdvMediaItem';
			if($p == 'advSwitchStatus')
				$action = 'advSwitchStatus';
			if($p == 'approve')
				$action = 'approve';
			if($p == 'advAdminDeleteItem')
				$action = 'advAdminDeleteItem';
			if($p == 'prolong')
				$action = 'advProlong';
		}
		
		if($action)
			$CORE->action = $action;
	}
	
	
	
	function index()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		
		$CONTENT->setTitle('Личный кабинет');
		
		$MODEL['user'] = $USER;
		
		$params = array(
						'userId'=>$_SESSION['user']['id'], 
					);
		$MODEL['advs'] = AdvItem::getList($params);
		
		$crumbs = array();
		$crumbs[] = '<a href="'.Route::getByName(Route::MAIN)->url().'">Главная</a>';
		$crumbs[] = 'Личный кабинет';
		$MODEL['crumbs'] = $crumbs;
		
		Core::renderView('cabinet/indexForAuthorized.php', $MODEL);
	}
		
	
	
	#######################################
	############ 	ОБЪЯВЛЕНИЯ		#######
	#######################################
	function advs()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		
		$CONTENT->setTitle('Мои объявления - Личный кабинет');
		
		$MODEL['statusesToShow'] = AdvItem::$statusesToShow;
		$MODEL['chosenStatus'] = Status::code($_REQUEST['status']) ? Status::code($_REQUEST['status']) : null;
		
		$MODEL['dealTypes'] = DealType::$items;
		$MODEL['chosenDealType'] = DealType::code($_REQUEST['dealType']);
		
		if($USER )
		{
			$elPP = self::CABINET_ADVS_LIST_ELEMENTS_PER_PAGE;
			$page = intval($CORE->specialParams['p']) ? intval($CORE->specialParams['p'])-1 : 0;
			
			$limit=" LIMIT ".$page*$elPP.", ".$elPP."";
			//vd($limit);
			$MODEL['elPP'] = $elPP;
			$MODEL['page'] = $page;
			
			
			$params = array(
						'userId'=>$USER->id,
						'orderBy'=>'id DESC',
						'limit'=>$limit,
					);
			$statuses = array(
							Status::code(Status::ACTIVE),
							Status::code(Status::INACTIVE),
							Status::code(Status::MODERATION),
							Status::code(Status::EXPIRED),
						);
			if($MODEL['chosenStatus'])
				$statuses = array($MODEL['chosenStatus']);
			
			$MODEL['advs'] = AdvItem::getList($params, $statuses, $MODEL['chosenDealType']);
			$params['limit'] = '';
			$MODEL['totalCount'] = AdvItem::getCount($params, $statuses, $MODEL['chosenDealType']);
			foreach($MODEL['advs'] as $key=>$adv)
			{
				$adv->initCat();
				$adv->initMedia();
				$adv->initCity();
			}
		}
		else
			$MODEL['error'] = 'Вы не авторизованы.';
	
		$crumbs = array();
		$crumbs[] = '<a href="'.Route::getByName(Route::MAIN)->url().'">Главная</a>';
		$crumbs[] = '<a href="'.Route::getByName(Route::CABINET)->url().'">Личный кабинет</a>';
		$crumbs[] = 'Мои объявления';
		$MODEL['crumbs'] = $crumbs;
		
		Core::renderView('cabinet/advs/advsList.php', $MODEL);
	}
	
	
	
	
	
	
	
	function advEdit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		
		$CONTENT->setTitle('Редактирование объявления - Личный кабинет');
		
		$user = $USER;
		if($user)
		{
			$MODEL['cities'] = $_GLOBALS['cities'];
			//vd($getParams);
			//vd($CORE->params);
			
			# 	передан несуществующий id
			if(intval($CORE->params[2]))
			{
				$item = AdvItem::get($CORE->params[2]);
				if(!$item)
					$MODEL['error'] = 'ОШИБКА! Объявление не найдено. [a26]';
			}
			
			# 	принадлежит ли юзеру
			if(!$MODEL['error'] && $item && $item->userId!=$user->id)
				$MODEL['error'] = 'ОШИБКА! Объявление не найдено. [a52]';
		}
		else 
			$MODEL['error'] = 'Вам необходимо авторизоваться на сайте.';
		
		
		
		if($MODEL['error'])
		{
			Core::renderView('cabinet/advs/advEdit.php', $MODEL);
			return;
		}
			
		if($item)
		{
			$chosenCat = AdvCat::get($item->catId);
			
			$MODEL['chosenBrand'] = Brand::get($item->brandId);
			# 	список арт. номеров
			if($MODEL['chosenBrand'])
			{
				$MODEL['artnums'] = ArtNum::getList(Status::$items[Status::ACTIVE], $MODEL['chosenBrand']->id, $item->catId);
				# 	выбранный арт.номер
				$MODEL['chosenArtnum'] = ArtNum::get($item->artnumId);
			}
			
			$item->initMedia(Status::code(Status::ACTIVE));
			$item->user = $user;
			
			$MODEL['chosenCity'] = $MODEL['cities'][$item->cityId];
		}
		else
		{
			$chosenCat = AdvCat::get($CORE->specialParams['cat']);
			
			$MODEL['chosenCity'] = $_GLOBALS['city'];
		}
		//vd($item);
		
		$MODEL['item'] = $item;
		$MODEL['user'] = $user;
		
		$MODEL['chosenCat'] = $chosenCat;
		if($item || (!$item && $chosenCat))
		{
			$chosenCat->initClass(Status::code(Status::ACTIVE));
			if($chosenCat->class)
			{
				$chosenCat->class->initProps(Status::code(Status::ACTIVE));
				foreach($chosenCat->class->props as $prop)
					$prop->initTableColumns(Status::code(Status::ACTIVE));
			}
			
			if($item)
			{
				$item->cat = $chosenCat;
				$item->initValues();
			}
			
			$chosenCat->initProductVolumeUnits(Status::code(Status::ACTIVE));
			//vd($chosenCat);
			$MODEL['brands'] = Brand::getList(Status::code(Status::ACTIVE), $chosenCat->id);
			
			$crumbs = array();
			$crumbs[] = '<a href="'.Route::getByName(Route::MAIN)->url().'">Главная</a>';
			$crumbs[] = '<a href="'.Route::getByName(Route::CABINET)->url().'">Личный кабинет</a>';
			if($item)
				$crumbs[] = 'Редактирование объявления';
			else 
				$crumbs[] = 'Создание объявления';
			$MODEL['crumbs'] = $crumbs;
			
			Core::renderView('cabinet/advs/advEdit.php', $MODEL);
		}
		else 
		{
			$MODEL['cats'] = AdvCat::getFullCatsTree(Status::code(Status::ACTIVE));
			
			$crumbs = array();
			$crumbs[] = '<a href="'.Route::getByName(Route::MAIN)->url().'">Главная</a>';
			$crumbs[] = '<a href="'.Route::getByName(Route::CABINET)->url().'">Личный кабинет</a>';
			$crumbs[] = 'Создание объявления';
			$MODEL['crumbs'] = $crumbs;
			
			Core::renderView('cabinet/advs/advEditCatPick.php', $MODEL);
		}
	}
	
	
	
	
	
	function advEditSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		
		$CORE->setLayout(null);
		
		//vd($_REQUEST);
		$user = User::get($_SESSION['user']['id']);
		if(!$user)
		{
			$errors[] = Slonne::setError('', 'ОШИБКА! Вам необходимо авторизоваться!'); 
			echo'
			<script>
				window.top.markErrors('.json_encode($errors).', \'#adv-form\');
				window.top.$("#adv-form .info").html("'.$errors[0]['error'].'").slideDown("fast")
				
			</script>';
		}
		//vd($user);
		# 	проверка итема на существование
		if($_REQUEST['id'])
		{
			$edit = true;
			$item = AdvItem::get($_REQUEST['id']);
			if(!$item)
				$errors[] = Slonne::setError('', 'Ошибка! Позиция не найдена.');
			if($item->userId!=$user->id)
				$errors[] = Slonne::setError('', 'Ошибка! Позиция не найдена.');
		}
		else
			$item = new AdvItem();
		
		# 	проверка категории
		if(!count($errors))
		{
			$cat = AdvCat::get($_REQUEST['catId'], Status::code(Status::ACTIVE));
			if(!$cat)
				$errors[] = Slonne::setError('', 'Ошибка! Не передана категория');
		}
		
		if(!count($errors))
		{
			if(!$edit)
				$item->catId = $_REQUEST['catId'];
			
			$item->initCat();
			$item->cat->initClass(Status::code(Status::ACTIVE));
			//vd($item->cat);
			if($item->cat->class)
				$item->cat->class->initProps(Status::code(Status::ACTIVE));
	
			specialCharizeArray($_REQUEST);
			//vd($_REQUEST);
			$item->setValuesFromArray($_REQUEST);
			//vd($item);
					$item->status = Status::code(Status::MODERATION);
			$item->userId = $user->id;
			$item->cityId = $_REQUEST['city'];
			
			$validateResult = $item->validate();
			$errors = $errors ? array_merge($errors, $validateResult) : $validateResult;
			//vd($errors);
			if(!count($errors))
			{
				#	всё ок, сохраняем!
				$problem = $item->tryToInsertOrUpdateItemAndProps();
				if($problem)
					$errors[] = Slonne::setError('', $problem);
			}
		}
		//vd($errors);
		if(count($errors))
		{
			echo'
			<script>
				var errors = '.json_encode($errors).';
				window.top.markErrors(errors, \'#adv-form\');
				//window.top.$("#adv-form .info").html("'.$errors[0]['error'].'").slideDown("fast")
				
				var str="";
				for(var i in errors)
					str+=" - "+errors[i].error+"<br/>";
				window.top.error(str)
						
			</script>';
		}
		else
		{
			# 	обработка файлов
			Slonne::fixFILES();
			//$destDir = ROOT.'/'.UPLOAD_IMAGES_REL_DIR.AdvMedia::ADV_MEDIA_SUBDIR.'/'.$USER->id;
			$destDir = ROOT.'/'.UPLOAD_IMAGES_REL_DIR.AdvMedia::ADV_MEDIA_SUBDIR.'/';
			foreach($_FILES['pics'] as $key2=>$file)
			{
				$newFileName = Funx::generateName();
				$destDir .= AdvMedia::getSubdirsByFile($newFileName);
				vd($destDir);
				//echo '<hr />';
				$saveFileResult = AdvMedia::savePic($file, $destDir, $newFileName);
				if(!$saveFileResult['problem'])
				{
					$path = AdvMedia::getSubdirsByFile($newFileName).'/'.$saveFileResult['newFileName'];
					$am = new AdvMedia();
					$am->pid = $item->id;
					$am->status = Status::code(Status::ACTIVE);
					$am->path = $path;
						
					$am->insert();
				}
				else
					$warnings[] = $saveFileResult['problem'];
			}
			
			
			$successText = '';
			if(count($warnings))
			{
				$successText.='<p>Некоторые картинки загрузить не удалось: ';
				foreach($warnings as $w)
				{
					$successText.='<br/>'.$w['error'];
				}
			}
			vd($item->url());
			echo '
			<script>
			window.top.$("#adv-form + .success ").slideDown();
			window.top.$("#adv-form + .success>.warnings").html("'.$successText.'");
			window.top.$("#adv-form").slideUp();
			window.top.$("#view-adv-btn").attr("href", "'.str_replace("'", '', $item->url()).'")
			</script>';
		}

		echo '<script>window.top.submitEnd()</script>';
	}
	
	
	
	function advSwitchStatus()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		
		$CORE->setLayout(null);
	
		$user = $USER;
		//vd($_GLOBALS);
		$error = '';
		
		$item = AdvItem::get($_REQUEST['id']);
		if($item)
		{
			if($item->userId == $user->id)
			{
				# 	какие статусы дозволительно изменять юзеру
				if(in_array($item->status->code, array(
													Status::code(Status::ACTIVE)->code,
													Status::code(Status::INACTIVE)->code,
				)))
				{
					$statusToBe = $item->status->code == Status::code(Status::ACTIVE)->code ? Status::code(Status::INACTIVE) : Status::code(Status::ACTIVE);
					//vd($statusToBe);
						
					$item->setStatus($statusToBe);
				}
				else 
					$error='Вы не можете сменить статус у этого объявления';
			}
			else
				$error='Вы не можете сменить статус у этого объявления';
		}
		else
			$error='Ошибка! Не не найдено объявление '.$_REQUEST['catId'].'';
	
		$json['error'] = $error;
		$json['status'] = $statusToBe;
	
		echo json_encode($json);
	}
	
	
	
	
	function approve()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		$CORE->setLayout(null);
	
		$errors = null;
		if($ADMIN->hasRole(Role::ADV_MODERATOR) )
		{
			if(Admin::isAdmin())
			{
				$item = AdvItem::get($_REQUEST['id']);
				if($item)
				{
					if($item->status->code == Status::code(Status::MODERATION)->code )
					{
						$statusToBe = Status::code(Status::ACTIVE);
						$item->setStatus($statusToBe);
						
						# 	записываем в журнал событий
						$je = new JournalEntry();
						$je->objectType = Object::code(Object::ADV);
						$je->objectId = $item->id;
						$je->journalEntryType = JournalEntryType::code(JournalEntryType::APPROVE);
						$je->comment = 'Одобрено';
						$je->adminId = $ADMIN->id;
						$je->insert();

						#   оповещение клиента
                        $user = User::get($item->userId);
                        $m = new Mail();
                        $m->to = $item->email;
                        $m->from = ROBOT_EMAIL;
                        $m->subject = 'Ваше объявление ОДОБРЕНО! '.DOMAIN_FIRST_CAPITAL;
                        $m->msg = Mail::advApproved([
                            'name'=>$user->name.' '.$user->fathername,
                            'adv'=>$item,
                        ]);
                        //vd($m);
                        $m->send();

					}
					else
						$errors[] = new Error('Объявление не находится в модерации.');
				}
				else
					$errors[] = new Error('Ошибка! Не не найдено объявление '.$_REQUEST['catId'].'');
			}
			else
				$errors[] = new Error('У вас нет прав на удаление.');
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
	
		$json['errors'] = $errors;
		$json['status'] = $statusToBe;
	
		echo json_encode($json);
	}
	
	
	
	
	
	function advProlong()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		$CORE->setLayout(null);
	
		$errors = null;
		
		$item = AdvItem::get($_REQUEST['id']);
		if($item )
		{
			if($item->status->code == Status::EXPIRED )
			{
				$item->status = Status::code(Status::ACTIVE);
				//vd($item->status);
				
				$date = new DateTime();
				$date->modify('+'.AdvItem::DAYS_TILL_EXPIRATION.'  day');
				$item->dateExpired = $date->format('Y-m-d H:i:s');
				
				$item->update();
			}
			else
				$errors[] = new Error('Объявление не может быть продлено, т.к. не просрочено.');
		}
		else
			$errors[] = new Error('Ошибка! Объявление не найдено.'); 
	
		$json['errors'] = $errors;

		echo json_encode($json);
	}
	
	
	
	
	
	function advAdminDeleteItem()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		$CORE->setLayout(null);
	
		$errors = null;
		if($ADMIN->hasRole(Role::ADV_MODERATOR) )
		{
			if(Admin::isAdmin())
			{
				$item = AdvItem::get($_REQUEST['id']);
				if($item)
				{
					$item->status = Status::code(Status::DELETED);
					$item->reason = $_REQUEST['reason'];
					$item->update();

                    # 	записываем в журнал событий
                    $je = new JournalEntry();
                    $je->objectType = Object::code(Object::ADV);
                    $je->objectId = $item->id;
                    $je->journalEntryType = JournalEntryType::code(JournalEntryType::DELETE);
                    $je->comment = 'Удалено. (причина: '.($item->reason ? $item->reason : 'не указана').')';
                    $je->adminId = $ADMIN->id;
                    $je->insert();

                    #   оповещение клиента
                    $user = User::get($item->userId);
                    $m = new Mail();
                    $m->to = $item->email;
                    $m->from = ROBOT_EMAIL;
                    $m->subject = 'Ваше объявление удалено модератором. '.DOMAIN_FIRST_CAPITAL;
                    $m->msg = Mail::advDeleted([
                        'name'=>$user->name.' '.$user->fathername,
                        'adv'=>$item,
                    ]);
                    //vd($m);
                    $m->send();

				}
				else
					$errors[] = new Error('Ошибка! Не не найдено объявление '.$_REQUEST['catId'].'');
			}
			else
				$errors[] = new Error('У вас нет прав на удаление.');
		}
		else 
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
	
		$json['errors'] = $errors;
		$json['status'] = $item->status;
	
		echo json_encode($json);
	}
	
	
	
	function deleteAdvMediaItem()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		
		$CORE->setLayout(null);
	
		$error = '';
		$user = $USER;
		//vd($_REQUEST);
		$am = AdvMedia::get($_REQUEST['id']);
		
		if($am)
		{
			$item = AdvItem::get($am->pid);
			if($item->userId==$user->id)
				$am->delete();
			else
				$error = "Ошибка! Нет прав.";
		}
		else
			$error = "Ошибка! Картинка не найдена.. Пожалуйста, обновите страницу.";
		
		$json['error'] = $error;
	
		echo json_encode($json);
	}
	
	
	
	
	#######################################
	############ 	ПРОФИЛЬ		###########
	#######################################
	function profileEdit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
	
		$CONTENT->setTitle('Редактирование - Личный кабинет');
		
		$MODEL['user'] = $USER;
		
		$crumbs = array();
		$crumbs[] = '<a href="'.Route::getByName(Route::MAIN)->url().'">Главная</a>';
		if($MODEL['user'])
		{
			$crumbs[] = '<a href="'.Route::getByName(Route::CABINET)->url().'">Личный кабинет</a>';
			$crumbs[] = 'Редактирование личных данных';
		}
		else
			$crumbs[] = 'Регистрация';
		$MODEL['crumbs'] = $crumbs;
		
		Core::renderView('cabinet/profile/edit.php', $MODEL);
	}

	
	function profileEditSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		$CORE->setLayout(null);
		
		$errors = null;
		//vd($_REQUEST);
		//usleep(800000);
		$sessionUser = $USER;
		//echo "!";
		if($_REQUEST['id'])
		{
			if($_REQUEST['id'] != $sessionUser->id)
				$errors[] = new Error('Ошибка! Пользователь не найден. [a26]');
			
			$user = User::get($_REQUEST['id']);
			if(!$user)
				$errors[] = new Error('Ошибка! Пользователь не найден.');
		}
		else
			$user = new User();
		
		if(!$errors)
		{
			specialCharizeArray($_REQUEST);
			
			$user->surname = trim($_REQUEST['surname']);
			$user->name = trim($_REQUEST['name']);
			$user->fathername = trim($_REQUEST['fathername']);
			$user->phone = trim($_REQUEST['phone']);
			$user->email = trim($_REQUEST['email']);
			$user->cityId = trim($_REQUEST['city']);
			if(!$user->id)
			{
				$user->password = trim($_REQUEST['pass']);
				$user->password2 = trim($_REQUEST['pass2']);
				$user->status = Status::code(Status::INACTIVE);
				$user->salt = Funx::getSalt(20);					
			}
			
			$errors = $user->validate();
			
			# 	капча 
			if(!$user->id && $_REQUEST['captcha'] != $_SESSION['captcha_keystring'] )
			{
				$errors[] = new Error('Вы ввели неверный код с картинки.', 'captcha');
				if($errors[0]->field=='captcha')
					$refreshCaptcha = true; 
			}
		}

		
		if(!$errors)
		{
			if($user->id)
				$user->update();
			else
			{
				$user->password = User::encryptPassword($user->password);
				$user->insert();
				#	отсылка письма с активацией
				/*$p = Page::get(11);
				$msg = $p->attrs['descr'];
				$msg = str_replace('_SITE_', $_SERVER['HTTP_HOST'], $msg);
				$msg = str_replace('_LINK_', 'http://'.$_SERVER['HTTP_HOST'].'/'.LANG.'/cabinet/profile/activate/'.$user->salt, $msg);
				Funx::sendMail($user->email, 'robot@'.$_SERVER['HTTP_HOST'], 'Активация учётной записи на сайте '.$_SERVER['HTTP_HOST'], $msg);*/
				
				$m = new Mail();
				$m->to = $user->email;
				$m->from = ROBOT_EMAIL;
				$m->subject = 'Активация учётной записи на сайте '.DOMAIN_CAPITAL;
				$arr = array(
						'name'=>$u->name.' '.$u->fathername,
						'url'=>'http://'.$_SERVER['HTTP_HOST'].'/cabinet/profile/activate/'.$user->salt,
				);
				$m->msg = Mail::getMsgForRegistration($arr);
				
				$m->send();
				
			}
			
			echo '
			<script>
				window.top.$("#edit-form + .success ").slideDown();
				window.top.$("#edit-form").slideUp();
			</script>';
			
		}
		else
		{
			//echo "!";
			
			echo'
			<script>
				window.top.showErrors('.json_encode($errors).');
			</script>';
			
			/*echo '
			<script>
				window.top.Cabinet.showProfileErrors('.json_encode($errors).'); 
				window.top.$("#edit-form .loading").css("display", "none")
			</script>';*/
			if($refreshCaptcha)
			{
				echo '
				<script>window.top.$("#captcha-pic").attr("src", "/'.INCLUDE_DIR.'/kcaptcha/?"+(new Date()).getTime());</script>';
			}
		}
		
	}
	
	
	
	
	function profileActivate()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
	
		$CONTENT->setTitle('Активация учётной записи - Личный кабинет');
		
		$salt = trim($CORE->params[2]);
		
		if($salt)
		{
			$user = User::getBySalt($salt);
			
			if($user)
			{
				if($user->status->num == Status::code(Status::ACTIVE)->num)
					$MODEL['error'] = 'Этот аккаунт уже был активирован!';
				elseif($user->status->num != Status::code(Status::INACTIVE)->num)
					$MODEL['error'] = 'Ошибка! Аккаунт не может быть активирован.';
				else
				{
					$user->setStatus(Status::code(Status::ACTIVE));
					
					$MODEL['notice'] = $_CONST['NOTICE успешная активация аккаунта'];
				}
			}
			else
				$MODEL['error']= $_CONST['ERROR код активации не найден'];
		}
		else
			$MODEL['error']= $_CONST['ERROR код активации не найден'];
	
	
		Core::renderView('cabinet/profile/activate.php', $MODEL);
	}
	
	
	
	function profileAuthSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		//Startup::execute(Startup::FRONTEND);
		
		$CORE->setLayout(null);
		
		//usleep(800000);
		$errors = array(); 
		
		$email = trim($_REQUEST['email']);
		$pass = trim($_REQUEST['password']);
	
		$errors = array();
		if(!$email)
			$errors[] = Slonne::setError('email', 'Укажите e-mail!');
		if(!$pass)
			$errors[] = Slonne::setError('password', 'Укажите пароль!');
	
		if(!count($errors))
		{
			if($u = User::getByEmailAndPassword($email, User::encryptPassword($pass), Status::code(Status::ACTIVE)))
				$_SESSION['user']['id'] =  $u->id;
			else
			{
				$errors[] = Slonne::setError('email', 'Неверная пара "e-mail и пароль".');
				$errors[] = Slonne::setError('password', 'Неверная пара "e-mail и пароль".');
			}
		}
	
		$res['errors'] = $errors;
	
		echo json_encode($res);
	
	}
	
	
	
	function profileLogout()
	{
		//require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		//Startup::execute(Startup::FRONTEND);
		unset($_SESSION['user']);
	}
	
	
	function profilePasswordChange()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		
		$CONTENT->setTitle('Смена пароля - Личный кабинет');
		
		$MODEL['user'] = $USER;
		
		$crumbs = array();
		$crumbs[] = '<a href="'.Route::getByName(Route::MAIN)->url().'">Главная</a>';
		$crumbs[] = '<a href="'.Route::getByName(Route::CABINET)->url().'">Личный кабинет</a>';
		$crumbs[] = 'Смена пароля';
		$MODEL['crumbs'] = $crumbs;
		//echo "!";
		Core::renderView('cabinet/profile/passwordChange.php', $MODEL);
	}
	
	
	function profilePasswordChangeSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		
		$CORE->setLayout(null); 
		
		$errors = array();
		$user = $USER;
		
		if(!$user)
			$errors[] = Slonne::setError('', 'Вы не авторизованы.');
		else
		{
			$oldPass = strPrepare($_REQUEST['old_pass']);
			$pass = strPrepare($_REQUEST['pass']);
			$pass2 = strPrepare($_REQUEST['pass2']);
			
			if(!$oldPass )
				$errors[] = Slonne::setError('old_pass', 'Введите старый пароль!');
			if(!$pass)
				$errors[] = Slonne::setError('pass', 'Введите новый пароль!');
			if(!$pass2)
				$errors[] = Slonne::setError('pass2', 'Подтвердите новый пароль!');
			if($pass && $pass2 && $pass!=$pass2)
			{
				$errors[] = Slonne::setError('pass', 'Пароль и подтверждение должны совпадать!');
				$errors[] = Slonne::setError('pass2', 'Пароль и подтверждение должны совпадать!');
			}
			
			if(!count($errors))
			{
				if($user->password != User::encryptPassword($oldPass))
					$errors[] = Slonne::setError('old_pass', 'Старый пароль введён неверно.');
				elseif(User::encryptPassword($pass) == $user->password)
				{
					$errors[] = Slonne::setError('pass', 'Новый пароль не должен совпадать со старым.');
					$errors[] = Slonne::setError('pass2', 'Новый пароль не должен совпадать со старым.');
				}
				else
				{
					$user->password = User::encryptPassword($pass);
					$user->update();
				}
			}
			
		}
		
		
		$json['errors'] = $errors;
		echo json_encode($json);
	}
	
	
	
	
	function profilePasswordResetClaim()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		
		$CONTENT->setTitle('Восстановление пароля - Личный кабинет');
		
		Core::renderView('cabinet/profile/passwordResetClaim.php', $MODEL);
	}
	
	
	
	function passwordResetSendClaimAjax()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		//Startup::execute(Startup::FRONTEND);
		
		$CORE->setLayout(null);
		
		$errors = array();
		
		if($email = trim($_REQUEST['email']))
		{
			if($user = User::getByEmail($email, Status::code(Status::ACTIVE)))
			{
				$user->salt = Funx::getSalt(22);
				$user->update();
				//vd($user->salt); 
				#	отсылка письма с активацией
				/*$p = Page::get(22);
				$msg = $p->attrs['descr'];
				$msg = str_replace('_NAME_', $user->name.' '.$user->fathername, $msg);
				$msg = str_replace('_SITE_', $_SERVER['HTTP_HOST'], $msg);
				$msg = str_replace('_LINK_', 'http://'.$_SERVER['HTTP_HOST'].'/cabinet/profile/password_reset/'.$user->email.'/'.$user->salt, $msg);
				Funx::sendMail($user->email, 'robot@'.$_SERVER['HTTP_HOST'], 'Восстановление пароля на  '.$_SERVER['HTTP_HOST'], $msg);*/
				
				$m = new Mail();
				$m->to = $user->email;
				$m->from = ROBOT_EMAIL;
				$m->subject = 'Восстановление пароля на '.DOMAIN_CAPITAL;
				$arr = array(
						'name'=>$user->name.' '.$user->fathername,
						'url'=>'http://'.$_SERVER['HTTP_HOST'].'/cabinet/profile/password_reset/'.$user->email.'/'.$user->salt,
				);
				$m->msg = Mail::getMsgForPasswordResetClaim($arr);
				
				$m->send();
			}
			else
				$errors[] = Slonne::setError('email', 'Указанный Вами e-mail ещё не зарегистрирован. <br>Для дальнейшей работы пройдите <a href="/profile/edit/">регистрацию</a>.  ');
		}
		else 
			$errors[] = Slonne::setError('email', 'Пожалуйста, укажите Вам e-mail.');

		$json['errors'] = $errors;
		echo json_encode($json);
	}
	
	
	
	
	function profilePasswordReset()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
	
		$CONTENT->setTitle('Восстановление пароля - Личный кабинет');
		
		$email = trim($CORE->params[2]);
		$salt = trim($CORE->params[3]);
		
		if($email && $salt)
		{
			$user = User::getByEmail($email, Status::code(Status::ACTIVE));
			if($user && $user->salt == $salt)
			{
				$newPassword = Funx::getSalt(5);
				$user->password = User::encryptPassword($newPassword);
				$user->salt = Funx::getSalt(24);
				$user->update();
				
				# 	письмо с новым паролем.
				/*$p = Page::get(23);
				$msg = $p->attrs['descr'];
				$msg = str_replace('_NAME_', $user->name.' '.$user->fathername, $msg);
				$msg = str_replace('_SITE_', $_SERVER['HTTP_HOST'], $msg);
				$msg = str_replace('_EMAIL_', $user->email, $msg);
				$msg = str_replace('_PASSWORD_', $newPassword, $msg);
				Funx::sendMail($user->email, 'robot@'.$_SERVER['HTTP_HOST'], 'Восстановление пароля на  '.$_SERVER['HTTP_HOST'], $msg);*/
				
				$m = new Mail();
				$m->to = $user->email;
				$m->from = ROBOT_EMAIL;
				$m->subject = 'Восстановление пароля на '.DOMAIN_CAPITAL;

				$m->msg = Mail::getMsgForNewPasswordInfo([
                    'name'=>$user->name.' '.$user->fathername,
                    'email'=>$user->email,
                    'password'=>$newPassword,
                ]);
				
				$m->send();
			}
			else
				$MODEL['error'] = 'Ошибка! Неверная ссылка восстановления. Пожалуйста, обратитесь к разработчикам.';
		}
		else
			$MODEL['error'] = 'Ошибка! Неверная ссылка восстановления. Пожалуйста, обратитесь к разработчикам.';
		
		
		$MODEL['newPass'] = $newPassword;
		Core::renderView('cabinet/profile/passwordReset.php', $MODEL);
	}
	
	
}

?>