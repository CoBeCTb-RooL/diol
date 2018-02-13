<?php
class UserController extends MainController{
	
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
		
		Core::renderView('users/indexView.php', $model);
	}
	
	
	
	
	function list1()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		if($ADMIN->hasRole(Role::USERS_MODERATOR) )
		{
			$p = $_REQUEST['p'] ? $_REQUEST['p'] : 1 ;
			$elPP = 10;
			
			$from = ($p-1)*$elPP;
			$status = Status::num($_REQUEST['status']);
			
			$MODEL['email'] = trim($_REQUEST['email']);
			$MODEL['orderBy'] = $_REQUEST['orderBy'] ? $_REQUEST['orderBy'] : 'id';
			$MODEL['desc'] = $_REQUEST['desc'];
			//$status = Status::code(Status::ACTIVE);
			
			$params = array(
							'status' => $status,
							'email'=>$MODEL['email'],
							'from' => $from,
							'count' => $elPP,
							'orderBy' => $MODEL['orderBy'],
							'desc' =>$MODEL['desc'],
						);
			//vd($params);
			$users = User::getList($params);
			
			
			$cities = City::getList();
			foreach($users as $u)
				$u->initCity($cities);
	
			$MODEL['users'] = $users;
			$MODEL['totalCount'] = User::getCount($params); 
			$MODEL['p'] = $p;
			$MODEL['elPP'] = $elPP;
			$MODEL['orderBy'] = $_REQUEST['orderBy'] ? $_REQUEST['orderBy'] : 'id';
			$MODEL['desc'] = $_REQUEST['desc'];
			$MODEL['status'] = $status;
		}
		else 
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
		
		//vd($cities);
		//vd($model);
	
		Core::renderView('users/list.php', $MODEL);
	}
	
	
	
	
	function view()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		if($ADMIN->hasRole(Role::USERS_MODERATOR) )
		{
			$MODEL['user'] = User::get($_REQUEST['id']);
		}
		else 
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
		
		
		Core::renderView('users/view.php', $MODEL);
	}
	
	
	
	
	function setStatus()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null);
	
		$errors = null;
		
		if(Admin::isAdmin())
		{
			$user = User::get($_REQUEST['userId']);
			if($user)
			{
				$status = Status::code($_REQUEST['status']);
				if($status)
				{
					$prevStatus = $user->status->code;
					if($prevStatus!=$status->code)
					{
						$user->setStatus($status);
						
						$je = new JournalEntry();
						$je->objectType = Object::code(Object::USER);
						$je->objectId = $user->id;
						$je->journalEntryType = JournalEntryType::code(JournalEntryType::STATUS_CHANGED);
						$je->comment = 'Смена статуса с "'.$prevStatus.'" на "'.$status->code.'"';
						$je->adminId = $ADMIN->id;
							
						$je->insert();
					}
				}
				else
					$errors[] = Slonne::setError('qqq', 'Ошибка! Левый статус. ['.$_REQUEST['status'].']');
			}
			else
				$errors[] = Slonne::setError('qqq', 'Ошибка! Комментарий не найден.');
		}
		else
			$errors[] = Slonne::setError('qqq', 'Ошибка! Нет прав.');
		
		$res['errors'] = $errors;
		$res['status'] = $status->code;
		echo json_encode($res);
	}
	
	
	
	
	
}




?>