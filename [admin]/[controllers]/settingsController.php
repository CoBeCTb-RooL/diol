<?php
class SettingsController extends MainController{
	
	
	
	function index()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);

		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			if($_REQUEST['go_btn'])
			{
				Settings::save($_REQUEST);
				
				echo'Сохранено!';
			}
			
			$_CONFIG['SETTINGS'] = Settings::get();
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
		
		Core::renderView('settings/index.php', $MODEL);
	}
	
	
	
	
	
	
	
	
	
	
	
}




?>