<?php
class AdminActivityController extends MainController{
	
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
		
		Core::renderView('adminActivity/indexView.php', $MODEL);
	}
	
	
	
	
	function list1()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null);
		
		//vd($ADMIN);
		if($ADMIN->hasRole(Role::ADMINS_MODERATOR))
		{
            $MODEL['adminId'] = $_REQUEST['adminId'];
            $MODEL['objectType'] = Object::code($_REQUEST['objectType']);
            $MODEL['journalEntryType'] = JournalEntryType::code($_REQUEST['journalEntryType']);

            $MODEL['admins'] = Admin::getList();
            $MODEL['objectTypes'] = Object::$items;
            $MODEL['journalEntryTypes'] = JournalEntryType::$items;

            $params = [
                'adminId' => $MODEL['adminId'],
                'objectType' => $MODEL['objectType'],
                'journalEntryType' => $MODEL['journalEntryType'],

                'orderBy' => 'dateCreated DESC',
            ];
			$MODEL['list'] = JournalEntry::getList($params);
			foreach($MODEL['list'] as $key=>$val)
				$val->initAdmin();
		}
		else 
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
		
		Core::renderView('adminActivity/list.php', $MODEL);
	}
	

	
	
	
	
}




?>