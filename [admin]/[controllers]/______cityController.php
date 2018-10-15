<?php
class CityController extends MainController{
	
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
		
		if(isset($_REQUEST['from_file']))
		{
			$file = ROOT.'/'.INCLUDE_DIR.'/content/kzCities.txt';
			$str = file_get_contents($file);
			$tmp = explode("\r\n", $str);
			foreach($tmp as $key=>$val)
			{
				if(trim($val))
					$cities[] = mb_strtoupper(mb_substr(trim($val), 0, 1)) .  mb_substr(trim($val), 1);
			}
			vd($cities);
				
			if($_REQUEST['grab_from_file'])
			{
				foreach($cities as $key=>$c)
				{
					vd($c);
					if(!City::getByName($c))
					{
						$a = new City();
						$a->status = Status::$items[Status::ACTIVE];
						$a->name = $c;
						$a->countryId = Country::KAZAKHSTAN_ID;
						$a->insert();
					}
				}
			}
		
		}
		
		
		Core::renderView('cities/indexView.php', $model);
	}
	
	
	
	
	function list1()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		$CORE->setLayout(null);
	
		//vd($_REQUEST);
		$MODEL['list'] = City::getList($countryId=null, $status=null, $orderBy='isLarge DESC, name');
	
		Core::renderView('cities/list.php', $MODEL);
	}
	
	
	
	function edit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		$CORE->setLayout(null);

		$error = '';
		if($id = intval($_REQUEST['id']))
		{
			$MODEL['item'] = City::get($id);
			if(isset($_REQUEST['id']) && !$MODEL['item'])
				$error = 'Ошибка! Мера не найдена. ';
		}
		
		$MODEL['error'] = $error;
	
		Core::renderView('cities/edit.php', $MODEL);
	}
	
	
	
	function editSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		$CORE->setLayout(null);
	//vd($_REQUEST);
		$errors = null;
		if($name=trim($_REQUEST['name']))
		{
			$item = City::get($_REQUEST['id']);
			
			if($item)
			{
				$prev = clone $item;
				/*$prev = new City();
				$prev->name = $item->name;
				$prev->isLarge = $item->isLarge;*/
				
				$byName = City::getByNameAndCountryId($name, Country::KAZAKHSTAN_ID);
				if($byName && $byName->id != $item->id)
					$errors[] = Slonne::setError('name', 'Этот город уже существует в этой стране!');
				else
				{	
					$item->name = $name;
					$item->isLarge = $_REQUEST['isLarge'] ? true : false; 
					
					$changes = array();
					if($item->name != $prev->name)
						$changes[] = 'Название изменено с "'.$prev->name.'" на "'.$item->name.'"';
					if($item->isLarge != $prev->isLarge)
						$changes[] = 'Поле КРУПНЫЙ изменено с "'.($prev->isLarge?'ДА':'НЕТ').'" на "'.($item->isLarge?'ДА':'НЕТ').'"';
	
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
			}
			else
			{
				$byName = City::getByNameAndCountryId($name, Country::KAZAKHSTAN_ID);
				if($byName)
					$errors[] = Slonne::setError('name', 'Этот город уже существует в этой стране!');
				else
				{
					$item = new City();
					$item->name = $name;
					$item->status = Status::code(Status::ACTIVE);
					$item->countryId = Country::KAZAKHSTAN_ID;
					$item->isLarge = $_REQUEST['isLarge'] ? true : false;
					$item->id = $item->insert();
					$msg = 'Создан';
					$journalEntryType = JournalEntryType::code(JournalEntryType::CREATE);
				}
			}
			
			if(!$noNeedToJournalize)
			{
				$je = new JournalEntry();
				$je->objectType = Object::code(Object::CITY);
				$je->objectId = $item->id;
				$je->journalEntryType = $journalEntryType;
				$je->comment = $msg;
				$je->adminId = $ADMIN->id;
				$je->param1 = $param1;
				$je->insert();
			}
		}
		else 
			$errors[] = Slonne::setError('name', 'Вы не указали значение!');
		
	
		$json['errors'] = $errors;
		$json['status'] = $statusToBe;

		echo json_encode($json);
	}
	
	
	
	
	function switchStatus()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		$CORE->setLayout(null);
	
		$error = '';
	
		$item = City::get($_REQUEST['id']);
		if($item)
		{
			$prevStatus = $item->status;
			$statusToBe = $item->status->code == Status::ACTIVE ? Status::code(Status::INACTIVE) : Status::code(Status::ACTIVE);
			//vd($statusToBe);
	
			$item->status = $statusToBe;
			$item->update();
			
			$je = new JournalEntry();
			$je->objectType = Object::code(Object::CITY);
			$je->objectId = $item->id;
			$je->journalEntryType = JournalEntryType::code(JournalEntryType::STATUS_CHANGED);
			$je->comment = 'Смена статуса с "'.$prevStatus->code.'" на "'.$statusToBe->code.'"';
			$je->adminId = $ADMIN->id;
			$je->param1 = $item->status->num;
			$je->insert();
		}
		else
			$error='Ошибка! Не найден город '.$_REQUEST['id'].'';
	
		$json['error'] = $error;
		$json['status'] = $statusToBe;

		echo json_encode($json);
	}
	
	
	
	
	
	
	
	
}




?>