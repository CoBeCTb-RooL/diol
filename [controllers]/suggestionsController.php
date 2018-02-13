<?php
class SuggestionsController extends MainController{
	
	const ITEMS_PER_PAGE = 10;
	
	
	function routifyAction()
	{
		global $CORE;
		$section = $CORE->params[0];
		$p = $CORE->params[1];
	
		if(!$section || strpos($section, 'p_')===0)
			$action='index';
		
		if($action)
			$CORE->action = $action;
	}
	
	
	function index()
	{
		self::list1();
	}
	
	function list1()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
				
		$CONTENT->setTitle('Вопросы, предложения');	
		
		$MODEL['p'] = $_REQUEST['p'] ? $_REQUEST['p'] : 1 ;
		$MODEL['p'] = $CORE->specialParams['p'] ? $CORE->specialParams['p'] : 1 ;
		$MODEL['elPP'] = self::ITEMS_PER_PAGE;
		
		$MODEL['from'] = ($MODEL['p']-1)*$MODEL['elPP'];
		
		$MODEL['suggestionType'] = SuggestionType::code($_REQUEST['type']) ? SuggestionType::code($_REQUEST['type']) : SuggestionType::code(SuggestionType::QUESTION); 
		
		$status = Status::code(Status::ACTIVE);
		if($s = Status::code($_REQUEST['status']))
			$status = $s;
		
		$MODEL['status'] = $status;
		
		$params = array(
				'status' => $MODEL['status'],
				'suggestionType' => $MODEL['suggestionType'],
				'from' => $MODEL['from'],
				'count' => $MODEL['elPP'],
				'orderBy' => 'id',
				'desc' => true,
		);
		
		$MODEL['items'] = Suggestion::getList($params);
		$MODEL['totalCount'] = Suggestion::getCount($params);
		//vd($MODEL['totalCount']);
		
		
		
		$crumbs = array();
		$crumbs[] = '<a href="'.Route::getByName(Route::MAIN)->url().'">Главная</a>';
		$crumbs[] = 'Вопросы, предложения';
		$MODEL['crumbs'] = $crumbs;
		
		Core::renderView('suggestions/list.php', $MODEL);
	}
	
	
	
	
	function add()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		//Startup::execute(Startup::FRONTEND);
		
		$CORE->setLayout(null);
		
		$errors = null;
		specialCharizeArray($_REQUEST);
		
		$s = new Suggestion();
		$s->name = $_REQUEST['name'];
		$s->email = $_REQUEST['email'];
		$s->phone = $_REQUEST['phone'];
		$s->text = $_REQUEST['text'];
		$s->suggestionType = SuggestionType::code($_REQUEST['suggestionType']);
		$s->status = Status::code(Status::MODERATION);
		
		$errors = $s->validate();
		
		if($_REQUEST['captcha'] != $_SESSION['captcha_keystring'])
			$errors[] = Slonne::setError('captcha', 'Вы ввели неправильный <b>код подтверждения</b>');
		
			
		if(!$errors)
		{
			if($_SESSION['user']['id'])
			{
				$u = User::get($_SESSION['user']['id'], Status::code(Status::ACTIVE));
				if($u)
					$s->userId = $u->id;
			}
			$s->insert();
		}
		
		$arr['errors'] = $errors;
		echo json_encode($arr);
	}
	
	
	
	
	
	function editForm()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		//Startup::execute(Startup::FRONTEND);
		
		$CORE->setLayout(null);
		
		if(Admin::isAdmin())
		{
			$MODEL['item'] = Suggestion::get($_REQUEST['id']);
		}
		else 
			$MODEL['error'] = 'Нет прав';
		
		
		Core::renderView('suggestions/edit.php', $MODEL);
	}
	
	
	
	
	
	
	function update()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		//Startup::execute(Startup::FRONTEND);
	
		$CORE->setLayout(null);
	
		$errors = null;
		if(Admin::isAdmin() )
		{
			if( $s = Suggestion::get($_REQUEST['id']) )
			{
				specialCharizeArray($_REQUEST);
				
				$s->name = $_REQUEST['name'];
				$s->email = $_REQUEST['email'];
				$s->phone = $_REQUEST['phone'];
				$s->text = $_REQUEST['text'];
				$s->answer = $_REQUEST['answer'];
			
				$errors = $s->validate();
				
				if(!$errors)
					$s->update();
			}		
		}
		else
			$errors[] = Slonne::setError('', 'Нет прав.');

		$arr['errors'] = $errors;
		echo json_encode($arr);
	}
	
	
	
	
	function setStatus()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		//Startup::execute(Startup::FRONTEND);
	
		$CORE->setLayout(null);
	
		$errors = null;
		if(Admin::isAdmin() )
		{
			if( $s = Suggestion::get($_REQUEST['id']) )
			{
				if($status = Status::code($_REQUEST['status']) )
				{
					$s->status = $status;
					$s->update();
				}
				else
					$errors[] = Slonne::setError('', 'Ошибка статуса. ['.$_REQUEST['status'].']');
			}
			else
				$errors[] = Slonne::setError('', 'Ошибка! Объект не найден.');
		}
		else
			$errors[] = Slonne::setError('', 'Нет прав.');
		
	
		$arr['errors'] = $errors;
		echo json_encode($arr);
	}
	
	
	
	
}




?>