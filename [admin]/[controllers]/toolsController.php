<?php
class ToolsController extends MainController{
	
	
	
	function index()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		Core::renderView('tools/indexView.php', $model);
	}
	
	
	
	
	function recache()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(/*Startup::ADMIN*/);
		
		$CORE->setLayout(null); 
		
		$error = '';
		
		AdvQuan::recacheAll();
		
		# 	журналируем
		$je = new JournalEntry();
		$je->objectType = Object::code(Object::TOOL);
		$je->objectId = null;
		$je->journalEntryType = JournalEntryType::code(JournalEntryType::TOOL_ADV_QUANS_RECACHE);
		$je->comment = null;
		$je->adminId = $ADMIN->id;
		$je->insert();
		
		
		$json['error'] = $error;
		echo json_encode($json);
	}	
	
	
	
	
	
	function showTimers()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null); 
		
		Timer::fixSessionTimers();
		
		Core::renderView(SHARED_VIEWS_DIR.'/adminTools/timers.php', $arr = array('timers'=>$_SESSION['timers']), $buffer=false, $ignoreIsAdminka=true);
	}	
	
	
	
	
	
	
	function report()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		//$CORE->setLayout(null);
		$MODEL['dateStart'] = $_REQUEST['dateStart'] ? $_REQUEST['dateStart'] : date('Y-m-d');
		$MODEL['toolType'] = ToolType::code($_REQUEST['toolType']) ? ToolType::code($_REQUEST['toolType']) : null ;  
		
		$MODEL['list'] = ToolStat::getList($params = array(
				'dateStart'=>$MODEL['dateStart'],
				'toolType'=>$MODEL['toolType'],
				'orderBy'=>'id DESC', 
		));
		
		$secs = strtotime($MODEL['dateStart']);
		$MODEL['prevDate'] = date('Y-m-d', $secs-60*60*24);
		$MODEL['nextDate'] = date('Y-m-d', $secs+60*60*24);
		
		
		Core::renderView('tools/report.php', $MODEL);
	}
	
	
	
	
}




?>