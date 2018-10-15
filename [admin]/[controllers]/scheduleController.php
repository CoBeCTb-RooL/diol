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

		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR | Role::DOCTOR) )
		{
			//vd($_REQUEST);
			$params = $_REQUEST;
			$MODEL['p'] = $_REQUEST['p'] ? $_REQUEST['p'] : 1;
			$MODEL['elPP'] = $_REQUEST['elPP'] ? $_REQUEST['elPP'] : 10;

			$MODEL['totalCount'] = ScheduleEntry::getCount($params);

			if($ADMIN->isDoctor())
                $params['doctorId'] = $ADMIN->id;
			
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

			$MODEL['doctors'] = Admin::getActiveDoctors();
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
			if($_REQUEST['editOpts']['id'])
			{
				$MODEL['item'] = ScheduleEntry::get($_REQUEST['editOpts']['id']);
				if($MODEL['item'])
					$MODEL['item']->initClient();
			}

			$MODEL['doctors'] = Admin::getActiveDoctors();

			$MODEL['services'] = Service::getList(['active'=>1, ]);

			$MODEL['editOpts'] = $_REQUEST['editOpts'];

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

		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR | Role::DOCTOR) )
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



    function delete()
    {
        require(GLOBAL_VARS_SCRIPT_FILE_PATH);
        Startup::execute(Startup::ADMIN);
        $CORE->setLayout(null);

        if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR | Role::SUPER_ADMIN))
        {
            if($_REQUEST['id'])
            {

                $entry = ScheduleEntry::get($_REQUEST['id']);
                if($entry)
                {
                    ScheduleEntry::delete($entry->id);
                }
            }

        }

//        Core::renderView('schedule/edit.php', $MODEL);
    }







}




?>