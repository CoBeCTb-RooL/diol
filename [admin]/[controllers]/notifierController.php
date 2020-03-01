<?php
class NotifierController extends MainController{
	
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
            $params = [
                'orderBy' => 'dt asc',
                'dateTimeTo' => date('Y-m-d H:i:s'),
                'status' => Status::code(Status::ACTIVE),
            ];
            if($_REQUEST['clientId'])
                $params['clientId'] = $_REQUEST['clientId'];
            $MODEL['list'] = Reminder::getList($params);
            foreach ($MODEL['list'] as $item)
            {
                $item->initClient();
            }
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;

		Core::renderView('notifier/list.php', $MODEL);
	}



	
}




?>