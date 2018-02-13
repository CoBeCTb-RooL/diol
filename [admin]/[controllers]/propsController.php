<?php
class PropsController extends MainController{
	
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
		
		Core::renderView('props/indexView.php', $model);
	}
	
	
	function list1()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$MODEL['list'] = AdvProp::getList();
			foreach($MODEL['list'] as $key=>$prop)
			{
				if($prop->type == 'select')
					$prop->initOptions($status=null);
				if($prop->type == 'table')
					$prop->initTableColumns($status=null);
			}
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
	
		Core::renderView('props/propsList.php', $MODEL);
	}
	
	
	function edit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$prop = AdvProp::get($_REQUEST['propId']);
			if($prop)
			{
				if($prop->type == 'select')
					$prop->initOptions($status=null);
				if($prop->type == 'table')
					$prop->initTableColumns($status=null);
			}
			//vd($prop);
			$MODEL['prop'] = $prop;
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
		
	
		Core::renderView('props/propsEdit.php', $MODEL);
	}
	
	
	function editSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		$warnings = null;
		$errors = null;

		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$prop = AdvProp::get($_REQUEST['id']);
		
			if(!$prop->id)
				$prop = new AdvProp();
		
			if(!$prop->id)
			{
				$prop->code = $_REQUEST['code'];
				$prop->type = $_REQUEST['type'];
			}
			else
			{
					
			}
	
			$prop->name = $_REQUEST['name'];
			$prop->nameOnSite = $_REQUEST['nameOnSite'];
	
			$prop->size = $_REQUEST['size'];
			if($_REQUEST['width'] && $_REQUEST['height'])
				$prop->size = $_REQUEST['width'].'x'.$_REQUEST['height'];
	
			//$prop->options = $_REQUEST['options'];
			$prop->multiple = ($_REQUEST['pic_multiple'] || $_REQUEST['select_multiple']) ? 1 : 0;
			$prop->required = $_REQUEST['required'] ? 1 : 0;
			$prop->global = $_REQUEST['global'] ? 1 : 0;
			$prop->status = $_REQUEST['active'] ? Status::code(Status::ACTIVE) : Status::code(Status::INACTIVE);
	
	
			$errors = $prop->validate();
			//vd($problems);
			//vd($error);
			if(!count($errors))
			{
				if($prop->id)
					$prop->update();
				else
					$prop->id = $prop->insert();
	
				#	заводим новые опции
				if($prop->type == 'select')
				{
					$newOptions = trim($_REQUEST['options']);
					if($newOptions)
					{
						$arr = explode("\r\n", $newOptions);
						foreach($arr as $key=>$val)
						{
							if(trim($val))
							{
								$opt = new AdvCatSelectOption();
								$opt->propId = $prop->id;
								$opt->value = strPrepare($val);
								$opt->status = Status::code(Status::ACTIVE);
								//vd($opt);
								$optSimilar = $opt->getSimilarByValue();
								//vd($optSimilar);
								if(!$optSimilar)
									$opt->insert();
								else
								{
									$warnings[] = 'Опция <b>"'.$opt->value.'"</b> уже есть в этом списке! НЕ ДОБАВЛЕНА!';
								}
							}
						}
					}
				}
							
							
				# 	обработка столбцов типа ТАБЛИЦА
				if($prop->type=='table')
				{
					//vd($_REQUEST);
					# 	НОВЫЕ
					foreach($_REQUEST['insert'] as $num=>$colName)
					{
						$colName = trim($colName);
						if($colName && !AdvPropTableColumn::getByPropIdAndName($prop->id, $colName))
						{
							$col = new AdvPropTableColumn();
							$col->name = trim($colName);
							$col->idx = intval($_REQUEST['insert_idx'][$num]);
							$col->status = Status::code(Status::ACTIVE);
							$col->propId = $prop->id;
	
							$col->insert();
						}
					}
	
					# 	АПДЕЙТ
					//vd($_REQUEST);
					foreach($_REQUEST['update'] as $colId=>$colName)
					{
						$colName = trim($colName);
						if($colName && !AdvPropTableColumn::getByPropIdAndName($prop->id, $colName, $colId))
						{
							$col = AdvPropTableColumn::get($colId);
							$col->name = trim($colName);
							$col->idx = intval($_REQUEST['update_idx'][$colId]);
	
							$col->update();
						}
					}
				}
			}
		}
		else 
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
		//vd($warnings);

		$result['errors'] = $errors;
		$result['warnings'] = $warnings;
		vd($result);
		echo '<script>window.top.propEditSubmitComplete('.json_encode($result).')</script>';
	}
	
	
	function propsDelete()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		$errors = null;
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			if($id = intval($_REQUEST['id']) )
			{
				AdvProp::delete($id);
			}
			else
				$errors[] = new Error('Ошибка! Не передан id!');
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
	
		$result['errors'] = $errors;
		echo json_encode($result);
	}
	
	
	
	/*function propOptionValueSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
	
		$CORE->setLayout(null);
	
		$error = '';
	
		//vd($_REQUEST);
		$opt = AdvCatSelectOption::get($_REQUEST['id']);
		if($opt)
		{
			if($val = trim($_REQUEST['val']))
			{
				$opt->value = $val;
				#	проверка, существует ли такое значение уже в этом списке
				$similarByValue = $opt->getSimilarByValue();
				if($similarByValue)
					$error = "Опция с таким значением уже существует в этом списке!";
					else
					{
	
						$opt->update();
					}
			}
			else
				$error = 'Ошибка! Не передано значение! ';
		}
		else
			$error = 'Ошибка! Не удалось найти option id='.$_REQUEST['id'].' ';
	
			$result['error'] = $error;
	
			echo json_encode($result);
	}*/
	
	
	
	function optionDelete()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		$errors = null;
		
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			//vd($_REQUEST);
			if($id = intval($_REQUEST['id']) )
			{
				AdvCatSelectOption::delete($id);
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
		//vd($_REQUEST);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$prop = AdvProp::get($_REQUEST['propId']);
			//vd($prop);
			if($prop)
			{
				$statusToBe = $prop->status->code == Status::code(Status::ACTIVE)->code ? Status::code(Status::INACTIVE) : Status::code(Status::ACTIVE);
				//vd($statusToBe);
					
				$prop->status = $statusToBe;
				$prop->update();
			}
			else
				$errors[] = new Error('Ошибка! Не найдено свойство '.$_REQUEST['catId'].'');
		
			$json['error'] = $error;
			$json['status'] = $statusToBe;
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);

		$json['errors'] = $errors;
		echo json_encode($json);
	}
	
	
	
	
	function switchTableColumnStatus()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
	
		$CORE->setLayout(null);
	
		$errors = null;
		//vd($_REQUEST);
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$col = AdvPropTableColumn::get($_REQUEST['columnId']);
			//vd($prop);
			if($col)
			{
				$statusToBe = $col->status->code == Status::ACTIVE? Status::code(Status::INACTIVE) : Status::code(Status::ACTIVE);
				//vd($statusToBe);
				$col->status = $statusToBe;
				$col->update();
			}
			else
				$errors[] = new Error('Ошибка! Не найден столбец '.$_REQUEST['columnId'].'');
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
			
		$json['errors'] = $errors;
		$json['status'] = $statusToBe;

		echo json_encode($json);
	}
	
	
	
	
	function columnDelete()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		$errors = null;
		//vd($_REQUEST);
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			if($id = intval($_REQUEST['id']) )
			{
				AdvPropTableColumn::delete($id);
			}
			else
				$errors[] = new Error('Ошибка! Не передан id!');
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
		
		$result['errors'] = $errors;
		echo json_encode($result);
	}
	
	
	
	
	
	function optionValueSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		$errors = null;
	
		//vd($_REQUEST);
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$opt = AdvCatSelectOption::get($_REQUEST['id']);
			if($opt)
			{
				if($val = trim($_REQUEST['val']))
				{
					$opt->value = $val;
					#	проверка, существует ли такое значение уже в этом списке
					$similarByValue = $opt->getSimilarByValue();
					if($similarByValue )
						$errors[] = new Error("Опция с таким значением уже существует в этом списке!");
					else
					{
						$opt->update();
					}
				}
				else
					$errors[] = new Error('Ошибка! Не передано значение! ');
			}
			else
				$errors[] = new Error('Ошибка! Не удалось найти option id='.$_REQUEST['id'].' ');
		}
		else 
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
	
		$result['errors'] = $errors;
		echo json_encode($result);
	}
	
	
	
}




?>