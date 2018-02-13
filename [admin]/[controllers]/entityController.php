<?php
class EntityController extends MainController{
	
	
	
	function index()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		//vd($CORE->params);
		$essence=Essence2::get($CORE->params[1]);		
		$model['essence'] = $essence;
		
		Core::renderView('entities/indexView.php', $model);
	}
	
	
	
	
	function showList()
	{
		self::index();
	}
	
	
	
	
	function listJson()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null);
		//usleep(300000);
		
		$essenceCode = $_GET['essenceCode'];
		$pid = $_GET['pid'];
		//$lang = $_GET['lang'];
		$lang = $_CONFIG['langs'][$_GET['lang']] ? $_CONFIG['langs'][$_GET['lang']]: $_CONFIG['default_lang'];
		//vd($lang);
		
		$type = $_GET['type'];
		$p=intval($_REQUEST['p']) ? intval($_REQUEST['p'])-1 : 0;
		
		if($type == Entity2::TYPE_BLOCKS || $type == Entity2::TYPE_ELEMENTS) 
		{
			$elPP=Entity2::TREE_ELEMENTS_PER_PAGE;
			
			$result = array();
			if($essenceCode)
			{
				if($essence = Essence2::get($essenceCode))
				{
					$params = array(
									'essenceCode'=>$essenceCode,
									'pid'=>$pid,
									'limit'=>"LIMIT ".($p)*$elPP.", ".$elPP."",
									'type'=>$type, 
									'order'=>'', 
									'lang'=>$lang->code, 
									'additionalClauses'=>'and 1',
									'activeOnly'=>false,
								);
								
					$tmp = Entity2::getList($params); 
					//vd($tmp);
					
					#	сколько всего 
					$params['limit'] = '';
					$result['totalCount'] = Entity2::getCount($params);
					$result['elPP'] = $elPP;
					$result['p'] = $p;
					
					
					if(!$essence->linear)
					{
						foreach($tmp as $key=>$val)
						{
							if($type == Entity2::TYPE_ELEMENTS || $essence->jointFields )
							{
								$tmp[$key]->childBlocksCount = Entity2::getChildElementsCount($essence->code, $val->id);
							}
							if($type == Entity2::TYPE_BLOCKS && !$essence->jointFields )
							{
								$tmp[$key]->childBlocksCount = Entity2::getChildBlocksCount($essence->code, $val->id);
								$tmp[$key]->childElementsCount = Entity2::getChildElementsCount($essence->code, $val->id);
							}
						}
					}
					$result['treeItems'] = $tmp;
					$result['pagesHTML'] = drawPages($result['totalCount'], $p, $elPP, $onclick="Slonne.Entities.getEntities(Slonne.Entities.TREE_SETTINGS.essenceCode, ".$pid.", Slonne.Entities.TREE_SETTINGS.type, Slonne.Entities.TREE_SETTINGS.lang, ###)", $class="pg", $symbols = array('prev'=>'&larr;', 'next'=>'&rarr;'));
					
					
					$result['essence'] = $essence;
				}
				else 
					$result['error'] = 'Не найден essence ['.$essenceCode.']!';
			}
			else
				$result['error'] = 'Не передан essence code!';
		}
		else 	
			$result['error'] = 'Передан непонятный тип! ['.$type.']';	
			
		echo json_encode($result);
	}
	
	
	
	
	
	
	function view()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null); 
		
		//usleep(300000);
		//vd($_GET);
		$essenceCode = $_GET['essenceCode'];
		$id = $_GET['id'];
		$lang = $_CONFIG['langs'][$_REQUEST['lang']] ? $_CONFIG['langs'][$_REQUEST['lang']] : $_CONFIG['default_lang'];
		$type = $_GET['type'];
		if($type == Entity2::TYPE_BLOCKS || $type == Entity2::TYPE_ELEMENTS) 
		{
			if($essenceCode)
			{
				if($essence = Essence2::get($essenceCode))
				{
					$essence->initFields();
					$e = Entity2::get($essenceCode, $id, $type, $lang->code);
					if($e)
					{
						//echo "!";
						$e->initEssence($essence);
						//vd($e);
						$model['entity'] = $e;
						$model['essence'] = $essence;
						$model['type'] = $type;
						$model['lang'] = $lang->code;
					}
					else
						$model['error'] = 'Объект не найден! ['.$essenceCode.', '.$type.', '.$id.']';
				}
				else 
					$model['error'] = 'Не найден essence ['.$essenceCode.']!';
			}
			else
				$model['error'] = 'Не передан essence code!';
		}
		else 	
			$model['error'] = 'Передан непонятный тип! ['.$type.']';	
			
			
		Core::renderView('entities/view.php', $model);
	}
	
	
	
	
	
	
	
	function edit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null);
		
		//usleep(300000);
		//vd($_REQUEST);
		$essenceCode = $_REQUEST['essenceCode'];
		$id = $_REQUEST['id'];
		$pid = $_REQUEST['pid'];
		$lang = $_CONFIG['langs'][$_REQUEST['lang']] ? $_CONFIG['langs'][$_REQUEST['lang']] : $_CONFIG['default_lang'];
		$type = $_REQUEST['type'];
		if($type == Entity2::TYPE_BLOCKS || $type == Entity2::TYPE_ELEMENTS) 
		{
			if($essenceCode)
			{
				//$essence = new Essence($essenceCode);
				if($essence = Essence2::get($essenceCode))
				{
					$essence->initFields();
					$e = Entity2::get($essence->code, $id, $type, $lang->code);
					
					$pid = $pid ? $pid : $e->pid;
					//vd($pid);
					$model['entity'] = $e;
					$model['essence'] = $essence;
					$model['type'] = $type;
					$model['pid'] = $pid;
					$model['lang'] = $lang->code;
				}
				else 
					$model['error'] = 'Не найден essence ['.$essenceCode.']!';
			}
			else
				$model['error'] = 'Не передан essence code!';
		}
		else 	
			$model['error'] = 'Передан непонятный тип! ['.$type.']';	
			
			
		Core::renderView('entities/edit.php', $model);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	function editSubmit2()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null);

		$error = '';
		$problems = array();
		$warnings = array();
		$pidWas = null;
		$edit = false;
		
		//usleep(300000);
		//vd($_REQUEST);
		$essenceCode = $_REQUEST['essenceCode'];
		$id = $_REQUEST['id'];
		$pid = $_REQUEST['pid'];
		$lang = $_CONFIG['langs'][$_REQUEST['lang']] ? $_CONFIG['langs'][$_REQUEST['lang']] : $_CONFIG['default_lang'];
		$type = $_REQUEST['type'];
		if($type == Entity2::TYPE_BLOCKS || $type == Entity2::TYPE_ELEMENTS) 
		{
			if($essenceCode)
			{
				$essence = Essence2::get($essenceCode);
				if($essence)
				{
					$essence->initFields();
					
					$edit = true;
					
					$e = Entity2::get($essenceCode, $id, $type, $lang->code);
					if(!$e)		#	если создаём сущность
					{
						$e = new Entity2();
						//$essenceCode, $type, $lang
						$e->essenceCode =$essenceCode;
						$e->type = $type;
						$e->lang = $lang->code; 
						 
						$e->active = 1;
						$edit = false;
					}
					else
					{
						#	если сменился pid - то сделать изменения в дереве
						if($pid != $e->pid && $pid!='')
						{
							$pidWas = $e->pid;
						}
					}
					$e->initEssence($essence);
					
					#	приводим массив ФАЙЛС в удобный вид
					Field2::fixFILES();
					
					
					#	наполняем обьект данными из рекуэста
					$e->pid = $_REQUEST['pid'];
					$e->active = $_REQUEST['active'] ? 1 : 0;
					foreach($e->essence->fields[$e->type] as $key=>$field)
						if($field->type != 'pic' && $field->type != 'file' )
							$e->attrs[$field->code] = $_REQUEST[$field->code];
					
					#	валидация		
					$problems = $e->validate();
					
					if(!count($problems))	#	ВСЁ ХОРОШО! НАЧИНАЕМ СОХРАНЯТЬ
					{
						#	сохраняем ОДИНОЧНЫЕ картинки
						foreach($e->essence->fields[$e->type] as $key=>$field)
						{
							if( ($field->type == 'pic' || $field->type == 'file') && !$field->multiple)
							{
								$field->initEssence($e->essence);
								//vd($_FILES[$field->code][0]); die;
								if($_FILES[$field->code][0] !==null)
								{
									$saveFileResult = $field->saveUploadedMediaItem($_FILES[$field->code][0], $token = Field2::generateMediaName(), $field->type);
									if(!$saveFileResult['problem'])
										$e->attrs[$field->code] = $e->essence->code.'/'.$saveFileResult['newFileName'];
									else
										$warnings[] = $saveFileResult['problem'];
								}
							}
						}
						
						
						#	по идее ошибок мускула быть не должно, поэтому картинки сперва безнаказанно загружаются
						#	впрочем, вариантов нет, так что всё ровно) 
						DB::transactionStart();
						$mysqlError = '';
						if($edit)	
						{
							$e->update();
							$mysqlError = mysql_error();
						}
						else 	#	добваление во все языки
						{
							$e->idx = Entity2::getNextIdx($e->essence->code, $e->type, $e->lang, $e->pid );
							//vd($_CONFIG['langs']);
							foreach($_CONFIG['langs'] as $l=>$val)
							{
								$e2 = $e;
								$e2->lang = $l;
								$insertId = $e2->insert();
								//vd($e2);
								if(!$mysqlError)
									$mysqlError = mysql_error();
							}
							//vd($e);
							vd($insertId);
							$e->id = $insertId;
						}
						if(!$mysqlError)
						{
							#	работа с неприкасаемостью
							if($ADMIN->hasPrivilege($_GLOBALS['CURRENT_MODULE']->id, 'set_untouchability'))
							{
								$e->untouchable = $_REQUEST['untouchable'] ? 1 : 0;
								foreach($_CONFIG['langs'] as $ll=>$val)
								{
									$e3 = Entity2::get($e->essenceCode, $e->id, $e->type, $val->code);
									if($e3)
										$e3->setUntouchable($_REQUEST['untouchable']);
								}
							}
							
							DB::commit();
							
							//TODO: не забыть про варнинги картинок (толпы, и одинарных, которые не required)
							
							#	теперь сохранение картинок малтипылных
							$saveFileResult = null;
							foreach($e->essence->fields[$e->type] as $key=>$field)
							{
								if($field->type == 'pic' && $field->multiple)
								{
									$field->initEssence($e->essence);
									
									foreach($_FILES[$field->code] as $key2=>$file)
									{
										$saveFileResult = $field->saveUploadedMediaItem($file, $token = Field2::generateMediaName());
										if(!$saveFileResult['problem'])
										{
											$path = $e->essence->code.'/'.$saveFileResult['newFileName'];
											$m = new Media();
											$m->pid = $e->id;
											$m->active = 1;
											$m->essenceCode = $e->essence->code;
											$m->fieldCode = $field->code;
											$m->type = $e->type;
											$m->path = $path;
											
											$m->insert();
											
										}
										else
											$warnings[] = $saveFileResult['problem'];
									}
								}
							}

							#	редактирование тайтлов фоток
							foreach($_REQUEST['media'] as $key=>$val)
							{
								
								$m = Media::get($key);
								//vd($m);
								$m->title[$lang->code] = strPrepare($val);
								$m->update();
								//vd($m);
							}
						}
						else 
						{
							$error = $mysqlError;
							DB::rollback();
						}
					}
					else
					{
						foreach($problems as $problem)
							$problemTexts[] = ' - '.$problem['problem'];
							
						$error .= 'Возникли проблемы:<br> '.join('<br/>', $problemTexts);
					}		
				}
				else 
					$error = 'Не найден essence ['.$essenceCode.']!';
			}
			else
				$error = 'Не передан essence code!';
		}
		else 	
			$error = 'Передан непонятный тип! ['.$type.']';	
		
			
		#	результат
		$result = array(
					'edit'=>$edit,
					'e'=>$e,
					'error'=>$error, #	основной текст ошибки, который будет выведен
					'problems'=>$problems,	#	это большие ошибки, не позволяющие завершить транзакцию
					'warnings'=>$warnings,	#	небольшие варнинги
					'pidWas'=>$pidWas,	#	чтобы перерисовать в дереве
				);	
		//vd($e);
		//vd($result); 
		echo '<script>window.top.Slonne.Entities.editComplete('.json_encode($result).')</script>';	
		return;
		
	}
	
	
	
	
	
	
	function entitiesList()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null);
		
		//usleep(300000);
		//vd($_GET);
		//vd($_REQUEST);
		$essenceCode = $_REQUEST['essenceCode'];
		$pid = $_REQUEST['pid'];
		$lang = $_CONFIG['langs'][$_REQUEST['lang']] ? $_CONFIG['langs'][$_REQUEST['lang']] : $_CONFIG['default_lang'];
		$type = $_REQUEST['type'];
		//$page = $_REQUEST['p'];
		$p=intval($_REQUEST['p']) ? intval($_REQUEST['p'])-1 : 0;
		//vd($type);
		if($type == Entity2::TYPE_BLOCKS || $type == Entity2::TYPE_ELEMENTS) 
		{
			if($essenceCode)
			{
				//$essence = new Essence($essenceCode);
				if($essence = Essence2::get($essenceCode))
				{
					$essence->initFields();
					if($pid)
					{
						$ent = Entity2::get($essence->code, $pid, ($essence->jointFields ? Entity2::TYPE_ELEMENTS : Entity2::TYPE_BLOCKS), $lang->code);
					}
					$model['entity'] = $ent;
					$model['essence'] = $essence;
					$model['type'] = $type;
					$model['order'] = strPrepare($_REQUEST['order']);
					$model['order'] = $model['order'];
					$model['desc'] = intval($_REQUEST['desc']);
					
					$elPP=Entity2::LIST_ELEMENTS_PER_PAGE;
					//vd($p);
					//vd($model['order']);
					//$limit="LIMIT ".$p*$elPP.", ".$elPP."";
					
					$params = array(
								'essenceCode'=>$essenceCode,
								'pid'=>$pid,
								'limit'=>"LIMIT ".($p)*$elPP.", ".$elPP."",
								'type'=>$type, 
								'order'=>$model['order'].($model['desc'] ? ' DESC' : ''), 
								'lang'=>$lang->code, 
								'additionalClauses'=>'and 1',
								'activeOnly'=>false,
								
							);
							//vd($params);
					$list = Entity2::getList($params);
					$model['list'] = $list;

					$params['limit'] = '';
					$model['totalCount'] = Entity2::getCount($params);
					$model['elPP'] = $elPP;
					$model['p'] = $p;
				}
				else 
					$model['error'] = 'Не найден essence ['.$essenceCode.']!';
			}
			else
				$model['error'] = 'Не передан essence code!';
		}
		else 	
			$model['error'] = 'Передан непонятный тип! ['.$type.']';	
			
			
		Core::renderView('entities/list.php', $model);
	}
	
	
	
	
	
	
	
	
	function entitiesListTranslationJson()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null); 
		//usleep(300000);
		
		$essenceCode = $_REQUEST['essenceCode'];
		$pid = $_REQUEST['pid'];
		$lang = $_CONFIG['langs'][$_REQUEST['lang']] ? $_CONFIG['langs'][$_REQUEST['lang']] : $_CONFIG['default_lang'];
		
		$result = array();
		if($essenceCode)
		{
			$essence = Essence2::get($essenceCode);
			if($essence)
			{
				$type = $essence->jointFields ? Entity2::TYPE_ELEMENTS : Entity2::TYPE_BLOCKS;
				
				$params = array(
								'essenceCode'=>$essence->code,
								'pid'=>'999999999 OR id IN('.strPrepare(join(', ', $_REQUEST['ids'])).')',
								'limit'=>'',
								'type'=>$type, 
								'order'=>'', 
								'lang'=>$lang->code, 
								'additionalClauses'=>'and 1',
								'activeOnly'=>false,
							);
				$tmp = Entity2::getList($params); 
				
				if(!$essence->linear)
				{
					foreach($tmp as $key=>$val)
					{
						if($type == Entity2::TYPE_ELEMENTS || $essence->jointFields )
						{
							$tmp[$key]->childBlocksCount = Entity2::getChildElementsCount($essence->code, $val->id);
						}
						if($type == Entity2::TYPE_BLOCKS && !$essence->jointFields )
						{
							$tmp[$key]->childBlocksCount = Entity2::getChildBlocksCount($essence->code, $val->id);
							$tmp[$key]->childElementsCount = Entity2::getChildElementsCount($essence->code, $val->id);
						}
					}
				}
				$result['result']['treeItems'] = $tmp;
				$result['essence'] = $essence;
			}
			else 
				$result['error'] = 'Не найден essence ['.$essenceCode.']!';
		}
		else
			$result['error'] = 'Не передан essence code!';
	
			
		echo json_encode($result);
	}
	
	
	
	
	
	
	
	
	function treeSaveChanges()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null); 
		//vd($_REQUEST);
		
		$type = $_REQUEST['type'] ;
		$essence = Essence2::get($_REQUEST['essenceCode']);
		$lang = $_CONFIG['langs'][$_REQUEST['lang']] ? $_CONFIG['langs'][$_REQUEST['lang']] : $_CONFIG['default_lang'];
		
		if($essence)
		{
			if($type == Entity2::TYPE_ELEMENTS || $type == Entity2::TYPE_BLOCKS)
			{
				$langsArr = array_keys($_CONFIG['langs']);
				if($lang->code)
					$langsArr = array($lang->code);
					
				foreach($langsArr as $key=>$l)
				{
					foreach($_REQUEST['idx'] as $id=>$idx)
					{
						Entity2::setIdx($essence->code, $type, $l,  $id, $idx);
					}
				}
			}
			else 
				$error = 'Передан непонятный тип! ['.$type.']';
		}
		else 
			$error = 'Не найден essence ['.$essenceCode.']!';
		
		$str.='
		<script>';
		if($error)
		{
			$str.='
			window.top.error("'.$error.'")';
		}
		else
		{
			$str.='
			window.top.Slonne.Entities.treeSaveChangesComplete();
			window.top.notice("Изменения сохранены! Для перерисовки дерева, обновите страницу")';
		}
		$str.='
		</script>';
		echo $str;
	}
	
	
	
	
	
	
	
	
	
	function listSaveChanges()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null); 		
		//echo "!";
		$type = $_REQUEST['type'] ;
		
		$essence = Essence2::get($_REQUEST['essenceCode']);
		$lang = $_CONFIG['langs'][$_REQUEST['lang']] ? $_CONFIG['langs'][$_REQUEST['lang']] : $_CONFIG['default_lang'];
		
		if($essence)
		{
			if($type == Entity2::TYPE_ELEMENTS || $type == Entity2::TYPE_BLOCKS)
			{
				#	сохраняем IDX
				$langsArr = array_keys($_CONFIG['langs']);
				foreach($langsArr as $key=>$l)
				{
					foreach($_REQUEST['idx'] as $id=>$idx)
					{
						Entity2::setIdx($essence->code, $type, $l,  $id, $idx);
					}
				}
				
				#	УДАЛЕНИЕ
				$idsToDelete = array_keys($_REQUEST['del']);
				foreach($idsToDelete as $key=>$val)
				{
					$e = Entity2::get($essence->code, $val, $type, $lang->code);
					
					if($e)
					{
						$e->delete();
					}
					else 
						$error = 'Обьект не найден! ['.$val.']';
				}	
			}
			else 
				$error = 'Передан непонятный тип! ['.$type.']';
		}
		else 
			$error = 'Не найден essence ['.$essenceCode.']!';
		
		$str.='
		<script>';
		if($error)
		{
			$str.='
			window.top.error("'.addslashes($error).'")';
		}
		else
		{
			$str.='
			window.top.Slonne.Entities.listSaveChangesComplete();
			window.top.notice("Изменения сохранены! ")';
		}
		$str.='
		</script>';
		echo $str;
	}
	
	
	
	
	
	
	function delete()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null); 		
		
		$id = intval($_REQUEST['id']);
		$type = $_REQUEST['type'] ;
		$essence = Essence2::get($_REQUEST['essenceCode']);
		//$lang = $_CONFIG['langs'][$_REQUEST['lang']] ? $_REQUEST['lang'] : LANG;
		$lang = $_CONFIG['langs'][$_REQUEST['lang']] ? $_CONFIG['langs'][$_REQUEST['lang']] : $_CONFIG['default_lang'];
		//vd($lang);
		$error = '';
		
		if($essence)
		{
			if($type == Entity2::TYPE_ELEMENTS || $type == Entity2::TYPE_BLOCKS)
			{
				$e = Entity2::get($essence->code, $id, $type, $lang->code);
				//vd($e);
				if($e)
				{ 
					if(!$e->untouchable || ($e->untouchable && $ADMIN->hasPrivilege($_GLOBALS['CURRENT_MODULE']->id, 'delete_untouchable')) )
						$e->delete();
					else
						$error = 'Этот обьёкт неприкосновенен.';		
				}
				else 
					$error = 'Обьект не найден! ['.$_REQUEST['id'].']';
			}
			else 
				$error = 'Передан непонятный тип! ['.$type.']';
		}
		else 
			$error = 'Не найден essence ['.$essenceCode.']!';
		
		
		
		$result = array();
		$result['error'] = $error;
		
		echo json_encode($result);
	}
	
	
	
	
	
	
	
	function setActive()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null); 		
		
		$id = intval($_REQUEST['id']);
		$type = $_REQUEST['type'] ;
		$essenceCode = $_REQUEST['essenceCode'];
		$lang = $_CONFIG['langs'][$_REQUEST['lang']] ? $_CONFIG['langs'][$_REQUEST['lang']] : $_CONFIG['default_lang'];
		$error = '';
		
		$essence = Essence2::get($_REQUEST['essenceCode']);
		if($essence)
		{
			if($type == Entity2::TYPE_ELEMENTS || $type == Entity2::TYPE_BLOCKS)
			{
				//$e = new Entity($essence->attrs['code'], $_REQUEST['id'], $type, $lang);
				$e = Entity2::get($essence->code, $id, $type, $lang->code);
				if($e)
					$e->setActive($_REQUEST['value']);
				else 
					$error = 'Обьект не найден! ['.$_REQUEST['id'].']';
			}
			else 
				$error = 'Передан непонятный тип! ['.$type.']';
		}
		else 
			$error = 'Не найден essence ['.$essenceCode.']!';
		
		$result = array();
		$result['error'] = $error;
		
		echo json_encode($result);
	}
	
	
	
	
	
	
	
	function deleteMedia()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null);
		$error = ''; 		
		
		//echo "!";
		$id = intval($_REQUEST['id']);
		$m = Media::get($id);
		if($m)
		{
			//vd($m);
			$essence = Essence2::get($m->essenceCode);
			$f = Field2::getByCode($m->fieldCode, $essence->id);
			//vd($f);
			$e = Entity2::get($essence->code, $m->pid, $m->type, LANG);
			//vd($e);
			
			if($f->required && count($e->attrs[$m->fieldCode]) == 1)
				$error = 'Хотя бы одна картинка у поля должна остаться! ';
			else 
				$m->delete();
		}
		else 
			$error = 'Ошибка! Картинка не найдена..'.($_REQUEST['id'] ? '['.$_REQUEST['id'].']' : '').'';
		
		//$error = '!!';
		$result = array();
		$result['error'] = $error;
		
		echo json_encode($result);
	}
	
	
	
	
	
}




?>