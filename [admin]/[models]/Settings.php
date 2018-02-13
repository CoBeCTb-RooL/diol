<?php 
class Settings{
	
	const TBL = 'slonne__settings';
	
	var $attrs;
		
	
	
	function get()
	{
		$sql="SELECT * FROM `".self::TBL."` LIMIT 1";
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		if(mysql_num_rows($qr))
		{
			$next = mysql_fetch_array($qr, MYSQL_ASSOC);
			//vd($next);
			return $next;
		}
	}
	
	
	
	function save($arr)
	{
		if($arr)
		{
			$sql = "
			UPDATE `".self::TBL."` 
			SET 
			
			  `title_postfix` = '".strPrepare($arr['title_postfix'])."'
			, `title_separator` ='".mysql_real_escape_string($arr['title_separator'])."'
			, `description` ='".strPrepare($arr['description'])."'
			, `keywords` ='".strPrepare($arr['keywords'])."'
			
			
			";
			//vd($sql);
			$qr=DB::query($sql);
			echo mysql_error();
		}
	}
	
	
	
}
?>