<?php
class FieldController extends MainController{
	
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
		
		Core::renderView('fields/indexView.php', $model);
	}
	
	
	
	
	function list1()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null);
		
		//vd($_REQUEST);
		if($pid = intval($_REQUEST['pid']))
		{
			if( ($type = $_REQUEST['type']) == Entity2::TYPE_ELEMENTS || $type == Entity2::TYPE_BLOCKS)
			{
				$essence = Essence2::get($pid);
				if($essence)
				{
					$model['list'] = Field2::getList($pid, $type);
					$model['essence'] = $essence;
					$model['type'] = $type;
				}		
				else 
					$model['error'] = 'Ошибка! Сущность не найдена! ['.$pid.']';
			}
			else
				$model['error'] = 'Ошибка! Непонятный тип! ['.$type.']';
		}
		else
			$model['error'] = 'Ошибка! Неверный pid! ['.$_REQUEST['pid'].']';
		
		Core::renderView('fields/list.php', $model);
	}
	
	
	
	function edit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null);
		
		if(($type=$_REQUEST['type']) == Entity2::TYPE_ELEMENTS || $type == Entity2::TYPE_BLOCKS)
		{
			if($essence = Essence2::get($_REQUEST['essenceId']))
			{
				$model['essence'] = $essence;
				$model['field'] = Field2::get($_REQUEST['id']);
				$model['type'] = $type;
			}
			else
				$model['error'] = 'Ошика! Сущность не найдена.';
		} 
		else
			$model['error'] = 'Ошика! Непонятный тип!';
		
			
		Core::renderView('fields/edit.php', $model);
	}
	
	
	
	
	function editSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null);
		
		if($id = intval($_REQUEST['id']))
		{
			$field = Field2::get($_REQUEST['id']);
			$edit = true; 
		}
		
		
		vd($_REQUEST);
		
		$active = ($_REQUEST['active'] ? 1 : 0);
		$pid = intval($_REQUEST['pid']);
		$name = strPrepare($_REQUEST['name']);
		$code = strPrepare($_REQUEST['code']);
		$type = strPrepare($_REQUEST['type']);
		$ownerType = strPrepare($_REQUEST['ownerType']);
		$size = strPrepare($_REQUEST['size']);
		if($_REQUEST['width'] && $_REQUEST['height'])
			$size = strPrepare($_REQUEST['width'].'x'.$_REQUEST['height']);
		$options = strPrepare($_REQUEST['options']);
		$withTime = $_REQUEST['withTime'] ? 1 : 0;
		$multiple = ($_REQUEST['pic_multiple'] || $_REQUEST['select_multiple']) ? 1 : 0;
		$required = $_REQUEST['required'] ? 1 : 0;
		$displayed = $_REQUEST['displayed'] ? 1 : 0;
		$marked = $_REQUEST['marked'] ? 1 : 0;
		

		//vd($name);
		$essence = Essence2::get($pid);
		if(!$essence)
		{
			$error = 'Ошибка! Непонятная сущность! ['.$pid.']';
			$problems[] = 'essence';
		}
		else 
		{		 		
			$error = 'Заполните все необходимые поля!';
					
			
			
			if(!$name) $problems[] = 'name';
			
			if(!$edit)
			{
				if(!$type) $problems[] = 'type';
				if(!$code) $problems[] = 'code';
				
				
				$tmp=preg_match('/^[a-zA-Z][a-zA-Z0-9_]+$/', $code);
				if(!$tmp)	
				{
					$problems[]='code';
					$error='Некорректный код!';
				}
				
				#	проверка на сществование подобных полей
				if(!count($problems))
				{
					if(($byName = Field2::get(array('clause'=>" AND name='".$name."' AND ownerType='".$ownerType."' AND pid='".intval($essence->id)."' "))) && $byName->id != $field->id)
					{
						$error = 'Ошибка! Поле с таким именем уже есть! ';
						$problems[] = 'name';
					}
					elseif(($byCode = Field2::get(array('clause'=>" AND code='".$code."' AND ownerType='".$ownerType."' AND pid='".intval($essence->id)."'  "))) && $byCode->id != $field->id)
					{
						$error = 'Ошибка! Поле с таким кодом уже есть! ';
						$problems[] = 'code';
					}
					
				}
			}
			
		}
		//vd($problems);
		
		if(count($problems))
		{
			$str.='
			<script>';
			foreach($problems as $key=>$val)
			{
				$str.='
				window.top.highlight("field-edit-form *[name='.$val.']")
				window.top.$("#field-edit-form *[name='.$val.']").addClass("field-error")';
			}
			
			$str.='
				//window.top.$("#edit-form .info").html("'.$error.'")
				window.top.error("'.$error.'")
				window.top.Slonne.Fields.editSubmitComplete()';
			$str.='
			</script>';
			die($str);
		}
		else
		{
			if(!$edit)
			{
				$field = new Field2();
				$field->code = $code;
				$field->type = $type;
				$field->ownerType = $ownerType;
				$field->idx = 9999;
			}
		
			$field->pid = $pid;
			$field->name = $name;
			$field->size = $size;
			$field->options = $options;
			$field->withTime = $withTime;
			$field->multiple = $multiple;
			$field->required = $required;
			$field->displayed = $displayed;
			$field->marked = $marked;
			
			
			if($edit)
			{
				Field2::update($field);
			}
			else
			{
				$field->id = Field2::add($field);
				Field2::addFieldToTable($essence, $field);
			}
				
			$str.='
			<script>
				window.top.$.fancybox.close();
				window.top.Slonne.Fields.list('.$pid.', "'.$ownerType.'");
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
			Field2::delete($id);
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
				Field2::setIdx($key, $val);
		}
		
		
		$str.='
		<script>
		window.top.Slonne.Fields.listSubmitComplete()
		window.top.notice("Сохранено!")
		</script>';
		
		echo $str;
	}
	
	
	
}




?>