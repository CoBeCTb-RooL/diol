<?php

$ACTION = $_PARAMS[0];


if($ACTION == 'list')
	$ACTION = 'list1';




class AuthController extends MainController{
	
	
	function routifyAction()
	{
		global $CORE;
		$section = $CORE->params[0];
		$p = $CORE->params[1];

		if($p == 'list')
			$action='list1';

		if($action)
			$CORE->action = $action;
	}
	
	
	
	function index()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout('authLayout.php');
		
	}
	
	
	function auth()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		//Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null);
		
		$error = '';
		$state = '';
		
		
		if(time() < $_SESSION['admin']['nextTryTS'])
		{
			$result['delay'] = ''.($_SESSION['admin']['nextTryTS'] - time()).' сек.';
			$state = 'tries_limit';
		}
		else
		{
			$email = trim($_REQUEST['email']);
			$password = trim($_REQUEST['password']);
			if(!$email || !$password)
				$error = 'Заполните все поля!';
			else
			{
				$admin = Admin::getByEmailAndPassword($email, $password, Status::code(Status::ACTIVE));
				//vd($admin);
				#	проверка группы
				if($admin)	
				{
					if($admin->groupId)
					{
						$admin->initGroup(Status::code(Status::ACTIVE));
						//vd($admin);
						if(!$admin->group)	
							$admin = null;
					}
				}
				
				if(!$admin)
				{
					$state = 'not_found';
					$_SESSION['admin']['authTries'] ++ ;
					$result['triesRemain'] = Admin::AUTH_TRIES_LIMIT - $_SESSION['admin']['authTries'];
					$_SESSION['admin']['nextTryTS'] = time() + Admin::SECONDS_DELAY_STEP * ($_SESSION['admin']['authTries'] - Admin::AUTH_TRIES_LIMIT) ;
				}
				else
				{
					//echo "!";
					//vd($admin);
					$_SESSION['admin']['id'] = $admin->id;
					//vd($_SESSION['admin']['id']);
					$state = 'ok';
					$admin->setLastAuth();
				}
			}
		}
		//vd($result);
		$result['error'] = $error;
		$result['result'] = $state;
		
		
		
		echo json_encode($result);
	}
	
	
	
	function logout()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		$CORE->setLayout(null);
		
		unset($_SESSION['admin']);
	}
	
	
}




?>