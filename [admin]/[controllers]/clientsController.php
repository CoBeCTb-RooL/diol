<?php
class ClientsController extends MainController{
	
	function routifyAction()
	{
		global $CORE;
		$section = $CORE->params[0];
		$p = $CORE->params[1];
	
		if($section == 'list')
			$action='list1';
	
		if($action)
			$CORE->action = $action;
	}
	
	
	
	
	function index()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);

		Core::renderView('clients/indexView.php', $model);
	}
	
	
	function list1()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
		
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			//vd($_REQUEST);
			$MODEL['p'] = $_REQUEST['p'] ? $_REQUEST['p'] : 1;
			$MODEL['elPP'] = $_REQUEST['elPP'] ? $_REQUEST['elPP'] : 10;

			$params =[
				'phone' => $_REQUEST['phone'],
				'email' => $_REQUEST['email'],
			];
			$MODEL['totalCount'] = Client::getCount($params);

			$params['from'] = ($MODEL['p']-1) * $MODEL['elPP'];
			$params['count'] = $MODEL['elPP'];
			$MODEL['list'] = Client::getList($params);
			$MODEL['params'] = $params;
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;

		Core::renderView('clients/list.php', $MODEL);
	}


	
	
	function switchStatus()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		$errors = null;
		//vd($_REQUEST);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$cat = Client::get($_REQUEST['id']);
			if($cat)
			{
				$statusToBe = $cat->status->code == Status::ACTIVE ? Status::code(Status::INACTIVE) : Status::code(Status::ACTIVE);
				//vd($statusToBe);
	
				$cat->status = $statusToBe;
				$cat->update();
			}
			else
				$errors[] = new Error('Ошибка! Не найдена категория '.$_REQUEST['id'].'');
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
	
			$json['errors'] = $errors;
			$json['status'] = $statusToBe;
	
			echo json_encode($json);
	}
	
	
	function edit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR))
		{
			$MODEL['item'] = Client::get($_REQUEST['id']);
			if($MODEL['item'])
				$MODEL['item']->initMedia();
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
	
		Core::renderView('clients/edit.php', $MODEL);
	}
	
	
	
	function editSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		//usleep(800000);
		$errors = null;
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			if($_REQUEST['clientId'] = intval($_REQUEST['clientId']))
			{
				$cat = Client::get($_REQUEST['clientId']);
				if(!$cat)
					$errors[] = Slonne::setError('', 'Ошибка! Категория не найдена '.$_REQUEST['clientId'].'');
			}
			else
				$cat = new Client();
					
				if(!count($errors))
				{
					$cat->setData($_REQUEST);
//					vd($cat);
					$errors = $cat->validate();
					if(!count($errors))
					{
						if($cat->id)
							$cat->update();
						else
							$cat->id = $cat->insert();

						# 	обрабатываем медию
						//vd($cat);
						Slonne::fixFILES();
						//vd($_FILES);
						if(count($_FILES['media']))
							foreach ($_FILES['media'] as $media)
							{
								$destDir = ROOT.'/'.UPLOAD_IMAGES_REL_DIR.'clients/';
								$newFileName = Funx::generateName();
								$destDir .= Funx::getSubdirsByFile($newFileName);
								//vd($destDir);
								//echo '<hr />';
								$saveFileResult = Media2::savePic($media, $destDir, $newFileName);
								if(!$saveFileResult['problem'])
								{
									$path = 'clients/'.Funx::getSubdirsByFile($newFileName).'/'.$saveFileResult['newFileName'];
									$m = new Media2();
									$m->objId = $cat->id;
									$m->objType = Object::CLIENT;
									$m->status = Status::code(Status::ACTIVE);
									$m->path = $path;
									$m->idx = 9999;

									$m->insert();
								}
								else
									$warnings[] = $saveFileResult['problem'];
							}
					}

					//vd($_REQUEST);
					foreach ($_REQUEST['media'] as $mediaId=>$text)
					{
						//vd($mediaId);
						//vd($text);
						$m = Media2::get($mediaId);
						$m->title = trim(strPrepare($text));
						$m->update();
						//vd($m);
					}
				}
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
	
			$json['errors']=$errors;
			echo '<script>window.top.editSubmitComplete('.json_encode($json).')</script>';
	
	}
	
	
	function listSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		$errors = null;
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			//usleep(800000);
			foreach($_REQUEST['idx'] as $clientId=>$val)
			{
				if($val = intval($val))
					Client::setIdx($clientId, $val);
			}
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
	
			$json['errors']=$errors;
	
			echo '<script>window.top.listSubmitComplete('.json_encode($json).')</script>';
	}
	
	
	
	function unitsList()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$MODEL['cat'] = Client::get($_REQUEST['id']);
			$MODEL['cat']->initProductVolumeUnits();
			$MODEL['units'] = ProductVolumeUnit::getList();
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
	
		Core::renderView('clients/unitsList.php', $MODEL);
	}
	
	
	
	function unitClick()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
		
		$errors = null;
		
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$cat = Client::get($_REQUEST['clientId']);
			if($cat)
			{
				$unit = ProductVolumeUnit::get($_REQUEST['unitId']);
				if($unit)
				{
					$checked = $_REQUEST['checked'] ? true : false;
					$catUnitCmb = CatProductVolumeUnitCmb::get($cat->id, $unit->id);
					if($checked)
					{
						if(!$catUnitCmb)
						{
							$catUnitCmb = new CatProductVolumeUnitCmb();
							$catUnitCmb->clientId = $cat->id;
							$catUnitCmb->unitId = $unit->id;
							$catUnitCmb->insert();
							
							$jeType = JournalEntryType::code(JournalEntryType::CATEGORY_CREATE_PRODUCT_UNIT_CMB);
							$msg = 'Добавлена единица изм. "'.$unit->name.'"(id:'.$unit->id.')';
							
							$toJournal = true;
						}
					}
					else
					{
						if($catUnitCmb) 
						{
							$catUnitCmb->delete();
							$jeType = JournalEntryType::code(JournalEntryType::CATEGORY_DELETE_PRODUCT_UNIT_CMB);
							$msg = 'Удалена единица изм. "'.$unit->name.'"(id:'.$unit->id.')';
							
							$toJournal = true;
						}
					}
					
					if($toJournal )
					{
						//vd($jeType);
						# 	записываем в журнал событий
						$je = new JournalEntry();
						$je->objectType = Object::code(Object::CATEGORY);
						$je->objectId = $cat->id;
						$je->journalEntryType = $jeType;
						$je->comment = $msg;
						$je->param1 = $unit->id;
						$je->adminId = $ADMIN->id;
						$je->insert();
					}
				}
				else
					$errors[] = new Error('ОШИБКА! Мера не найдена.');
			}
			else 
				$errors[] = new Error('ОШИБКА! Категория не найдена.');
			
			$cat->initProductVolumeUnits();
		}
		else 
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
		
		$json['cat'] = $cat;
		$json['errors'] = $errors;
		$json['checked'] = $checked;
		
		echo json_encode($json);
	}






	function deleteMedia()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);

		$errors = null;

		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$media = Media2::get($_REQUEST['id']);
			if($media)
			{
				$media->delete();
			}
			else
				$errors[] = new Error('ОШИБКА! Медиа не найдено.');
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);

		$json['errors'] = $errors;

		echo json_encode($json);
	}
	
	
	
}




?>