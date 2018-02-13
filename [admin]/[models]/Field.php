<?php
class Field2
{
	var 	  $id
			, $pid
			, $active
			, $code
			, $name
			, $type
			, $ownerType
			, $size
			, $options
			, $withTime
			, $multiple
			, $required
			, $dislayed
			, $marked
			, $idx
			
			, $essence;
			
			
	const TBL = 'slonne__fields';
	
	
	static $types=array(
							
						'smalltext'=>'Текстовое поле (varchar)',
						'text'=>'Textarea (text)',
						'html'=>'HTML редактор (text)',
						'html_long'=>'HTML редактор(longtext)',
						'num'=>'Число (int)',
						'date'=>'Дата',
						'select'=>'Выпадающий список',
						'checkbox'=>'Галочка',
						'pic'=>'Картинка',
						'file'=>'Файл',
						
					);
	
	//static $validPicTypes = array('jpg', 'jpeg', 'gif', 'png');			
	//static $validFileTypes = array('pdf', 'zip', 'gzip', 'rar', '7z');	

	static $allowedMediaTypes = array(
					'pic'=>array('jpg', 'jpeg', 'gif', 'png'),
					'file'=>array('pdf', 'djvu', 'zip', 'gz', 'gzip', 'rar', '7z',),
	);
	
					
	#	дефолтовые значения для длин и размеров полей
	static $defaultFieldsPresets=array(
									'smalltext'=>array('size'=>"240", ),
									'text'=>array('width'=>"250", "height"=>"60", ),
	
									//'select'=>array('options'=>"выбор 1\nвыбор 2\nвыбор 3\n", ),
								);				
	
	
	
	function getList($pid, $type)
	{
		if(!$pid = intval($pid))
			return;
		if( !($type==Entity2::TYPE_ELEMENTS || $type==Entity2::TYPE_BLOCKS) )
			return;
		//echo "!";
		//vd($pid);
		$sql="SELECT * FROM `".self::TBL."` WHERE pid=".$pid." AND ownerType='".strPrepare($type)."' ORDER BY idx";
		//vd($sql);
		$qr = DB::query($sql);
		echo mysql_error();
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
			$ret[] = self::init($next);
		
		return $ret;
	}
	
	
	
	
	
	function init($arr)
	{
		$field = new self();
		
		$field->id = $arr['id'];
		$field->pid = $arr['pid'];
		$field->code = $arr['code'];
		$field->name = $arr['name'];
		$field->type = $arr['type'];
		$field->ownerType = $arr['ownerType'];
		$field->size = $arr['size'];
		$field->options = $arr['options'];
		$field->withTime = $arr['withTime'];
		$field->multiple = $arr['multiple'];
		$field->required = $arr['required'];
		$field->displayed = $arr['displayed'];
		$field->marked = $arr['marked'];
		$field->idx = $arr['idx'];
		
		#	для select
		if($field->type == 'select')
		{
			$tmp = explode("\r\n", $field->options);
			foreach($tmp as $val)
				if($val = trim($val))
					$tmp2[] = $val;
			$field->options = $tmp2;
		}
		
        return $field;
	}
	
	
	
	function get($id)
	{
		//vd($id);
		if(gettype($id) == 'array')
		{
			
			$ok = true;
			$clause = $id['clause'];
		}
		else
		{
			
			if($id == intval($id))
			{
				$clause = " AND id = ".intval($id);
				$ok = true;
			}
		}
		
		if($ok)
		{
			$sql = "SELECT * FROM `".self::TBL."` WHERE 1 ".$clause."";
			//vd($sql);
			$qr=DB::query($sql);
			echo mysql_error();
			if($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				return self::init($next);
		}
	}
	
	
	function getByCode($code, $pid)
	{
		if($code = strPrepare($code))
		{
			$sql = "SELECT * FROM `".self::TBL."` WHERE code = '".$code."' ".($pid=intval($pid) ? "AND pid='".$pid."'" : "" )."";
			$qr=DB::query($sql);
			echo mysql_error();
			if($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				return self::init($next);
		}
	}
	
	

	
	function add($field)
	{
		if($field)
		{
			$sql = "
			INSERT INTO `".self::TBL."` 
			SET 
			
			  pid = '".$field->pid."'
			, code = '".$field->code."'
			, name = '".$field->name."'
			, type = '".$field->type."'
			, ownerType = '".$field->ownerType."'
			, size = '".$field->size."'
			, options = '".$field->options."'
			, withTime = '".$field->withTime."'
			, multiple = '".$field->multiple."'
			, required = '".$field->required."'
			, displayed = '".$field->displayed."'
			, marked = '".$field->marked."'
			, idx = '".$field->idx."'
			
			";
			//vd($sql);
			$qr=DB::query($sql);
			echo mysql_error();
			return mysql_insert_id();
		}
	}
	
	
	
	function update($field)
	{
		if($field)
		{
			$sql = "
			UPDATE `".self::TBL."` 
			SET 

			  pid = '".$field->pid."'
			, code = '".$field->code."'
			, name = '".$field->name."'
			, type = '".$field->type."'
			, ownerType = '".$field->ownerType."'
			, size = '".$field->size."'
			, options = '".$field->options."'
			, withTime = '".$field->withTime."'
			, multiple = '".$field->multiple."'
			, required = '".$field->required."'
			, displayed = '".$field->displayed."'
			, marked = '".$field->marked."'
			, idx = '".$field->idx."'
			
			WHERE id=".intval($field->id)."
			";
			//vd($sql);
			$qr=DB::query($sql);
			echo mysql_error();
		}
	}
	
	
	
	
	
	function delete($id)
	{
		global $_CONFIG;
		
		if($id = intval($id))
		{
			$field = Field2::get($id);
			//vd($field);
			if($field = Field2::get($id))
			{
				$sql = "
				DELETE FROM `".self::TBL."` WHERE id=".$id;
				//vd($sql);
				DB::query($sql);
				echo mysql_error();
				
				$field->initEssence();
				//vd($field);
				
				Field2::deleteColumn($field);
			}
		}
	}
	
	
	
	
	
	function deleteColumn($field)
	{
		global $_CONFIG;
		
		if($field)
		{
			if(!$field->essence)
				$field->initEssence();
				
			foreach($_CONFIG['LANGS'] as $lang=>$val)
			{
				$tbl = Essence2::getTblName($field->essence->code, $field->ownerType, $lang);
				$sql="ALTER TABLE `".$tbl."` DROP `".$field->code."` ";
				DB::query($sql);
				echo mysql_error();
			}
		}
	}
	
	
	
	
	
	function initEssence()
	{
		$this->essence = Essence2::get($this->pid);
	}
	
	
	
	
	
	function addFieldToTable($essence, $field)
	{
		global $_CONFIG;
		
		if($essence && $field)
		{
			foreach($_CONFIG['LANGS'] as $lang=>$val)
			{
				$tbl = Essence2::getTblName($essence->code, $field->ownerType, $lang);
				
				$sql="ALTER TABLE `".$tbl."` ADD `".$field->code."` ";
				
				switch($field->type)
				{
					case 'smalltext': 
					case 'pic': 
					case 'file': 
						$sql.=" VARCHAR( 255 ) NOT NULL ";
						break;
					
					case 'text':
					case 'html':
					case 'select':
						$sql.=" TEXT NOT NULL ";
						break;
						
					case 'html_long':
						$sql.=" LONGTEXT NOT NULL ";
						break;

					case 'num':
						$sql.="INT( 11 ) NOT NULL ";
						break;
						
					case 'date':
						$sql.="DATETIME NULL ";
						break;
						
					case 'checkbox':
						$sql.="INT( 1 ) NOT NULL ";
						break;
				}
				
				vd($sql);
				DB::query($sql);
				echo mysql_error();
			}
		}
	}
	
	
	
	
	
	
	function setIdx($id, $val)
	{
		if($id=intval($id))
		{
			$sql = "UPDATE `".self::TBL."` SET idx='".intval($val)."' WHERE id=".$id;
			DB::query($sql);
			echo mysql_error();
		}
	}
	
	
	
	
	
	
	
	function displayValueInView($value, $lang/*для тайтлов картинок*/)
	{
		//$str = $this->attrs['type'].' = '.$value;
		
		switch($this->type)
		{
			default:
				$str = $value;
				break;
				
				
			case 'checkbox':
					$str = $value ? 'ДА' : 'НЕТ';
				break;	
				
				
			case 'pic':
				if(!$this->multiple)	#	ОДНА
				{
					//vd($value);
					if($value)
					{
						$str = '
						<div class="pic-wrap single" >
							<div class="src">'.$value.'</div>		
							<a href="/'.UPLOAD_IMAGES_REL_DIR.''.$value.'" onclick="return hs.expand(this)" class="highslide ">
								<img src="'.Media::img($value.'&height=220').'">
							</a>
						</div>';
					}
					else 	
						$str.='нет';
				} 
				else 	#	МАЛТИПЛ
				{
					//vd($value);
					//vd($value);
					foreach($value as $key=>$m)
					{
						$str.='
						<div class="pic-wrap" id="pic-wrap-'.$m->id.'">
							<div class="id">id: <b>'.$m->id.'</b></div>
							<div class="src">'.$m->path.'</div>		
							<a href="/'.UPLOAD_IMAGES_REL_DIR.''.$m->path.'" onclick="return hs.expand(this)" class="highslide ">
								<img src="'.Media::img($m->path.'&height=100').'" style="">
							</a>
							<div class="title">'.$m->title[$lang].'</div>
						</div>';
					}
					$str.='
					<div class="clear"></div>';
				}
				break;
				
		}
		
		return $str;
	}
	
	
	
	
	
	
	
	function displayValueForList( $value)
	{
		//$str = $this->attrs['type'].' = '.$value;
		
		switch($this->type)
		{
			default:
				$str = $value;
				break;
				
				
			case 'checkbox':
					$str = $value ? 'ДА' : 'НЕТ';
				break;	
				
				
			case 'pic':
				if(!$this->multiple)
				{
					if($value)
					{
						$str = '
						<div class="pic-wrap single">
							<a href="/'.UPLOAD_IMAGES_REL_DIR.''.$value.'" onclick="return hs.expand(this)" class="highslide ">
								<img src="'.Media::img($value.'&width=70').'" style="">
							</a>
						</div>';
					}
					else 	
						$str.='НЕТ';
				} 
				else 
				{
					//vd($value);
					if(count($value) && $value)
					{
						$str.='
						<span>Картинок: <b>'.count($value).'</b></span><br>';
						
						$i=0; 
						foreach($value as $key=>$pic)
						{
							if($i >= 3)
								break;
								
							$str.='
							<div >	
								<a href="/'.UPLOAD_IMAGES_REL_DIR.$pic->path.'" onclick="return hs.expand(this)" class="highslide ">
									<img src="'.Media::img($pic->path.'&height=35').'" style="">
								</a>
							</div>';
							
							$i++;
						}
						//vd($i);
						$str.='
						<div class="clear"></div>';
						if(count($value) > $i)
							$str.='<div>...</div>';
					}
					else 	
						$str.='НЕТ';
				}
				break;
				
		}
		
		return $str;
	}
	
	
	
	
	
	
	
	function drawInput($value, $lang/*для тайтлов картинок*/)
	{
		//vd($value);
		switch($this->type)
		{
			
			default:
				$str.='<span style=" color: #B34949; font-size: 13px; font-weight: normal; text-align: right; text-shadow: 0 1px 2px #CCCCCC;">Oops! o_O Problems here! (field: <b>'.$this->code.'</b>, type:<b>'.$this->type.'</b> : HTML_NOT_PROVIDED)';
				break;
			
				
			
			case 'smalltext':
			case 'num':
					$str.='
					<input type="text" value="'.htmlspecialchars($value).'" name="'.$this->code.'" id="'.$this->code.'" style="width: '.($this->size ? $this->size : Field2::$defaultFieldsPresets['smalltext']).'px; ">';
				break;
				

				
			case 'text': 
					if($this->size)
					{
						list($w, $h)=explode("x", $this->size);
						if($w)	$styleStr.=' width: '.$w.'px; ';
						if($w)	$styleStr.=' height: '.$h.'px; ';
					}
					$str.='
					<textarea name="'.$this->code.'" id="'.$this->code.'" style="'.$styleStr.'">'.str_replace("<br />", "\n", $value).'</textarea>';
				break;

				
				
				
				
			case 'html': 
			case 'html_long': 
					if($this->size)
					{
						list($w, $h)=explode("x", $this->size);
						if($w)	$styleStr.=' width: '.$w.'px; ';
						if($w)	$styleStr.=' height: '.$h.'px; ';
					}
					
					/*$str.='<div><input type="hidden" id="'.$this->code.'" name="'.$this->code.'" value="'.htmlspecialchars(stripslashes($value)).'"><input type="hidden" id="FCKeditor1___Config" value=""><iframe id="FCKeditor1___Frame" src="/'.INCLUDE_DIR.'/FCKeditor/editor/fckeditor.html?InstanceName='.$this->code.'&Toolbar=Slonne" style="min-width: 640px;" width="100%" height="400px" frameborder="no" scrolling="no"></iframe></div>';*/
					$str.='
							<textarea id="'.$this->code.'" name="'.$this->code.'">
									'.(stripslashes($value)).'
							</textarea>
							<script>
								CKEDITOR.replace( "'.$this->code.'", {
										height: "300px" 
								} );
										
							</script>';
					
				break;
				
				
				
				
				
				
				
				
				
			case 'select':
				//vd($fieldInfo);
					//vd($this);
					$str.='
					<select name="'.$this->code.'" id="'.$this->code.'">
						<option value="">-выберите-</option>';
					foreach($this->options as $key=>$val)
					{
						$str.='
						<option value="'.$val.'" '.($val==$value?' selected="selected" ':'').'>'.$val.'</option>';
					}
					$str.='
					</select>';
				break;
				

				
				
				
				
			case "date":
					$str.='
					<input type="text" name="'.$this->code.'" id="'.$this->code.'" value="'.$value.'"  style="width:70px"> <img id="'.$this->code.'-calendar-btn" src="/js/calendar/calendar.jpg" style="border:0px;">
					
					<script>
						Calendar.setup({
						    inputField     :    "'.$this->code.'",      // id of the input field
						    ifFormat       :    "%Y-%m-%d",       // format of the input field
						    showsTime      :    false,            // will display a time selector
						    button         :    "'.$this->code.'-calendar-btn",   // trigger for the calendar (button ID)
						    singleClick    :    true,           // double-click mode
						    step           :    1                // show all years in drop-down boxes (instead of every other year as default)
						});
				</script>';
				break;	
				
				
				
				
				
				
			case 'checkbox':
					$str.='
					<input type="checkbox" '.($value?' checked="checked" ':'').' name="'.$this->code.'" id="'.$this->code.'">';
				break;
				
				
				
				
			case "pic":
				if(!$this->multiple)
				{
					//vd($value);
					if($value)
					{
						$str.='
						
						
						<div class="pic-wrap single" id="pic-'.$this->code.'-div" >
							<!--<div class="src">'.$value.'</div>-->		
							<a href="/'.UPLOAD_IMAGES_REL_DIR.''.$value.'" onclick="return hs.expand(this)" class="highslide ">
								<img src="'.Media::img($value.'&height=120').'">
							</a>
						</div>
						<div class="clear"></div>';
					}
				}
				else
				{
					foreach($value as $key=>$m)
					{
						$str.='
						<div class="pic-wrap" id="multipic-'.$m->id.'" >	
							<img class="delete" src="/'.ADMIN_DIR.'/img/delete.png" onclick="Slonne.Entities.deleteMedia('.$m->id.')" alt="удалить" title="удалить">
							<div class="delete-loading">загрузка..</div>
							<div class="id">id: <b>'.$m->id.'</b></div>
							<div class="src">'.$m->path.'</div>
							<a href="/'.UPLOAD_IMAGES_REL_DIR.''.$m->path.'" onclick="return hs.expand(this)" class="highslide ">
								<img src="'.Media::img($m->path.'&height=100').'" >
							</a>
							<textarea name="media['.$m->id.']" >'.$m->title[$lang].'</textarea>
						</div>
						';
					}
				}
				$str.='
				<div class="clear"></div>
				<input type="file" name="'.$this->code.'[]" id="'.$this->code.'" '.($this->multiple ? 'multiple' : '').' >';
				break;
				
				
				
			case 'file':
				$tmp = explode('/', $value);
				$fileName = $tmp[count($tmp)-1];
				if($value)
				{
					$str.=''.$fileName.'';
				}
				$str.='
				<div class="clear"></div>
				<input type="file" name="'.$this->code.'[]" id="'.$this->code.'" '.($this->multiple ? 'multiple' : '').' >';
				break;
		}
		
		return $str;
	}
	
	
	
	
	
	
	function drawTreeSelect($essence, $pid/*чьих детей отображать*/, $self_id, $idToBeSelected, $level=0, $lang )
	{
		global $_CONFIG;
		
		$lang = $_CONFIG['langs'][$lang] ? $lang : $_CONFIG['default_lang']->code;
		$pid=intval($pid);
		$level=intval($level);
		$type = $essence->jointFields ? Entity2::TYPE_ELEMENTS : Entity2::TYPE_BLOCKS;
		
		$e = Entity2::get($essence->code, $pid, $type, $lang);	
		if($e->id == $self_id && $self_id)
			return $ret;
		
		if($e->id )
		{
			$ret.='
				<option '.($idToBeSelected==$e->id?' selected="selected"  ':'').' value="'.$e->id.'">';
				for($i=1; $i<$level; $i++)
				{
					$ret.='------';
				}
				$ret.='| ('.$e->id.') '.$e->attrs['name'];
				$ret.='
				</option>';
		}
		
		#	достаём детей
		$params = array(
						'essenceCode'=>$essence->code,
						'pid'=>$pid,
						'limit'=>'',
						'type'=>$type, 
						'order'=>'', 
						'lang'=>$lang, 
						'additionalClauses'=>'and 1',
						'activeOnly'=>false,
					);
		$children = Entity2::getList($params);
		foreach($children as $key=>$child)
		{
			$ret.=self::drawTreeSelect($essence, $child->id, $self_id,  $idToBeSelected,  ($level+1), $lang);
		} 
	
		return $ret;
	}
	
	
	
	
	
	
	
	
	function validateValue($value)
	{
		static $i;
		$i++;
		
		$problem = NULL;
		
		switch($this->type)
		{
			case "num":
				$value=trim($value);
				
				if(!$value)
				{	
					if($this->required)
						$problem = Field2::setProblem($this->code, 'Заполните поле <b>'.$this->name.'</b>');
				}
				elseif(!is_numeric($value))		#	проверяем в любом случае, даже если не required (если хоть что то введено)
					$problem = Field2::setProblem($this->code, 'Поле <b>'.$this->name.'</b> должно быть числом!');
			break;
				
			
			case "smalltext":
			case "text":
			case "html":
			case "html_long":
				$value=trim($value);
				if($this->required && !$value )
					$problem = Field2::setProblem($this->code, 'Заполните поле <b>'.$this->name.'</b>');
			break;
				
			
			case "select":
					if($this->required && ( $this->multiple && !count($value) || (!$this->multiple && !$value)  )   )
						$problem = Field2::setProblem($this->code, 'Не выбрано значение в поле <b>'.$this->name.'</b>');
			break;
			
			
			
			case "checkbox":
				#	пусто
			break;
			
			
				
			case "date":
				$value = trim($value);
				
				if($value)
				{
					list($year, $month, $day)=explode('-', $value);
					if($this->required && (strlen($year)!=4 || strlen($month)!=2 || strlen($day)!=2) )
						$problem = Field2::setProblem($this->code, 'Дата должна иметь формат <b>YYYY-MM-DD</b> в поле <b>'.$this->name.'</b>');

					elseif($this->required && (!is_numeric($year) || !is_numeric($month) || !is_numeric($day)) )
						$problem = Field2::setProblem($this->code, 'Недопустимые символы в поле <b>'.$this->name.'</b>');	
						
					elseif($this->required && ($month > 12 || $day > 31) )
						$problem = Field2::setProblem($this->code, 'Некорректная дата в поле <b>'.$this->name.'</b>');
				}
				elseif($this->required)
					$problem = Field2::setProblem($this->code, 'Заполните поле <b>'.$this->name.'</b>');
			break;

			

			case "pic":		#	здесь проверка только на состояние поля! чтоб было не пустое, а все варнинги с файлами - отдельно в контроллере
			case "file":
				
				if(!$this->multiple)	#	одна картинка
				{				
					if(  ($this->required && !$value)  )
					{
						if(!$_FILES[$this->code])
							$problem = Field2::setProblem($this->code, 'Выберите картинку для поля <b>'.$this->name.'</b>');
						
					}
					
					if(!$problem && $_FILES[$this->code])
					{
						if($picValidateProblem = self::validateMedia($_FILES[$this->code][0], $this))
							$problem = Field2::setProblem($this->code, 'Ошибка в поле <b>'.$this->name.'</b> - '.$picValidateProblem);	
					}
				}
				else	#	много картинок
				{
					if($this->required && !$value)
					{
						if(!$_FILES[$this->code])
							$problem = Field2::setProblem($this->code, 'Выберите картинку для поля <b>'.$this->name.'</b>');
						else
						{
							foreach($_FILES[$this->code] as $filesPic)
								if(!$picValidateProblem = self::validateMedia($filesPic, $this))
									$ok = true;
							
							if(!$ok)
								$problem = Field2::setProblem($this->code, 'Выберите хотя бы одну картинку для поля <b>'.$this->name.'</b> верного формата');
						}
					}
				}
			break;
			
				
			
		}
		
		return $problem;
	}
	
	
	
	
	function setProblem($fieldCode, $err)
	{
		return array('field'=>$fieldCode, 'problem'=>$err);
	}
	
	
	
	
	function fixFILES()
	{
		//vd($_FILES);
		$pics = NULL;
		foreach($_FILES as $fieldCode=>$val)
		{
			$pics[$fieldCode] = NULL;
			foreach($val['name'] as $num=>$pic)
			{
				if($_FILES[$fieldCode]['name'][0])
				{
					$tmp['name'] = $_FILES[$fieldCode]['name'][$num];
					$tmp['type'] = $_FILES[$fieldCode]['type'][$num];
					$tmp['tmp_name'] = $_FILES[$fieldCode]['tmp_name'][$num];
					$tmp['error'] = $_FILES[$fieldCode]['error'][$num];
					$tmp['size'] = $_FILES[$fieldCode]['size'][$num];
					
					$pics[$fieldCode][] = $tmp;
				}
			}
		}

		$_FILES = $pics;
	}
	
	
	
	
	
	function validateMedia($file, $field)
	{
		
		#	картинка не пришла	
		if(!$file['size'])
		{
			if($field->type=='pic')
				return 'Картинка не загружена!';
			if($field->type=='file')
				return 'Файл не загружен!';
		}
		
		$allowedExts = self::$allowedMediaTypes[$field->type];
		
		
		#	массив допустимых типов	
		foreach($allowedExts as $key=>$val)
		{
			if($field->type == 'pic')
				$arr[]='image/'.$val;
			if($field->type == 'file')
				$arr[]='application/'.$val;	
		}
		#	недопустимый тип	
		if(!in_array($file['type'], $arr) )
			return 'Недопустимый формат <b>'.$file['type'].'</b>! Допустимые: <b>'.join('</b>, <b>', $allowedExts).'</b>';
			
		
	}
	
	
	
	
	
	
	function generateMediaName()
	{
		$a = substr(time(), 7).'_'.uniqid();
		return $a;
	}
	
	
	
	
	
	
	
	function saveUploadedMediaItem($file, $newFileName, $type)
	{
		$problem = '';

		if(!trim($newFileName))
			$newFileName = self::generateMediaName();

		if($file)
		{
			$dot=strrpos($file['name'], '.');
			$name=(substr($file['name'], 0, $dot));
			$ext=strtolower(substr($file['name'],  $dot+1));
			
			$tmpFile = $file["tmp_name"];
			if(is_uploaded_file($tmpFile))
			{
				$allowedExts = array();
				switch($type)
				{
					case 'pic': 
						break;
				}
				$destDir = ROOT.'/'.UPLOAD_IMAGES_REL_DIR.''.$this->essence->code.'/';
				mkdir($destDir);
				
				$destFile=$destDir.$newFileName.'.'.$ext;
				
				if( move_uploaded_file($tmpFile, $destFile))	
				{
					#	всё ок, проблем не возвращаем
				}
				else 
					$problem = self::setProblem($this->code, 'Не удалось загрузить файл для поля <b>'.$this->name.'</b>');
			}
			else
				$problem = self::setProblem($this->code, 'Файл для поля <b>'.$this->name.'</b> не загружен..');
		}
		else
			$problem = self::setProblem($this->code, 'Не удалось загрузить файл для поля <b>'.$this->name.'</b>');
			
		$result['problem'] = $problem;
		$result['newFileName'] = $newFileName.'.'.$ext;
		 
		return $result;
	}
	
	
	
	
	
	
	
	
} 
?>