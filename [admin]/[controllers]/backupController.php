<?php
class BackupController extends MainController{
	
	
	
	function index()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		if($ADMIN->hasRole(Role::SUPER_ADMIN) )
		{
			
		}
		else 
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
		
		Core::renderView('backup/indexView.php', $MODEL);
	}
	
	
	
	
}




?>