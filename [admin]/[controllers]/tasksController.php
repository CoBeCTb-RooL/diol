<?php
class TasksController extends MainController{
	
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
		
		Core::renderView('tasks/indexView.php', $model);
	}
	
	
	
	function listAll()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
		
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			//vd($_REQUEST);
			$globalGroup = new TaskGroup();
			$globalGroup->id=null;
			$globalGroup->name = 'Общие задачи';
			$globalGroup->status = Status::code(Status::ACTIVE);
			$MODEL['groups'][] = $globalGroup;
			
			$tmp = TaskGroup::getList( $statuses=array(Status::$items[Status::ACTIVE], Status::$items[Status::DONE]), $orderBy='name');
			$MODEL['groups'] = array_merge($MODEL['groups'], $tmp);
			
			foreach($MODEL['groups'] as $g)
				$g->tasks = Task::getList($g->id, $statuses=array(Status::$items[Status::ACTIVE], Status::$items[Status::DONE]));
			
			$MODEL['globalTasks'] = Task::getList(null, $statuses=array(Status::$items[Status::ACTIVE], Status::$items[Status::DONE]));
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
	
		Core::renderView('tasks/listAll.php', $MODEL);
	}
	
	
	
	
	function groupsEdit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
		
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$MODEL['item'] = TaskGroup::get($_REQUEST['id']);
		}
		else 
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
		//vd($MODEL['item']);
		
		Core::renderView('tasks/groupEdit.php', $MODEL);
	}
	
	
	
	function groupsEditSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	//vd($_REQUEST);
		$errors = null;
		
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$isNew = false; 
			
			if($name=trim($_REQUEST['name']))
			{
				$item = TaskGroup::get($_REQUEST['id']);
				
				if($item)
				{
					$prev = clone $item;
					
					$item->name = $name; 
					
					$changes = array();
					if($item->name != $prev->name)
						$changes[] = 'Название изменено с "'.$prev->name.'" на "'.$item->name.'"';
	
					if(count($changes))
					{
						$item->update();
						$journalEntryType = JournalEntryType::code(JournalEntryType::UPDATE);
						$msg = join('\n   - ', $changes);
						$param1 = $item->name;
					}
					else
						$noNeedToJournalize = true;
				}
				else
				{
					$item = new TaskGroup();
					$item->name = $name;
					$item->status = Status::code(Status::ACTIVE);
					$item->creatorId = $ADMIN->id;
					$item->id = $item->insert();
					$msg = 'Создан';
					$journalEntryType = JournalEntryType::code(JournalEntryType::CREATE);
				}
				
				if(!$noNeedToJournalize)
				{
					$je = new JournalEntry();
					$je->objectType = Object::code(Object::TASK_GROUP);
					$je->objectId = $item->id;
					$je->journalEntryType = $journalEntryType;
					$je->comment = $msg;
					$je->adminId = $ADMIN->id;
					$je->param1 = $param1;
					$je->insert();
					
					$isNew = true;
				}
			}
			else 
				$errors[] = new Error('Вы не указали значение!', 'name');
		}
		else 
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
	
		$json['errors'] = $errors;
		$json['result'] = $item;
		$json['isNew'] = $isNew;

		echo json_encode($json);
	}
	
	
	
	/*function groupsSetStatus()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
		
		if($status = Status::code($_REQUEST['status']))
		{
			$item = TaskGroup::get($_REQUEST['id']);	
			if($item)
			{
				$tasks = Task::getList($item->id, $statuses=array(Status::code(Status::ACTIVE)));
				if(!$tasks)
				{
					$prevStatus = $item->status;
					
					$item->status = $status;
					$item->update();
						
					$je = new JournalEntry();
					$je->objectType = Object::code(Object::TASK_GROUP);
					$je->objectId = $item->id;
					$je->journalEntryType = JournalEntryType::code(JournalEntryType::TASK_GROUP_SET_STATUS);
					$je->comment = 'Смена статуса с "'.$prevStatus->code.'" на "'.$status->code.'"';
					if($status->code == Status::DELETED)
					{
						$je->journalEntryType = JournalEntryType::code(JournalEntryType::TASK_GROUP_DELETE);
						$je->comment = 'Удалено';
					}
					$je->adminId = $ADMIN->id;
					$je->param1 = $item->status->code;
					$je->insert();
				}
				else
					$errors[] = Slonne::setError('', 'ОШИБКА! Нельзя удалить эту группу - есть незавершённые задачи. ');
			}
			else
				$errors[] = Slonne::setError('', 'Ошибка! Не найдена группа задач'.$_REQUEST['id'].'');
		}
		else 
			$errors[] = Slonne::setError('', 'Ошибка статуса. ['.$_REQUEST['status'].']');
		
		
		$json['errors'] = $errors;
		$json['result'] = $item;
	
		echo json_encode($json);
	}*/
	
	
	
	
	
	function groupsDelete()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$item = TaskGroup::get($_REQUEST['id']);
			if($item)
			{
				$tasks = Task::getList($item->id, $statuses=array(Status::code(Status::ACTIVE)));
				if(!$tasks)
				{
					$item->tasks = Task::getList($item->id);
					foreach($itme->tasks as $task)
						Task::delete($task->id);
					
					TaskGroup::delete($item->id);
				}
				else
					$errors[] = Slonne::setError('', 'ОШИБКА! Нельзя удалить эту группу - есть незавершённые задачи. ');
			}
			else
				$errors[] = Slonne::setError('', 'Ошибка! Не найдена группа задач'.$_REQUEST['id'].'');
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
		

		$json['errors'] = $errors;
		$json['result'] = $item;

		echo json_encode($json);
	}
	
	
	
	function tasksEdit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$MODEL['item'] = Task::get($_REQUEST['id']);
			//$MODEL['groups'] = TaskGroup::getList($statuses = array(Status::code(Status::ACTIVE), Status::code(Status::DONE)));
			$MODEL['chosenGroup'] = TaskGroup::get($MODEL['item'] ? $MODEL['item']->groupId : $_REQUEST['groupId']);
			//vd($MODEL['item']);
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
	
		Core::renderView('tasks/taskEdit.php', $MODEL);
	}
	
	
	function tasksEditSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
		//vd($_REQUEST);
		//return;
		$errors = null;
		
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$isNew = false;
		
			if($title=trim($_REQUEST['title']))
			{
				$item = Task::get($_REQUEST['id']);
					
				if($item)
				{
					$prev = clone $item;
		
					$item->title = $title;
					$item->groupId = $_REQUEST['groupId'];
		
					$changes = array();
					if($item->title != $prev->title)
						$changes[] = 'Название изменено с "'.$prev->title.'" на "'.$item->title.'"';
		
					if(count($changes))
					{
						$item->update();
						$journalEntryType = JournalEntryType::code(JournalEntryType::UPDATE);
						$msg = join('\n   - ', $changes);
						$param1 = $item->title;
					}
					else
						$noNeedToJournalize = true;
				}
				else
				{
					$item = new Task();
					$item->title = $title;
					$item->groupId = $_REQUEST['groupId'];
					$item->status = Status::code(Status::ACTIVE);
					$item->creatorId = $ADMIN->id;
					$item->id = $item->insert();
					$msg = 'Создан';
					$journalEntryType = JournalEntryType::code(JournalEntryType::CREATE);
					
					$group = TaskGroup::get($item->groupId);
					if($group->status->code == Status::DONE)
					{
						$group->status = Status::code(Status::ACTIVE);
						$group->update();
					}
				}
					
				if(!$noNeedToJournalize)
				{
					$je = new JournalEntry();
					$je->objectType = Object::code(Object::TASK);
					$je->objectId = $item->id;
					$je->journalEntryType = $journalEntryType;
					$je->comment = $msg;
					$je->adminId = $ADMIN->id;
					$je->param1 = $param1;
					$je->insert();
		
					$isNew = true;
				}
			}
			else
				$errors[] = new Error('Вы не указали значение!', 'title');
		}
		else 
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
	

		$json['errors'] = $errors;
		$json['result'] = $item;
		$json['isNew'] = $isNew;
		$json['group'] = $group;

		echo json_encode($json);
	}
	
	
	function tasksDelete()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$item = Task::get($_REQUEST['id']);
			$group = TaskGroup::get($item->groupId);
			if($item)
			{
				Task::delete($item->id);
				
				if($group)
				{
					$tasks = Task::getList($group->id, $statuses=array(Status::code(Status::ACTIVE)));
					if(!$tasks && $group->status->code == Status::ACTIVE)
					{
						$group->status = Status::code(Status::DONE);
						$group->update();
					}
				}
			}
			else
				$errors[] = Slonne::setError('', 'Ошибка! Не найдена группа задач'.$_REQUEST['id'].'');
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
		
	
		$json['errors'] = $errors;
		$json['result'] = $item;
		$json['group'] = $group;

		echo json_encode($json);
	}
	
	
	
	
	
	function switchTaskStatus()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		$errors = null;
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$item = Task::get($_REQUEST['id']);
			if($item)
			{
				$prevStatus = $item->status;
				$statusToBe = $item->status->code == Status::ACTIVE ? Status::code(Status::DONE) : Status::code(Status::ACTIVE);
				//vd($statusToBe);
		
				$item->status = $statusToBe;
				$item->update();
				
				# 	смотрим, нужно ли менять статус группы
                if($item->groupId)
                {
                    $group = TaskGroup::get($item->groupId);
                    if($tmp = Task::getList($group->id, $statuses=array(Status::code(Status::ACTIVE))))
                    {
                        if($group->status->code!=Status::ACTIVE)
                        {
                            $group->status = Status::code(Status::ACTIVE);
                            $group->update();
                        }
                    }
                    elseif($group->status->code!=Status::DONE)
                    {
                        $group->status = Status::code(Status::DONE);
                        $group->update();
                    }
                }
			}
			else
				$errors[] = new Error('Ошибка! Не найдена задача '.$_REQUEST['id'].'');
		}
		else 
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
	
		$json['errors'] = $errors;
		$json['status'] = $item->status;
		$json['group'] = $group;
		
		echo json_encode($json);
	}
	
	
	
	
	
	
	
	
}




?>