<?php
class CategoriesController extends MainController{
	
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
	
		Core::renderView('categories/indexView.php', $model);
	}
	
	
	function list1()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
		
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$MODEL['cat'] = AdvCat::get($_REQUEST['catId']);
				
			if($MODEL['cat'])
			{
				$MODEL['cat']->initClass();
				$MODEL['cat']->getElderCats();
				$MODEL['cat']->initProductVolumeUnits();
			}
				
			$MODEL['list'] = AdvCat::getList(intval($_REQUEST['catId']));
			foreach($MODEL['list'] as $cat)
			{
				$cat->initClass();
				$cat->subCatsCount = AdvCat::getCount($cat->id);
				$cat->advsCount = AdvItem::getCount($cat->id);
				$cat->advsCountByStatus = AdvItem::getCountGroupByStatus($cat->id);
				$cat->initProductVolumeUnits();
			}
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
			//vd($MODEL);
	
		Core::renderView('categories/list.php', $MODEL);
	}
	
	
	function switchStatus()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		$errors = null;
		//vd($_REQUEST);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$cat = AdvCat::get($_REQUEST['catId']);
			if($cat)
			{
				$statusToBe = $cat->status->code == Status::ACTIVE ? Status::code(Status::INACTIVE) : Status::code(Status::ACTIVE);
				//vd($statusToBe);
	
				$cat->status = $statusToBe;
				$cat->update();
			}
			else
				$errors[] = new Error('Ошибка! Не найдена категория '.$_REQUEST['catId'].'');
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
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR))
		{
			$MODEL['cat'] = AdvCat::get($_REQUEST['catId']);
			if($MODEL['cat'])
			{
				$MODEL['cat']->initClass();
				if($MODEL['cat']->class)
					$MODEL['cat']->class->initProps(Status::code(Status::ACTIVE));
			}
				
			$MODEL['classes'] = AdvClass::getList($status=null);
			foreach($MODEL['classes'] as $class)
				$class->initProps(Status::code(Status::ACTIVE));
					
				$MODEL['currentCatId'] = $_REQUEST['currentCat'];
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
	
			Core::renderView('categories/edit.php', $MODEL);
	}
	
	
	
	function editSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		//usleep(800000);
		$errors = null;
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			if($_REQUEST['catId'] = intval($_REQUEST['catId']))
			{
				$cat = AdvCat::get($_REQUEST['catId']);
				if(!$cat)
					$errors[] = Slonne::setError('', 'Ошибка! Категория не найдена '.$_REQUEST['catId'].'');
			}
			else
				$cat = new AdvCat();
					
				if(!count($errors))
				{
					$cat->name = strPrepare($_REQUEST['name']);
					$cat->pid = $_REQUEST['pid'];
					$cat->status = $_REQUEST['active'] ? Status::code(Status::ACTIVE) : Status::code(Status::INACTIVE);
					$cat->classId = intval($_REQUEST['classId']);
	
					$errors = $cat->validate();
					if(!count($errors))
					{
						if($cat->id)
							$cat->update();
							else
							{
								$cat->idx = AdvCat::getNextIdx($cat->pid);
								$cat->id = $cat->insert();
							}
					}
				}
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
	
			$json['errors']=$errors;
			echo '<script>window.top.editSubmitComplete('.json_encode($json).')</script>';
	
	}
	
	
	function listSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		$errors = null;
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			//usleep(800000);
			foreach($_REQUEST['idx'] as $catId=>$val)
			{
				if($val = intval($val))
					AdvCat::setIdx($catId, $val);
			}
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
	
			$json['errors']=$errors;
	
			echo '<script>window.top.listSubmitComplete('.json_encode($json).')</script>';
	}
	
	
	
	function unitsList()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$MODEL['cat'] = AdvCat::get($_REQUEST['id']);
			$MODEL['cat']->initProductVolumeUnits();
			$MODEL['units'] = ProductVolumeUnit::getList();
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
	
		Core::renderView('categories/unitsList.php', $MODEL);
	}
	
	
	
	function unitClick()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
		
		$errors = null;
		
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$cat = AdvCat::get($_REQUEST['catId']);
			if($cat)
			{
				$unit = ProductVolumeUnit::get($_REQUEST['unitId']);
				if($unit)
				{
					$checked = $_REQUEST['checked'] ? true : false;
					$catUnitCmb = CatProductVolumeUnitCmb::get($cat->id, $unit->id);
					if($checked)
					{
						if(!$catUnitCmb)
						{
							$catUnitCmb = new CatProductVolumeUnitCmb();
							$catUnitCmb->catId = $cat->id;
							$catUnitCmb->unitId = $unit->id;
							$catUnitCmb->insert();
							
							$jeType = JournalEntryType::code(JournalEntryType::CATEGORY_CREATE_PRODUCT_UNIT_CMB);
							$msg = 'Добавлена единица изм. "'.$unit->name.'"(id:'.$unit->id.')';
							
							$toJournal = true;
						}
					}
					else
					{
						if($catUnitCmb) 
						{
							$catUnitCmb->delete();
							$jeType = JournalEntryType::code(JournalEntryType::CATEGORY_DELETE_PRODUCT_UNIT_CMB);
							$msg = 'Удалена единица изм. "'.$unit->name.'"(id:'.$unit->id.')';
							
							$toJournal = true;
						}
					}
					
					if($toJournal )
					{
						//vd($jeType);
						# 	записываем в журнал событий
						$je = new JournalEntry();
						$je->objectType = Object::code(Object::CATEGORY);
						$je->objectId = $cat->id;
						$je->journalEntryType = $jeType;
						$je->comment = $msg;
						$je->param1 = $unit->id;
						$je->adminId = $ADMIN->id;
						$je->insert();
					}
				}
				else
					$errors[] = new Error('ОШИБКА! Мера не найдена.');
			}
			else 
				$errors[] = new Error('ОШИБКА! Категория не найдена.');
			
			$cat->initProductVolumeUnits();
		}
		else 
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
		
		$json['cat'] = $cat;
		$json['errors'] = $errors;
		$json['checked'] = $checked;
		
		echo json_encode($json);
	}
	
	
	
}




?>