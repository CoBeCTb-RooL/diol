<?php
class CommentController extends MainController{
	
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
		//vd($_GLOBALS);
		
		if($ADMIN->hasRole(Role::COMMENT_MODERATOR) )
		{
			$params = array(
					'status' => Status::code(Status::MODERATION),
					'from' => 0,
					'count' => /*AdvItem::COMMENTS_PER_PAGE*/'',
					'orderBy' => '',
					'desc' =>'',
			);
			$MODEL['comments'] = Comment::getList($params);
			foreach($MODEL['comments'] as $c)
				$userIdsToTake[] = $c->userId;
				//vd($userIdsToTake);
			$users = User::getByIdsList($userIdsToTake);
			foreach($MODEL['comments'] as $c)
				$c->user = $users[$c->userId];
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
		
		Core::renderView('comments/indexView.php', $MODEL);
	}
	
	
	
	
	function setStatus()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
		
		
		//usleep(200000);
		//vd($_REQUEST);
		//vd($_SESSION['captcha_keystring']);
		$errors = null;
		
		if($ADMIN->hasRole(Role::COMMENT_MODERATOR) )
		{
			$status = Status::code($_REQUEST['status']);
			if($status)
			{
				$item = Comment::get($_REQUEST['id']);
				if($item)
				{
					if(Admin::isAdmin())
					{
						$prevStatus = $item->status->code;
						//vd($prevStatus);
						$item->setStatus($status);
						
						$je = new JournalEntry();
						$je->objectType = Object::code(Object::COMMENT);
						$je->objectId = $item->id;
						$je->journalEntryType = JournalEntryType::code(JournalEntryType::STATUS_CHANGED);
						$je->comment = 'Статус изменён с "'.$prevStatus.'" на "'.$status->code.'"';
						$je->adminId = $ADMIN->id;
						$je->param1 = $status->code;
						
						// 	если сменилось с МОД на АКТИВ
						if($prevStatus == Status::MODERATION && $status->code == Status::ACTIVE)
						{
							$je->journalEntryType = JournalEntryType::code(JournalEntryType::APPROVE);
							$je->comment = 'Комментарий одобрен.';
						}
						$je->insert();
						
						// 	отссылка письма хозяину объявления
						if($prevStatus == Status::MODERATION && $status->code == Status::ACTIVE)
						{
							$u = User::get($item->userId);
							//vd($u);
							if($u)
							{
								$adv = AdvItem::get($item->pid);
								
								$m = new Mail();
								$m->to = $u->email;
								$m->from = ROBOT_EMAIL;
								$m->subject = 'Новый комментарий на ваше объявление на сайте '.DOMAIN_CAPITAL;
								$arr = array(
										'name'=>$u->name.' '.$u->fathername,
										'url'=>'http://'.$_SERVER['HTTP_HOST'].$adv->url().'#comment-'.$item->id,
								);
								$m->msg = Mail::getMsgForNewCommentForAdvNotification($arr);
								
								$m->send();
							}
						}
					}
					else
						$errors[] = new Error('Вы не админ! ');
				}
				else
					$errors[] = new Error('Ошибка! Объявление не найдено.');
			}
			else 
				$errors[] = new Error('ОШИБКА СТАТУСА');
		}
		else 
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
		
		$res['errors'] = $errors;
		$res['status'] = $status->code; 
		echo json_encode($res);
	}
	
	
	
	
	
	
	
}




?>