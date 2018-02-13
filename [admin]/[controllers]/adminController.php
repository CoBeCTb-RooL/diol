<?php
class AdminController extends MainController{
	
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
		
		Core::renderView('admins/indexView.php', $model);
	}
	
	
	
	
	function list1()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null);
		
		//vd($ADMIN);
		if($ADMIN->hasRole(Role::ADMINS_MODERATOR))
		{	
			$MODEL['list'] = Admin::getList(null, $statusesNotIn=array(Status::code(Status::DELETED)));
			foreach($MODEL['list'] as $key=>$val)
				$val->initGroup();
		}
		else 
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
		
		Core::renderView('admins/list.php', $MODEL);
	}
	
	
	
	function switchStatus()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
	
		$CORE->setLayout(null);
	
		$errors = null;
		//vd($_REQUEST);
	
		if($ADMIN->hasRole(Role::ADMINS_MODERATOR))
		{
			$item = Admin::get($_REQUEST['id']);
			if($item)
			{
				$statusPrev = clone $item->status;
				$statusToBe = $item->status->code == Status::ACTIVE ? Status::code(Status::INACTIVE) : Status::code(Status::ACTIVE);
				//vd($statusToBe);
		
				$item->status = $statusToBe;
				$item->update();
					
				# 	журналируем
				$je = new JournalEntry();
				$je->objectType = Object::code(Object::ADMIN);
				$je->objectId = $item->id;
				$je->journalEntryType = JournalEntryType::code(JournalEntryType::STATUS_CHANGED);
				$je->comment = 'Статус изменён с "'.($statusPrev->code).'" на "'.$statusToBe->code.'"';
				$je->adminId = $ADMIN->id;
				$je->param1 = $statusToBe->code;
				$je->insert();
					
			}
			else
				$errors[] = new Error('Ошибка! Не найден админ. ['.$_REQUEST['id'].']');
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
		
		if($ADMIN->hasRole(Role::ADMINS_MODERATOR) )
		{
			$MODEL['admin'] = Admin::get($_REQUEST['id']);
			$MODEL['groups'] = AdminGroup::getList('', $statusesNotIn=array(Status::code(Status::DELETED)));
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
		
		Core::renderView('admins/edit.php', $MODEL);
	}
	
	
	
	
	
	function editSubmit2()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
	
		$CORE->setLayout(null);
	
		
		$errors = null; 
		if($ADMIN->hasRole(Role::ADMINS_MODERATOR) )
		{
			if($_REQUEST['id'] )
			{
				if(!$item = Admin::get($_REQUEST['id']) )
					$errors[] = new Error('Ошибка! Админ не найден. ['.$_REQUEST['id'].']');
				else 
					$item->initGroup();
			}
			elseif(!$errors)
			{
				$item = new Admin();
				$item->status = Status::code(Status::ACTIVE);
			}
			
			if(!$errors )
			{
				$item->name = strPrepare($_REQUEST['name']);
				$item->email = strPrepare($_REQUEST['email']);
				$errors = Error::merge($errors, $item->validate());
				
				# 	проверка на занятость имейла
				$sameEmail = Admin::getByEmail($item->email);
				if($sameEmail && $sameEmail->id != $item->id)
					$errors[] = new Error('Этот e-mail уже занят.', 'email');
				
				# 	проверка существования группы 
				if( $_REQUEST['groupId'] && (!$group = AdminGroup::get($_REQUEST['groupId'])) )
					$errors[] = new Error('Группа не найдена. ['.$_REQUEST['groupId'].']', 'groupId');
				# 	запрещаем не сверх-админу добавлять кого-то в группу с правами сверх-админа
				if($group && ($group->role & Role::SUPER_ADMIN) && !$ADMIN->hasRole(Role::SUPER_ADMIN) )
					$errors[] = new Error('У вас недостаточно прав, чтобы добавлять кого-то в группу "<b>'.Role::num(Role::SUPER_ADMIN)->name.'</b>"', 'groupId');
				# 	запрещаем не сверх-админу свергать сверх-админа
				if(!$ADMIN->hasRole(Role::SUPER_ADMIN) && ($item->group->role & Role::SUPER_ADMIN) && !($group->role & Role::SUPER_ADMIN)) 
					$errors[] = new Error('У вас недостаточно прав, чтобы администратора из группы с ролью "<b>'.Role::num(Role::SUPER_ADMIN)->name.'</b>" переводить в группу без этой роли.', 'groupId');
					
				
				$item->groupId = intval($_REQUEST['groupId']);
				
			}
			
			# 	разбираемся с паролем
			$item->password = strPrepare(trim($_REQUEST['password']));
			$item->password2 = strPrepare(trim($_REQUEST['password2']));
			if( !$item->id ||  ($item->id && ($item->password || $item->password2) )  )
			{
				if(!$item->password)
				{
					$errors[] = new Error('Введите пароль!', 'password');
				}
				elseif(!$item->password2)
				{
					$errors[] = new Error('Подтвердите пароль!', 'password2');
				}
				elseif($item->password != $item->password2)
				{
					$errors[] = new Error('Пароли не совпадают!', 'password');
					$errors[] = new Error('', 'password2');
				}
			}
			
			
			if(!$errors)
			{
				# 	сохраняем
				if($item->id)
					$item->update();
				else 
					$item->id = $item->insert();
				
				# 	сохраняем пароль, если нужно
				if($item->password)
					$item->setPassword($item->password);
			}
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
		
		echo '<script>window.top.adminsEditSubmitComplete('.json_encode($errors).')</script>';
	}
	
	
	
	function setStatus()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
	
		$CORE->setLayout(null);
	
		$errors = null;
		//vd($_REQUEST);
	
		if($ADMIN->hasRole(Role::ADMINS_MODERATOR) )
		{
			if($item = Admin::get($_REQUEST['id']) )
			{
				if($status = Status::code($_REQUEST['status']))
				{
					$item->status = Status::code(Status::DELETED);
					$item->update();
		
					# 	журналируем
					$je = new JournalEntry();
					$je->objectType = Object::code(Object::ADMIN);
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
				$errors[] = new Error('Ошибка! Админ не найден. ['.$_REQUEST['id'].']');
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
	
		$json['errors'] = $errors;
		$json['item'] = $item;

		echo json_encode($json);
	}
	
	
	
	
	
}




?>