<?php
class Cabinet{
	
	
	function dateOfBirthInput($birthdate)
	{
		global $_CONST;
		
		if($birthdate)
		{
			$day = intval(substr($birthdate, 8, 2));
			$month = intval(substr($birthdate, 5, 2));
			$year = intval(substr($birthdate, 0, 4));
		}
		
		#	день
		$str.='
		<select name="day" id="day">
			';
		for($i=1; $i<=31; $i++)
			$str.='
			<option value="'.$i.'" '.($i == $day ? ' selected="selected" ' : '').'>'.$i.'</option>';
		$str.='
		</select>';
		
		#	месяц
		$str.='
		<select name="month" id="month">
			';
		foreach(Funx::$months[$_SESSION['lang']] as $key=>$val)
		{
			$str.='
			<option value="'.$key.'" '.($key == $month ? ' selected="selected" ' : '').'>'.$val[1].'</option>';
		}
		$str.='
		</select>';
	
		#	год
		$str.='
		<select name="year" id="year">
			';
		$y=date('Y')-17;
		//vd($y);
		for($i=$y; $i>=($y-80); $i--)
			$str.='
			<option value="'.$i.'" '.($i == $year ? ' selected="selected" ' : '').'>'.$i.'</option>';
		$str.='
		</select>';	
		
		
		return $str;
	}
	
	
	
	
} 
?>