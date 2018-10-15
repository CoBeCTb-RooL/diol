<?php
class ClassesController extends MainController{
	
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
		
		Core::renderView('classes/indexView.php', $model);
	}
	
	

	function list1()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$list = AdvClass::getList();
			foreach($list as $key=>$class)
				$class->initProps($status=null);		
			$MODEL['list'] = $list;
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;

		Core::renderView('classes/list.php', $MODEL);
	}
	
	
	function edit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$class = AdvClass::get($_REQUEST['classId']);
			if($class)
				$class->initProps($status=null);
		
			$MODEL['class'] = $class;
			$MODEL['props'] = AdvProp::getList($status=null);
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;

		Core::renderView('classes/edit.php', $MODEL);
	}
	
	
	function editSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
		
		//vd($_REQUEST);
		$errors = null;

		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$class = AdvClass::get($_REQUEST['id']);
		
			if(!$class->id)
				$class = new AdvClass();
		
			$class->status = $_REQUEST['active'] ? Status::code(Status::ACTIVE) : Status::code(Status::INACTIVE);
			$class->name = strPrepare($_REQUEST['name']);
	
			$errors = $class->validate();
	
			if(!$errors)
			{
				if($class->id)
					$class->update();
				else
					$class->id = $class->insert();
	
				#	сохранение связей
				AdvClassPropRelation::deleteRelationsOfClass($class->id);
	
				foreach($_REQUEST['props'] as $propId=>$val)
				{
					$rel = new AdvClassPropRelation();
					$rel->classId = $class->id;
					$rel->propId = $propId;
	
					$rel->insert();
				}
			}
		}
		else 
			$errors[] = new Error(Error::NO_ACCESS_ERROR);

		$result['errors'] = $errors;
		echo '<script>window.top.classEditSubmitComplete('.json_encode($result).')</script>';
	}
	
	
	
	function deleteClass()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		$errors = null;
		
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			if($id = intval($_REQUEST['id']) )
			{
				$class = AdvClass::get($id);
				if($class)
				{
					AdvClass::delete($id);
					AdvClassPropRelation::deleteRelationsOfClass($id);
				}
			}
			else
				$errors[] = new Error('Ошибка! Не передан id!');
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
	
		$result['errors'] = $errors;
		echo json_encode($result);
	}
	
	
	function switchStatus()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		$errors = null;

		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$class = AdvClass::get($_REQUEST['classId']);
			//vd($prop);
			if($class)
			{
				$statusToBe = $class->status->code == Status::ACTIVE ? Status::code(Status::INACTIVE) : Status::code(Status::ACTIVE);
				//vd($statusToBe);
		
				$class->status = $statusToBe;
				$class->update();
			}
			else
				$errors[] = new Error('Ошибка! Не найдено свойство '.$_REQUEST['catId'].'');
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
	
		$json['errors'] = $errors;
		$json['status'] = $statusToBe;
		echo json_encode($json);
	}
	
	
	function listSaveChanges()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);

		$errors = null;
		
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			foreach($_REQUEST['idx'] as $classId=>$tmp)
				foreach($tmp as $propId=>$idx)
					AdvClassPropRelation::setIdx($classId, $propId, $idx);
		}
		else 
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
		
		echo '<script>window.top.listSaveChangesComplete('.json_encode($errors).')</script>';
	}
	
	
	
	
}




?>