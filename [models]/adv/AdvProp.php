<?php 
class AdvProp{
	
	const TBL = 'adv__props';
	
	
	
	// catId выпилить  потом надо 
	public $id;
	public $statusId;
	public $status;
	public $name;
	public  $nameOnSite;
	public $descr;
	public $code;
	public $required;
	public $global;
	public $multiple;
	public $dateCreated;
	public $minValue;
	public $maxValue;
	public $size;
	public $unitId;
	
	public $options;
	public $tableColumns;
	public $value;
		
		
	static $types=array(
							
						'smalltext'=>'Текстовое поле (varchar)',
						'text'=>'Textarea (text)',
						'html'=>'HTML редактор (text)',
						//'html_long'=>'HTML редактор(longtext)',
						'num'=>'Число (int)',
						//'date'=>'Дата',
						'select'=>'Выпадающий список',
						'checkbox'=>'Галочка',
						//'pic'=>'Картинка',
						
					);
	
	static $validPicTypes = array('jpg', 'jpeg', 'gif', 'png');				
	
					
	#	дефолтовые значения для длин и размеров полей
	static $defaultFieldsPresets=array(
									'smalltext'=>array('size'=>"240", ),
									'text'=>array('width'=>"250", "height"=>"60", ),
	
									//'select'=>array('options'=>"выбор 1\nвыбор 2\nвыбор 3\n", ),
								);	
	
	
	function getList($params, $status)
	{
		if(gettype($params) != 'array' )
			$params = array('classId'=>$params, 'status'=>$status);
		
		$sql = "SELECT * FROM `".mysql_real_escape_string(self::TBL)."` AS props  ";
		
		if($params['classId'])
			$sql.=" INNER JOIN  `".mysql_real_escape_string(AdvClassPropRelation::TBL)."` AS rel ON rel.propId=props.id";
		
		$sql.=" WHERE 1  ";
		if($params['classId'])
			$sql.=" AND rel.classId='".intval($params['classId'])."' ";
		/*$sql.="".(intval($params['classId']) ? " AND props.id IN(SELECT propId FROM `".AdvClassPropRelation::TBL."` AS relTbl WHERE classId=".intval($params['classId']).") " : "")."";*/
		$sql.="
		".($params['status'] ? " AND status='".intval($params['status']->num)."' " : "")."";
		
		if($params['classId'])
			$sql.=" ORDER BY idx ";	
		$sql.="".strPrepare($params['limit'])."";
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
		{
			$res[$next['id']] = self::init($next);
		}
		
		return $res;
	}
	
	
	
	function getListInnerSql($params)
	{
		
		$sql.="
		
		";
	
		return $sql;
	}
	
	
	
	function init($arr)
	{
		$m = new self();
		
		$m->id = $arr['id'];
		$m->statusId = $arr['status'];
		$m->status = Status::num($m->statusId);
		$m->name = $arr['name'];
		$m->nameOnSite = $arr['nameOnSite'];
		$m->code = $arr['code'];
		$m->dateCreated = $arr['dateCreated'];
		$m->idx = $arr['idx'];
		$m->required = $arr['required'];
		$m->global = $arr['global'];
		$m->type = $arr['type'];
		$m->multiple = $arr['multiple'];
		$m->minValue = $arr['minValue'];
		$m->maxValue = $arr['maxValue'];
		$m->size = $arr['size'];
		$m->unitId = $arr['unitId'];
		
        return $m;
	}
	
	
	

	
	function get($id, $status)
	{
		if($id = intval($id))
		{
			$sql = "SELECT * FROM `".self::TBL."` WHERE id=".$id." ".($status ? " AND status='".intval($status->num)."' " : "")."";
			//vd($sql);
			$qr=DB::query($sql);
			echo mysql_error();
			if($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				return self::init($next);
		}
	}
	
	
	
	function getByName($name, $status)
	{
		if($name = trim($name))
		{
			$sql = "SELECT * FROM `".self::TBL."` WHERE name='".strPrepare($name)."' ".($status ? " AND status='".intval($status->num)."' " : "")."";
			//vd($sql);
			$qr=DB::query($sql);
			echo mysql_error();
			if($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				return self::init($next);
		}
	}
	
	
	
	function insert()
	{
		//$this->idx = 9999;
		$sql = "
		INSERT INTO `".self::TBL."` 
		SET dateCreated=NOW(),
		".$this->alterSql()."
		
		";
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		return mysql_insert_id();
	}
	
	
	
	function update()
	{
		$sql = "
		UPDATE `".self::TBL."` 
		SET 
		".$this->alterSql()."
		WHERE id=".intval($this->id)."
		";
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
	}
	
	
	
	
	function alterSql()
	{
		$str.="
		  `status` = '".intval($this->status->num)."'
		, `required`='".($this->required ? 1 : 0)."'
		, `global`='".($this->global ? 1 : 0)."'
		, `multiple`='".($this->multiple ? 1 : 0)."'
		/*, idx=".intval($this->idx)."*/
		, `name`='".strPrepare($this->name)."'
		, `nameOnSite`='".strPrepare($this->nameOnSite)."'
		, `code`='".strPrepare($this->code)."'
		, `type`='".strPrepare($this->type)."'
		, `minValue`='".strPrepare($this->minValue)."'
		, `maxValue`='".strPrepare($this->maxValue)."'
		, `size`='".strPrepare($this->size)."'
		, `unitId`='".intval($this->unitId)."'
		";
		
		return $str;
	}
	
	
	
	
	function delete($id)
	{
		if($id = intval($id))
		{
			$sql = "
			DELETE FROM `".self::TBL."` WHERE id=".$id;
			DB::query($sql);
			echo mysql_error(); 
		}
	}
	
	
	
	
	
	
	/*
	function setIdx($id, $val)
	{
		if($id=intval($id))
		{
			$sql = "UPDATE `".self::TBL."` SET idx='".intval($val)."' WHERE id=".$id;
			DB::query($sql);
			echo mysql_error();
		}
	}*/
	
	
	
	
	
	function validate()
	{
		//vd($this);
		echo "!";
		$problems = null;
		
		if(!$this->name) $problems[] = Slonne::setError('name', 'Введите название!');
		if(!$this->nameOnSite) $problems[] = Slonne::setError('nameOnSite', 'Введите название на сайте!');
		
		if(!$this->id)
		{
			if(!$this->code) $problems[] = Slonne::setError('code', 'Введите код!');
			$tmp=preg_match('/^[a-zA-Z][a-zA-Z0-9_]+$/', $this->code);
			if(!$tmp)	
				$problems[]=Slonne::setError('code', 'Неверный формат кода! Только латинница, без пробелов');
			
			if(!$this->type) $problems[] = Slonne::setError('type', 'Выберите тип!');
			
			#	проверка на сществование подобных полей
			if(!count($problems))
			{
				if(($byName = AdvProp::getByName($this->name) && $byName->id != $this->id))
					$problems[] = Slonne::setError('name', 'Поле с таким названием уже есть!');
				/*elseif(($byCode = AdvProp::get(array('clause'=>" AND code='".$this->code."' "))) && $byCode->id != $this->id)
					$problems[] = Slonne::setError('code', 'Поле с таким кодом уже есть!');
				*/
			}
		}
		
		
		return $problems;
	}
	
	
	
	
	
	function initOptions($status)
	{
		if($this->type == 'select')
		{
			$this->options = AdvCatSelectOption::getList($propId=$this->id, $status);
			//vd($this->options);
			$isFloatVals = true;

			foreach ($this->options as $opt)
			{
				if(strlen($opt->value) !== strlen(floatval($opt->value)))
				{
					$isFloatVals = false;
					break;
				}
				else
					$opt->value = floatval($opt->value);
			}

			if($isFloatVals) 	# 	надо сортировать по флоутвалу
			{
				/*function cmp($a, $b)
				{
					return ($a->value < $b->value) ? -1 : 1;
				}

				usort($this->options, "cmp");*/
			}
		}
	}
	
	
	
	function initTableColumns($status)
	{
		//vd($activeOnly);
		if($this->type == 'table')
			$this->tableColumns = AdvPropTableColumn::getList($propId=$this->id, $status);
	}
	
	
	
	function getPropsOfClass($classId, $activeOnly = false)
	{
		if($classId = intval($classId))
		{
			$sql = "
			SELECT * FROM `".mysql_real_escape_string(self::TBL)."` AS props 
			WHERE 1 
			AND props.id IN(SELECT propId FROM `".ClassPropRelation::TBL."` WHERE classId=".$classId.")
			".($activeOnly ? " AND active=1 " : "")."";
			$qr=DB::query($sql);
			echo mysql_error();
			while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
			{
				$res[$next['id']] = self::init($next);
			}
		}
		return $res;
	}
	
	
	
	
	
function backendInput($value, $lang/*для тайтлов картинок*/)
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
					<input type="text" value="'.htmlspecialchars($value).'" name="'.$this->code.'" id="'.$this->id.'_'.$this->code.'" style="width: '.($this->size ? $this->size : AdvProp::$defaultFieldsPresets['smalltext']).'px; ">';
				break;
				

				
			case 'text': 
					if($this->size)
					{
						list($w, $h)=explode("x", $this->size);
						if($w)	$styleStr.=' width: '.$w.'px; ';
						if($w)	$styleStr.=' height: '.$h.'px; ';
					}
					$str.='
					<textarea name="'.$this->code.'" id="'.$this->id.'_'.$this->code.'" style="'.$styleStr.'">'.str_replace("<br />", "\n", $value).'</textarea>';
				break;

				
				
				
				
			case 'html': 
			case 'html_long': 
					if($this->size)
					{
						list($w, $h)=explode("x", $this->size);
						if($w)	$styleStr.=' width: '.$w.'px; ';
						if($w)	$styleStr.=' height: '.$h.'px; ';
					}
					
					$str.='<div><input type="hidden" name="'.$this->code.'" id="'.$this->id.'_'.$this->code.'" value="'.htmlspecialchars(stripslashes($value)).'"><input type="hidden" id="FCKeditor1___Config" value=""><iframe id="FCKeditor1___Frame" src="/'.INCLUDE_DIR.'/FCKeditor/editor/fckeditor.html?InstanceName='.$this->code.'&Toolbar=Slonne" style="min-width: 640px;" width="100%" height="400px" frameborder="no" scrolling="no"></iframe></div>';
					
				break;
				
				

				
			case 'select':
					//vd($this);
					//vd($value);
					$str.='
					<select name="'.$this->code.''.($this->multiple ? '[]' : '').'" id="'.$this->id.'_'.$this->code.'" '.($this->multiple ? ' multiple="multiple" size="8" ' : '').'>
						<option value="">-выберите-</option>';
					foreach($this->options as $key=>$opt)
					{
						$str.='
						<option value="'.$opt->id.'" '.($opt->id==$value || (in_array($opt->id, $value) && $this->multiple)?' selected="selected" ':'').'>'.$opt->value.'</option>';
					}
					$str.='
					</select>';
				break;
				

				
				
				
				
			case "date":
					$str.='
					<input type="text" name="'.$this->code.'" id="'.$this->id.'_'.$this->code.'" value="'.$value.'"  style="width:70px"> <img id="'.$this->code.'-calendar-btn" src="/js/calendar/calendar.jpg" style="border:0px;">
					
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
					<input type="checkbox" '.($value?' checked="checked" ':'').' name="'.$this->code.'" id="'.$this->id.'_'.$this->code.'">';
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
		}
		
		return $str;
	}
	
	
	
	
	
	
	function backendListOutput($val)
	{
		
		switch($this->type)
		{
			case 'select':
				//vd($this);
				if(!$val)
					$str='<span style="color: #000">-нет значения-</span>';
				else 
				{
					$options = array();
					if(!$this->multiple)
						$options[] = $val;
					else
						foreach($val as $key=>$opt)
							$options[] = $opt;
							
					if(!count($options))
						$str='<span style="color: #000">-нет значения-</span>';
					else
					{
						//vd($options);
						foreach($options as $key=>$opt)
							$str.=' - '.$this->options[$opt->value]->value.'<br/>';
					}
					/*$selectOption = CatSelectOption::get($val);
					//vd($selectOption);
					if($selectOption)
						$str = $selectOption->value;
					else
						$str = 'Ошибка... не найдена опция '.$val;*/
				}
				break;
			
			default:
				$str = $val->value; 
				break;
				
		}
		
		
		
		return $str;
	}
	
	
	
	
	
	
	function frontendInput($value, $lang/*для тайтлов картинок*/)
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
					<input type="text" value="'.($value).'" name="'.$this->code.'" id="'.$this->id.'_'.$this->code.'" style="width: '.($this->size ? $this->size : AdvProp::$defaultFieldsPresets['smalltext']).'px; ">';
				break;
	
	
	
			case 'text':
				if($this->size)
				{
					list($w, $h)=explode("x", $this->size);
					if($w)	$styleStr.=' width: '.$w.'px; ';
					if($w)	$styleStr.=' height: '.$h.'px; ';
				}
				$str.='
					<textarea name="'.$this->code.'" id="'.$this->id.'_'.$this->code.'" style="'.$styleStr.'">'.str_replace("<br />", "\n", $value).'</textarea>';
				break;
	
	
	
	
	
			case 'html':
			case 'html_long':
				if($this->size)
				{
					list($w, $h)=explode("x", $this->size);
					if($w)	$styleStr.=' width: '.$w.'px; ';
					if($w)	$styleStr.=' height: '.$h.'px; ';
				}
					
				$str.='<div><input type="hidden" name="'.$this->code.'" id="'.$this->id.'_'.$this->code.'" value="'.htmlspecialchars(stripslashes($value)).'"><input type="hidden" id="FCKeditor1___Config" value=""><iframe id="FCKeditor1___Frame" src="/'.INCLUDE_DIR.'/FCKeditor/editor/fckeditor.html?InstanceName='.$this->code.'&Toolbar=Slonne" style="min-width: 640px;" width="100%" height="400px" frameborder="no" scrolling="no"></iframe></div>';
					
				break;
	
	
	
	
			case 'select':
				//vd($this);
				//vd($value);
				$str.='
					<select name="'.$this->code.''.($this->multiple ? '[]' : '').'" id="'.$this->id.'_'.$this->code.'" '.($this->multiple ? ' multiple="multiple" size="8" ' : '').'>
						<option value="">-выберите-</option>';
				foreach($this->options as $key=>$opt)
				{
					$str.='
						<option value="'.$opt->id.'" '.($opt->id==$value || (in_array($opt->id, $value) && $this->multiple)?' selected="selected" ':'').'>'.$opt->value.'</option>';
				}
				$str.='
					</select>';
				break;
	
	
	
	
	
	
			case "date":
				$str.='
					<input type="text" name="'.$this->code.'" id="'.$this->id.'_'.$this->code.'" value="'.$value.'"  style="width:70px"> <img id="'.$this->code.'-calendar-btn" src="/js/calendar/calendar.jpg" style="border:0px;">
			
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
					<input type="checkbox" '.($value?' checked="checked" ':'').' name="'.$this->code.'" id="'.$this->id.'_'.$this->code.'">';
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
				
				
				
			case 'table':
				//$str.='xoxoOooi';
				if(count($this->tableColumns))
				{
					$MODEL['prop'] = $this;
					
					$str.=Core::renderPartial('cabinet/advs/tableTypeColumnsInput.php', $MODEL, $buffered=true);
				}
				break;
				
		}
	
		return $str;
	}
	
	
	function frontendListOutput($val)
	{
		//vd($val);
		switch($this->type)
		{
			case 'select':
				//vd($this);
				if(!$val)
					$str='<span style="color: #000">-нет значения-</span>';
					else
					{
						$options = array();
						if(!$this->multiple)
							$options[] = $val;
						else
							foreach($val as $key=>$opt)
								$options[] = $opt;
								
							if(!count($options))
								$str='<span style="color: #000">-нет значения-</span>';
							else
							{
								//vd($options);
								$tmp=array();
								foreach($options as $key=>$opt)
									$tmp[] = $this->options[$opt->value]->value.'';
								
								$str.=join(', ', $tmp);
							}
							/*$selectOption = CatSelectOption::get($val);
							 //vd($selectOption);
							 if($selectOption)
							 	$str = $selectOption->value;
							 	else
							 		$str = 'Ошибка... не найдена опция '.$val;*/
					}
					break;

			case 'table':
				//
				$str = Core::renderPartial('adv/advTablePropValuesView.php', $a=array('prop'=>$this), $buffered=true);
				break;		
					
			default:
				$str = $val->value;
				break;
	
		}
	
	
	
		return $str;
	}
	
	
	
	function validateValue($value)
	{
		$problem = NULL;
	
		switch($this->type)
		{
			case "num":
				$value=trim($value);
				
				if(!$value)
				{	
					if($this->required)
						$problem = Slonne::setError($this->code, 'Заполните поле <b>'.$this->name.'</b>');
				}
				elseif(!is_numeric($value))		#	проверяем в любом случае, даже если не required (если хоть что то введено)
					$problem = Slonne::setError($this->code, 'Поле <b>'.$this->name.'</b> должно быть числом!');
			break;
				
			
			case "smalltext":
			case "text":
			case "html":
			case "html_long":
				$value=trim($value);
				if($this->required && !$value )
					$problem = Slonne::setError($this->code, 'Заполните поле <b>'.$this->name.'</b>');
			break;
				
			
			case "select":	### ДОПИЛИТЬ ПРОВЕРКУ МНОЖЕСТВЕННЫХ СПИСКОВ!!
				$ok = false;
				
				if($value)
				{
					if(!$this->multiple)
					{
						if($this->options[$value])
						{}
						else
							$problem = Slonne::setError($this->code, 'Ошибка! В поле <b>'.$this->name.'</b> выбрана опция, не принадлежащая этому списку! <b>optId:'.$value.'</b>');
					}
					else	#	мультивыбор
					{
						#	избавляемся от пустого 
						foreach($value as $v)
							if(trim($v))
								$arr[] = $v;
						$value = $arr;
						
						if(!$value)	#	ВЫБРАНО ТОЛЬКО ПУСТОЕ ЗНАЧЕНИЕ
							$problem = Slonne::setError($this->code, 'Не выбрано значение в поле <b>'.$this->name.'</b>');
						else
						{
							foreach($value as $key=>$optId)
							{
								if($this->options[$optId])
								{}
								else
									$problem = Slonne::setError($this->code, 'Ошибка! В поле <b>'.$this->name.'</b> выбрана опция, не принадлежащая этому списку! <b>optId:'.$optId.'</b>');
							}
						}
					}
				}
				else
					if($this->required)
						$problem = Slonne::setError($this->code, 'Не выбрано значение в поле <b>'.$this->name.'</b>');
				
				
					//if($this->required && ( $this->multiple && !count($value) || (!$this->multiple && !$value)   )   )
						
			break;
			
				
			case "date":
				$value = trim($value);
				
				if($value)
				{
					list($year, $month, $day)=explode('-', $value);
					if($this->required && (strlen($year)!=4 || strlen($month)!=2 || strlen($day)!=2) )
						$problem = Slonne::setError($this->code, 'Дата должна иметь формат <b>YYYY-MM-DD</b> в поле <b>'.$this->name.'</b>');

					elseif($this->required && (!is_numeric($year) || !is_numeric($month) || !is_numeric($day)) )
						$problem = Slonne::setError($this->code, 'Недопустимые символы в поле <b>'.$this->name.'</b>');	
						
					elseif($this->required && ($month > 12 || $day > 31) )
						$problem = Slonne::setError($this->code, 'Некорректная дата в поле <b>'.$this->name.'</b>');
				}
				elseif($this->required)
					$problem = Slonne::setError($this->code, 'Заполните поле <b>'.$this->name.'</b>');
			break;

			

		}
		
		return $problem;
	}
	
	
	
	function adminUrl()
	{
		return '/'.ADMIN_URL_SIGN.'/props/?propId='.$this->id;
	}
	
	
}
?>