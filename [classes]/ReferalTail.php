<?php

class ReferalTail{

	static $sessionKey = 'ReferalTail';
	
	static $utm = array(
		"utm_source"=>"",
		"utm_medium"=>"",
		"utm_campaign"=>"",
		"utm_content"=>"",
		"utm_term"=>"",
		"network"=>"",
		"placement"=>"",
		"position"=>"",
		"adid"=>"",
		"match"=>"",
		"keyword"=>"",
	);
	
	
	
	function init()
	{
		/*var_dump(self::$utm);
		echo '<hr>';
		var_dump($_REQUEST);*/
		foreach(self::$utm as $key=>$val)
		{
			//var_dump($_REQUEST[$key]);
			if($_REQUEST[$key])
				$_SESSION[self::$sessionKey][$key] = trim($_REQUEST[$key]);
		}
	}
	
	
	
	function info()
	{
		$str = '';
		if($_SESSION[ReferalTail::$sessionKey])
		{
			$str.='<p><p><hr>РЕФЕРАЛЬНЫЕ ХВОСТЫ:';
			foreach(self::$utm as $key=>$val)
			{
				if($a = $_SESSION[self::$sessionKey][$key])
					$str.='<br><b>'.$key.'</b> = '.$a.'';
			}
		}
		
		return $str;
	}
	
} 
?>