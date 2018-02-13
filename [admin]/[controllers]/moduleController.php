<?php
class ModuleController extends MainController{
	
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
		
		Core::renderView('modules/indexView.php', $model);
	}
	
	
	
	
	function list1()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null);
		
		$model = Module::getList();
		
		Core::renderView('modules/list.php', $model);
	}
	
	
	
	function edit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null);
		
		$model = Module::get($_REQUEST['id']);
		
		Core::renderView('modules/edit.php', $model);
	}
	
	
	
	
	function editSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null);
		
		if($id = intval($_REQUEST['id']))
		{
			$module = Module::get($_REQUEST['id']);
			$edit = true; 
		}
		
		$active = ($_REQUEST['active'] ? 1 : 0);
		$name = strPrepare(trim($_REQUEST['name']));
		$icon = strPrepare(trim($_REQUEST['icon']));
		$path = strPrepare(trim($_REQUEST['path']));
		$actions = strPrepare(trim($_REQUEST['actions']));
		
		//vd($name);
		 		
		$error = 'Заполните все необходимые поля!';
				
		if(!$name) $problems[] = 'name';
		//if(!$path) $problems[] = 'path';
		
		
		
		if(count($problems))
		{
			$str.='
			<script>';
			foreach($problems as $key=>$val)
			{
				$str.='
				window.top.highlight("edit-form input[name='.$val.']")
				window.top.$("#edit-form input[name='.$val.']").addClass("field-error")';
			}
			
			$str.='
				//window.top.$("#edit-form .info").html("'.$error.'")
				window.top.error("'.$error.'")
				window.top.Slonne.Modules.editSubmitComplete()';
			$str.='
			</script>';
			die($str);
		}
		else
		{
			if(!$edit)
				$module = new Module();
			
			$module->active = $active;
			$module->name = $name;
			$module->icon = $icon;
			$module->path = $path;
			$module->actions = $actions;
			
			if($edit)
				Module::update($module);
			else
				Module::add($module);
				
			$str.='
			<script>
				window.top.$.fancybox.close();
				window.top.Slonne.Modules.list();
				window.top.notice("Сохранено")
			</script>';
			echo $str;
		}
		
	}
	
	
	
	
	
	
	function delete()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null);
		
		$error = '';
		if($id = intval($_REQUEST['id']) )
		{
			Module::delete($id);
		}
		else 
			$error = 'Ошибка! Не передан id!';

		$result['error'] = $error;
		
		echo json_encode($result);
	}
	
	
	
	
	
	
	function listSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null);
		
		//vd($_REQUEST);
		foreach($_REQUEST['idx'] as $key=>$val)
		{
			if($val = intval($val))
				Module::setIdx($key, $val);
		}
		
		
		$str.='
		<script>
		window.top.Slonne.Modules.listSubmitComplete()
		window.top.notice("Сохранено!")
		</script>';
		
		echo $str;
	}
	
	
	
}




?>