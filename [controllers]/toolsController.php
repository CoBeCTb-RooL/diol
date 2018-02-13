<?php
class ToolsController extends MainController{
	
	/*function routifyAction()
	{
		global $CORE;
		$section = $CORE->params[0];
		$p = $CORE->params[1];
		
		if($action)
			$CORE->action = $action;
	}*/
		
		
	
	
	function index()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		$CORE->setLayout(null);
		
		Core::renderView('tools/index.php', $MODEL);
	}
	
	
	
	# 	пересчёт кол-ва объявлений
	function advsCountRecache()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		$CORE->setLayout(null);
		
		
		# 	сразу обозначаем какой тул
		$toolType = ToolType::code(ToolType::ADV_COUNT_RECACHE);
		
		# 	пытаемся выявить сразу тип запуска
		$execType=ToolExecType::code($_GET['execType']);
		
		# 	если нажата кнопка ГОУ - мы полюбому записываем стату, 
		# 	чтобы видеть, когда реально совершались попытки запуска, и контролить их фэйлы
		if($_REQUEST['go_btn'])
		{
			# 	начало создания отчёта
			$stat = new ToolStat();
			$stat->begin($toolType, $execType);
		}
		
		
		if($_GET[Core::CRON_KEY_1] == Core::CRON_VALUE_1)
		{
			if($execType)
			{
				echo '<h1>'.$toolType->name.'</h1>';
				echo 'Пересчёт количества объявлений по категориям / городам и тд.  <input type="button" onclick="if(confirm(\'Запустить?\')){location.href=location.href+\'&go_btn=1\';} " value="Запустить" />';
				
				if($_REQUEST['go_btn'])
				{
					ob_flush();
					flush();
					
					# 	ТУЛ
					$t = new Timer('блабла');
					AdvQuan::recacheAll();
					$t->stop();
					echo '<p>Завершено за '.$t->time.' сек.';
					# 	/ТУЛ
					
					# 	закрываем статистику выполнения
					$stat->success($text);
					
				} 
			}
			else
			{
				if($_REQUEST['go_btn'])
					$stat->fail('EXEC_TYPE_ERROR ['.$_REQUEST['execType'].']');
				echo 'EXEC_TYPE_ERROR. =(';
			}
		}
		else
		{
			if($_REQUEST['go_btn'])
				$stat->fail('CRON_SALT_ERROR');
			echo 'CRON_SALT_ERROR. =(';
		}
	}
	
	
	
	
	
	
	function setExpired()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		$CORE->setLayout(null);
	
		
		# 	сразу обозначаем какой тул
		$toolType = ToolType::code(ToolType::SET_EXPIRED);
		
		# 	пытаемся выявить сразу тип запуска
		$execType=ToolExecType::code($_GET['execType']);
		
		# 	если нажата кнопка ГОУ - мы полюбому записываем стату,
		# 	чтобы видеть, когда реально совершались попытки запуска, и контролить их фэйлы
		if($_REQUEST['go_btn'])
		{
			# 	начало создания отчёта
			$stat = new ToolStat();
			$stat->begin($toolType, $execType);
		}
		
		
		if($_GET[Core::CRON_KEY_1] == Core::CRON_VALUE_1)
		{
			$execType=ToolExecType::code($_GET['execType']);
			if($execType)
			{
				$toolType = ToolType::code(ToolType::SET_EXPIRED);
				echo '<h1>'.$toolType->name.'</h1>';
				echo 'Выставление статуса ПРОСРОЧЕН  <input type="button" onclick="if(confirm(\'Запустить?\')){location.href=location.href+\'&go_btn=1\';} " value="Запустить" /><p>';
				
				$t = new Timer('');
				$list = AdvItem::getExpiredList();
				
				if($list)
				{
					$str.='Объявлений к блокировке: '.count($list).'';
					echo $str;
					if($_REQUEST['go_btn'])
					{
						foreach($list as $key=>$item)
						{
							echo '<br>'.($key+1).')  id: '.$item->id.'';
							ob_flush();
							flush();
							//usleep(200000);
							
							if($_REQUEST['go_btn'])
							{
								$item->setStatus(Status::code(Status::EXPIRED));
								echo ' - ok';
								ob_flush();
								flush();
								//usleep(200000);
							}
						}
					}
				}
				else
				{
					echo 'Объявлений нет.';
				}
				$t->stop();
				
				
				if($_REQUEST['go_btn'])
				{
					# 	закрываем статистику выполнения
					$stat->param1 = count($list);
					$stat->text = count($list) ? 'Проработано объявлений: '.count($list).'' : 'Объявлений для блокировки нет.';
					$stat->success();
					
					echo '<p>Завершено за '.$t->time.' сек.';
				}
			}
			else
			{
				if($_REQUEST['go_btn'])
					$stat->fail('EXEC_TYPE_ERROR ['.$_REQUEST['execType'].']');
				echo 'EXEC_TYPE_ERROR. =(';
			}
		}
		else
		{
			if($_REQUEST['go_btn'])
				$stat->fail('CRON_SALT_ERROR');
			echo 'CRON_SALT_ERROR. =(';
		}
	}
	
	
	
	
	
}




?>