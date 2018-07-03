<?php
class ScheduleController extends MainController{
	
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

		Core::renderView('schedule/indexView.php', $model);
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
			$MODEL['totalCount'] = ScheduleEntry::getCount($params);

			$params['from'] = ($MODEL['p']-1) * $MODEL['elPP'];
			$params['count'] = $MODEL['elPP'];
			$MODEL['list'] = ScheduleEntry::getList($params);
			$MODEL['params'] = $params;

			$listByTimes = array_flip(ScheduleEntry::timeArr());
			foreach ($listByTimes as $time=>$q)
				$listByTimes[$time]=[];


			foreach ($MODEL['list'] as $entry)
			{
				$entry->initClient();
				$entry->initService();
				$entry->initDoctor();
				$listByTimes[$entry->getTime()][] = $entry;
			}
			$MODEL['listByTimes'] = $listByTimes;
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;

		Core::renderView('schedule/list.php', $MODEL);
	}




	function edit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);

		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR | Role::DOCTOR))
		{
			if($_REQUEST['id'])
			{
				$MODEL['item'] = ScheduleEntry::get($_REQUEST['id']);
				if($MODEL['item'])
					$MODEL['item']->initClient();
			}

			$tmp = Admin::getList();
			$doctors = [];
			foreach ($tmp as $v)
			{
				$v->initGroup();
				if($v->hasRole(Role::DOCTOR) && $v->status->code == Status::ACTIVE)
					$doctors[] = $v;
			}
			$MODEL['doctors'] = $doctors;

			$MODEL['services'] = Service::getList(['active'=>1, ]);

			/*if($MODEL['item'])
				$MODEL['item']->initMedia();*/
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;

		Core::renderView('schedule/edit.php', $MODEL);
	}



	function editSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);

		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			vd($_REQUEST);
			if($_REQUEST['scheduleEntryId'])
				$item = ScheduleEntry::get($_REQUEST['scheduleEntryId']);
			else
				$item = new ScheduleEntry();

			$item->setData($_REQUEST);

			$errors = $item->validate();
			if(!count($errors))
			{
				if($item->id)
					$item->update();
				else
					$item->id = $item->insert();
			}
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);

		$json['errors']=$errors;
		echo '<script>window.top.editSubmitComplete('.json_encode($json).')</script>';

		die;


		//usleep(800000);
		$errors = null;
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			if($_REQUEST['clientId'] = intval($_REQUEST['clientId']))
			{
				$cat = ScheduleEntry::get($_REQUEST['clientId']);
				if(!$cat)
					$errors[] = Slonne::setError('', 'Ошибка! Категория не найдена '.$_REQUEST['clientId'].'');
			}
			else
				$cat = new ScheduleEntry();

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
								$destDir = ROOT.'/'.UPLOAD_IMAGES_REL_DIR.'schedule/';
								$newFileName = Funx::generateName();
								$destDir .= Funx::getSubdirsByFile($newFileName);
								//vd($destDir);
								//echo '<hr />';
								$saveFileResult = Media2::savePic($media, $destDir, $newFileName);
								if(!$saveFileResult['problem'])
								{
									$path = 'schedule/'.Funx::getSubdirsByFile($newFileName).'/'.$saveFileResult['newFileName'];
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




	public function clientsSearch()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);

		$ret = [];
		$list = [];
		$error = null;

		$surname = trim($_REQUEST['surname']);
		$name = trim($_REQUEST['name']);
		$fatherName = trim($_REQUEST['fatherName']);
		$phone = trim($_REQUEST['phone']);

//		if($txt)
//		{
			$list = Client::getList([
				'status'=>Status::code(Status::ACTIVE),
				'surnameLike' =>$surname,
				'nameLike' =>$name,
				'fatherNameLike' =>$fatherName,
				'phoneLike' =>$phone,
				//'search'=>$txt,
			]);
//		}
//		else
//			$error = 'Пустое слово';
		//echo "!!!";

		$ret['error'] = $error;
		$ret['list'] = $list;
		header('Content-Type: application/json');
		echo json_encode($ret);
	}





	
	
	
}




?>