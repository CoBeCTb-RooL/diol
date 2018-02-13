<?php
class EssenceController extends MainController{
	
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
		
		Core::renderView('essences/indexView.php', $model);
	}
	
	
	
	
	function list1()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
		
		
		if($ADMIN->hasRole(Role::SUPER_ADMIN) )
		{
			$MODEL['list'] = Essence2::getList();
			foreach($MODEL['list'] as $key=>$item)
				$item->initFields();
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
		
		
		Core::renderView('essences/list.php', $MODEL);
	}
	
	
	
	function edit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
		
		if($ADMIN->hasRole(Role::SUPER_ADMIN) )
		{
			
		}
		else 
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
		
		Core::renderView('essences/edit.php', $MODEL);
	}
	
	
	
	
	function editSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
		
		$errors = null;
		
		if($ADMIN->hasRole(Role::SUPER_ADMIN) )
		{
			$item = new Essence2();
			$item->name = strPrepare(trim($_REQUEST['name']));
			$item->code = strPrepare(trim($_REQUEST['code']));
			$item->jointFields = $_REQUEST['jointFields'] ? 1 : 0;
			$item->linear = $_REQUEST['linear'] ? 1 : 0;
			 		
			//$error = 'Заполните все необходимые поля!';
					
			if(!$item->name) 
				$errors[] = new Error('Введите название', 'name');
			if(!$item->code)
				$errors[] = new Error('Введите код', 'code');
			
			$tmp=preg_match('/^[a-zA-Z][a-zA-Z0-9_]+$/', $item->code);
			if(!$tmp)	
				$errors[] = new Error('Некорректный код!', 'code');
			
			if(!$errors)
			{
				$tmp = Essence2::getByCode($item->code);
				if($tmp && $tmp->id != $item->id)
					$errors[] = new Error('Код уже занят.', 'code');
			}
			
			
			if(!$errors)
			{
				#	создаём сущность
				$item->id = Essence2::add($item);
				#	Создаём таблицы с обьектами
				$item->createTables();
			}
		}
		else 
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
		
		echo '<script>window.top.essenceEditSubmitComplete('.json_encode($errors).'); </script>';
	}
	
	
	
	
	
	
	function delete()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null);
		
		$errors = null;
		
		if($ADMIN->hasRole(Role::SUPER_ADMIN) )
		{
			if($id = intval($_REQUEST['id']) )
			{
				$essence = Essence2::get($id);
				if($essence)
				{
					Essence2::delete($id);
					$essence->dropTables();
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
	
	
	
	
	/*
	function listSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null);
		
		//vd($_REQUEST);
		foreach($_REQUEST['idx'] as $key=>$val)
		{
			if($val = intval($val))
				Essence2::setIdx($key, $val);
		}
		
		
		$str.='
		<script>
		window.top.Slonne.Essences.listSubmitComplete()
		window.top.notice("Сохранено!")
		</script>';
		
		echo $str;
	}
	*/
	
	
}




?>