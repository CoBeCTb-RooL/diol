<?php
class IndexController extends MainController{
	
	
	
	function index()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		

		Core::renderView('index/indexView.php', $MODEL);
	}
	
	
	
}




?>