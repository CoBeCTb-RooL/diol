<?php
class Product_volume_unitController extends MainController{
	
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
		
		Core::renderView('productVolumeUnits/indexView.php', $model);
	}
	
	
	
	
	function list1()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$MODEL['list'] = ProductVolumeUnit::getList();
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
	
		Core::renderView('productVolumeUnits/list.php', $MODEL);
	}
	
	
	
	function edit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);

		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			if($id = intval($_REQUEST['id']))
			{
				$MODEL['item'] = ProductVolumeUnit::get($id);
				if(isset($_REQUEST['id']) && !$MODEL['item'])
					$MODEL['error'] = 'Ошибка! Мера не найдена. ';
			}
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
		
	
		Core::renderView('productVolumeUnits/edit.php', $MODEL);
	}
	
	
	
	function editSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		$errors = null;
		
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			if($name=trim($_REQUEST['name']))
			{
				$item = ProductVolumeUnit::get($_REQUEST['id']);
				
				if($item)
				{
					if($item->name != $name)
					{
						$prevName = $item->name;
						$item->name = $name;
						$item->update();
						$msg = 'Название изменено с "'.$prevName.'" на "'.$item->name.'"';
						$journalEntryType = JournalEntryType::code(JournalEntryType::UPDATE);
						$param1 = $item->name;
					}
					else 
						$noNeedToJournalize = true;
				}
				else
				{
					$item = new ProductVolumeUnit();
					$item->name = $name;
					$item->status = Status::code(Status::ACTIVE);
					$item->id = $item->insert();
					$msg = 'Создан';
					$journalEntryType = JournalEntryType::code(JournalEntryType::CREATE);
				}
				
				if(!$noNeedToJournalize)
				{
					$je = new JournalEntry();
					$je->objectType = Object::code(Object::PRODUCT_VOLUME_UNIT);
					$je->objectId = $item->id;
					$je->journalEntryType = $journalEntryType;
					$je->comment = $msg;
					$je->adminId = $ADMIN->id;
					$je->param1 = $param1;
					$je->insert();
				}
			}
			else 
				$errors[] = new Error('Вы не указали значение!', 'name');
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
		
	
		$json['errors'] = $errors;
		$json['status'] = $statusToBe;

		echo json_encode($json);
	}
	
	
	
	
	function switchStatus()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		$errors = null;
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$item = ProductVolumeUnit::get($_REQUEST['id']);
			if($item)
			{
				$prevStatus = $item->status;
				$statusToBe = $item->status->code == Status::ACTIVE ? Status::code(Status::INACTIVE) : Status::code(Status::ACTIVE);
				//vd($statusToBe);
		
				$item->status = $statusToBe;
				$item->update();
				
				$je = new JournalEntry();
				$je->objectType = Object::code(Object::PRODUCT_VOLUME_UNIT);
				$je->objectId = $item->id;
				$je->journalEntryType = JournalEntryType::code(JournalEntryType::STATUS_CHANGED);
				$je->comment = 'Смена статуса с "'.$prevStatus->code.'" на "'.$statusToBe->code.'"';
				$je->adminId = $ADMIN->id;
				$je->param1 = $item->status->num;
				$je->insert();
			}
			else
				$errors[] = new Error('Ошибка! Не найдена мера '.$_REQUEST['id'].'');
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
	
		$json['errors'] = $errors;
		$json['status'] = $statusToBe;

		echo json_encode($json);
	}
	
	
	
	
	
	
	
	
}




?>