<?php
class Url{
	
	function parse($url)
	{
		$urlParts=explode("/", $url);
		foreach($urlParts as $key=>$val)
			if($val = trim($val))
				$tmp[] = $val;
		$urlParts = $tmp;
				
		return $urlParts;
	}
	
} 
?>