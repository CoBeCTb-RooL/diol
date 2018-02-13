<?php
class testController extends MainController{


	function routifyAction()
	{
		global $CORE;
			$action = 'index';

		if($action)
			$CORE->action = $action;
	}
	
	
	function index()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
				
		$CONTENT->setTitle('test');
		
echo "!";
//vd($CORE);
		//Core::renderView('news/newsList.php', $MODEL);
	}
	

	
}




?>