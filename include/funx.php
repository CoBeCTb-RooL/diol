<?
if(!function_exists('vd'))
{
	function vd($a)
	{
		echo '<pre>';
		var_dump($a);
		echo '</pre>';
	}
		
}



function strPrepare($a)
{
	//return htmlspecialchars(trim($a));
	return mysql_real_escape_string(trim($a));
}



function specialCharizeArray(&$arr)
{
	//vd(is_array($arr));
	foreach($arr as $key=>$val)
	{
		if(!is_array($val))
		{
			//vd(htmlspecialchars($arr[$key]));
			$arr[$key] = htmlspecialchars($val, ENT_QUOTES);
			//vd(htmlspecialchars($arr[$key]));
		}
		else 
		{
			//vd($arr[$key]);
			//vd(specialCharizeArray($val));
			//vd($key);
			//echo '<hr>';
			$arr[$key] = specialCharizeArray($val);
		}
	}
	return $arr;
	//echo '<hr><hr><hr>';
}



function translit($string) {

    $converter = array(
        'а' => 'a',   'б' => 'b',   'в' => 'v',
        'г' => 'g',   'д' => 'd',   'е' => 'e',
        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
        'и' => 'i',   'й' => 'y',   'к' => 'k',
        'л' => 'l',   'м' => 'm',   'н' => 'n',
        'о' => 'o',   'п' => 'p',   'р' => 'r',
        'с' => 's',   'т' => 't',   'у' => 'u',
        'ф' => 'f',   'х' => 'h',   'ц' => 'c',
        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
        'ь' => 'y',  'ы' => 'y',   'ъ' => 'y',
        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',
        
        'А' => 'A',   'Б' => 'B',   'В' => 'V',
        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
        'И' => 'I',   'Й' => 'Y',   'К' => 'K',
        'Л' => 'L',   'М' => 'M',   'Н' => 'N',
        'О' => 'O',   'П' => 'P',   'Р' => 'R',
        'С' => 'S',   'Т' => 'T',   'У' => 'U',
        'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
        'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
    );

    return strtr($string, $converter);

}



function str2url($str) {

    // переводим в транслит
    $str = translit($str);

    // в нижний регистр
    $str = strtolower($str);

    // заменям все ненужное нам на "-"
    $str = preg_replace('~[^-a-z0-9_\']+~u', '-', $str);

    // удаляем начальные и конечные '-'
    $str = trim($str, "-");

    return $str;
}






function drawPages($totalCount, $currentPage, $elPP, $onclick, $class, $sym)
{
	//vd($onclick);
	$class = $class ? $class : 'pages';
	//vd($currentPage);
	$sym['beginning'] = $sym['beginning'] ? $sym['beginning'] : 'начало <<';
	$sym['prev'] = $sym['prev'] ? $sym['prev'] : '&larr; предыдущие';
	
	$sym['end'] = $sym['end'] ? $sym['end'] : '>> конец';
	$sym['next'] = $sym['next'] ? $sym['next'] : 'следующие &rarr;';
	
	
	
	
	
	$totalPages=ceil($totalCount/$elPP);
	$str = '';
	if($totalPages > 1)
	{
		$str.='
		<div class="'.$class.'">';
		
		if($currentPage>0) 
        {
        	$str .= '
        	<a class="item beginning"  title="начало"  href="#page-1" onclick="'.getOnclick(1, $onclick).'; return false; ">'.$sym['beginning'].'</a>
        	<a class="item" title="предыдущая" href="#page-'.$currentPage.'" onclick="'.getOnclick( ($currentPage), $onclick).'; return false; ">'.$sym['prev'].'</a>';
        }
        else
        {
        	$str.='
        	<div class="item inactive beginning">'.$sym['beginning'].'</div>
        	<div class="item inactive">'.$sym['prev'].'</div>';
        }
        
		$index = $currentPage>=6 ? ($currentPage+1<$totalPages-5 ? $currentPage-5 : ($totalPages>11 ? $totalPages-11 : 0)) : 0;
		for($i=1; $i<= ($totalPages<11 ? $totalPages : 11); $i++)
		{
			$index++;
			if($index>$totalPages) break;
			if($index == $currentPage+1)
			{
				$str .= ' <div class="item current">'.$index.'</div> ';
			}
			else
			{
				$str .= '<a class="item" href="#page-'.$index.'" onclick="'.getOnclick($index, $onclick).'; return false; ">'.$index.'</a> ';
			}
		}
        
		if($currentPage+1 < $totalPages) 
        {
        	$str .= '
        	<a class="item" title="следующая"  href="#page-'.($currentPage+2).'" onclick="'.getOnclick( ($currentPage+2), $onclick).'; return false; ">'.$sym['next'].'</a>
        	<a class="item end" title="конец"  href="#page-'.$totalPages.'" onclick="'.getOnclick($totalPages, $onclick).'; return false; ">'.$sym['end'].'</a>';
        }
        else
        {
        	$str.='
        	<div class="item inactive">'.$sym['next'].'</div>
        	<div class="item inactive end">'.$sym['end'].'</div>';
        }
        
		
		$str.='
		</div>';
		$str.='
		<div style="clear: both; "></div>';
	}
	

	return  $str;
}







function getOnclick($page, $onclick)
{
	//vd($page);
	$str=str_replace("###", $page, $onclick);
	
	return $str;
}







?>