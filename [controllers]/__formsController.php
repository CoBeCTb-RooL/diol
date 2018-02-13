<?php


$ACTION = $_PARAMS[0].$_PARAMS[1]; 	#	при сабмите будет просто добавляться слово Submit


	
class FormsController extends MainController{
	
	
	function feedback()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		
		$_GLOBALS['TITLE'] = Slonne::getTitle('Обратная связь');	
		Core::renderView('forms/feedbackForm.php');	
	}
	function feedbackSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		$_GLOBALS['NO_LAYOUT'] = true;
		
		FeedbackFormStatic::submit();
	}
	 
	
	
	
	
}















/*************************************************/
/*************************************************/
/*************************************************/
/*************************************************/
class FeedbackFormStatic extends Form  {
	const FORM_ID = 'feedback-form'; 
	static $emails = array();
	static $fields = array( 
						array('name'=>'name', 'msg'=>'Пожалуйста, введите Ваше имя.'),  
						//array('name'=>'tel', 'msg'=>'Пожалуйста, введите Ваш телефон.'),
						array('name'=>'msg', 'msg'=>'Пожалуйста, введите текст сообщения.'),
					);

	function submit()
	{
		global $_CONST;
		
		#	здесь будут скапливаться проблемы (массив)
		$errors = Form::getErrors(self::$fields);
				
		#	ЗДЕСЬ МОГУТ БЫТЬ ДОП. ПРОВЕРКИ
		
		if(count($errors))
			self::errorStatic(self::FORM_ID, $errors);
		else 
		{
			self::send();
			self::successStatic(self::FORM_ID);
		}
	}
	

	function send()
	{
		global $_CONST, $_CONFIG;
		
		$subject = 'Заявка с сайта '.$_SERVER['HTTP_HOST'];
		
		#	сообщение
		$msg.='
		<h3>Сообщение с сайта '.$_SERVER['HTTP_HOST'].'</h3>
		<div>Имя: <b>'.$_REQUEST['name'].'</b></div>
		<div>Телефон: <b>'.$_REQUEST['tel'].'</b></div>
		<div>Сообщение: <b>'.$_REQUEST['msg'].'</b></div>
		';

		$emails = self::getEmails();
		self::sendEmails($emails, $subject, $msg);
	}
	

	
}
	
?>