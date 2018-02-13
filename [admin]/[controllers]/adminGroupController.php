<?php
class AdminGroupController extends MainController{
	
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
		//echo "!";
		
		Core::renderView('admins/adminGroups/indexView.php', $MODEL);
		
		//self::list2();
	}
	
	
	
	
	function list2()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
	
		$CORE->setLayout(null);
		
		if($ADMIN->hasRole(Role::ADMIN_GROUPS_MODERATOR) )
		{
			$MODEL = AdminGroup::getList($status = null, $statusesNotIn = array(Status::code(Status::DELETED)));
		}
		else 
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
	
		Core::renderView('admins/adminGroups/list2.php', $MODEL);
	}
	
	
	function edit2()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
	
		$CORE->setLayout(null);
	
		if($ADMIN->hasRole(Role::ADMIN_GROUPS_MODERATOR) )
		{
			if($_REQUEST['id'])
			{
				$MODEL['group'] = AdminGroup::get($_REQUEST['id']);
			}
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
	
		Core::renderView('admins/adminGroups/edit2.php', $MODEL);
	}
	
	
	
	function editSubmit2()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
	
		$CORE->setLayout(null);
	
		if($ADMIN->hasRole(Role::ADMIN_GROUPS_MODERATOR) )
		{
			//vd($_REQUEST);
			$roles = $_REQUEST['role'];
			
			$errors = null; 
			//vd($roles);
			if($_REQUEST['id'] )
			{
				if(!$item = AdminGroup::get($_REQUEST['id']) )
					$errors[] = new Error('Ошибка! Группа не найдена. ['.$_REQUEST['id'].']');
			}
			elseif(!$errors)
			{
				$item = new AdminGroup();
				$item->status = Status::code(Status::ACTIVE);
			}
			
			if(!$errors )
			{
				$item->name = strPrepare($_REQUEST['name']);
				$errors = Error::merge($errors, $item->validate());
			}
			
			if(!$errors)
			{
				# 	подсчитываем роли
				$item->role = 0;
				foreach($roles as $r=>$val)
				{
					if(!($r==Role::SUPER_ADMIN && !$ADMIN->hasRole(Role::SUPER_ADMIN)))
						$item->role +=$r;
					else 
						$errors[] = new Error('Вы не имеете прав добавить роль <b>"'.Role::num(Role::SUPER_ADMIN)->name.'"</b>.');
				}
				
				if(!$errors)
				{
					# 	сохраняем
					if($item->id)
						$item->update();
					else 
						$item->id = $item->insert();
				}
			}
		}
		else 
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
		
		echo '<script>window.top.groupsEditSubmitComplete('.json_encode($errors).')</script>';
	}
	
	
	
	function switchStatus()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
	
		$CORE->setLayout(null);
	
		$errors = null;
		//vd($_REQUEST);
		if($ADMIN->hasRole(Role::ADMIN_GROUPS_MODERATOR) )
		{
			$item = AdminGroup::get($_REQUEST['id']);
			if($item)
			{
				$statusPrev = clone $item->status;
				$statusToBe = $item->status->code == Status::ACTIVE ? Status::code(Status::INACTIVE) : Status::code(Status::ACTIVE);
				//vd($statusToBe);
		
				$item->status = $statusToBe;
				$item->update();
				
				# 	журналируем
				$je = new JournalEntry();
				$je->objectType = Object::code(Object::ADMIN_GROUP);
				$je->objectId = $item->id;
				$je->journalEntryType = JournalEntryType::code(JournalEntryType::STATUS_CHANGED);
				$je->comment = 'Статус изменён с "'.($statusPrev->code).'" на "'.$statusToBe->code.'"';
				$je->adminId = $ADMIN->id;
				$je->param1 = $statusToBe->code;
				$je->insert();
				
			}
			else
				$errors[] = new Error('Ошибка! Не найдена группа админов. ['.$_REQUEST['id'].']');
		}
		else 
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
	
		$json['errors'] = $errors;
		$json['status'] = $statusToBe;

		echo json_encode($json);
	}
	
	
	
	function setStatus()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
	
		$CORE->setLayout(null);
	
		$errors = null;
		//vd($_REQUEST);
	
		if($ADMIN->hasRole(Role::ADMIN_GROUPS_MODERATOR) )
		{
			if($item = AdminGroup::get($_REQUEST['id']))
			{
				if($status = Status::code($_REQUEST['status']))
				{
					$item->status = Status::code(Status::DELETED);
					$item->update();
					
					# 	журналируем
					$je = new JournalEntry();
					$je->objectType = Object::code(Object::ADMIN_GROUP);
					$je->objectId = $item->id;
					$je->journalEntryType = JournalEntryType::code(JournalEntryType::DELETE);
					$je->comment = 'Удалено.';
					$je->adminId = $ADMIN->id;
					$je->insert();
				}
				else 
					$errors[] = new Error('Ошибка! Непонятный статус. ['.$_REQUEST['status'].']');
			}
			else
				$errors[] = new Error('Ошибка! Группа не найдена. ['.$_REQUEST['id'].']');
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
	
		$json['errors'] = $errors;
		$json['item'] = $item;

		echo json_encode($json);
	}
	
	
	
	
	
	
	
}




?>