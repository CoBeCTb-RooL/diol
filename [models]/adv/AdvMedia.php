<?php 
class AdvMedia{
	
	const TBL = 'adv__media';
	const ADV_MEDIA_SUBDIR = 'adv_media';
	
	var   $id
		, $idx
		, $pid
		, $statusId
		, $status
		
		, $path
		, $title
		;
		
	
	
	
	function getByPidsList($ids, $status)
	{
		if($ids)
		{
			foreach($ids as $key=>$val)
				$ids[$key] = intval($val);
					
			$sql="SELECT * FROM `".strPrepare(self::TBL)."` WHERE 1  AND pid IN (".join(', ', $ids).") ";
			if($status)
				$sql.=" AND status='".intval($status->num)."' ";
			//vd($sql);
			$qr=DB::query($sql);
			echo mysql_error();
			while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				$ret[$next['pid']][] = self::init($next);
		}
		//vd($ret);
		return $ret;
	}
		
		
	
	
	function getList($pid, $status)
	{		
		$sql = "SELECT * FROM `".strPrepare(self::TBL)."` 
		WHERE 1 
		AND `pid` = '".intval($pid)."'
		".($status ? " AND status=".intval($status->num)."" : "")."
		ORDER BY idx";
		$qr=DB::query($sql);
		echo mysql_error();
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
			$res[] = self::init($next);
		
		return $res;
	}
	
	
	
	
	function init($arr)
	{
		$m = new self();
		$m->id = $arr['id'];
		$m->idx = $arr['idx'];
		$m->pid = $arr['pid'];
		$m->path = $arr['path'];
		$m->statusId = $arr['status'];
		$m->status = Status::num($m->statusId);
		$m->title= $arr['title'];
		
        return $m;
	}
	
	
	
	
	
	
	function get($id)
	{
		if($id = intval($id))
		{
			$sql = "SELECT * FROM `".self::TBL."` WHERE id = ".$id."
			";
			$qr=DB::query($sql);
			echo mysql_error();
			if($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				return self::init($next);
		}
	}
	
	
	
	
	
	function insert()
	{
		$this->idx = 9999;
		$sql = "
		INSERT INTO `".self::TBL."` 
		SET 
		".$this->alterSql()."
		
		";
		vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		
		$this->id = mysql_insert_id();
	}
	
	
	
	
	
	function update()
	{
		//vd($this);
		$sql = "
		UPDATE `".self::TBL."` 
		SET 
		".$this->alterSql()."
		WHERE id=".intval($this->id)."
		";
		vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
	}
	
	
	
	function alterSql()
	{
		$str.="
		  `status`='".intval($this->status->num)."'
		, `idx`=".intval($this->idx)."
		, `pid`='".intval($this->pid)."'
		, `path`='".strPrepare($this->path)."'
		, `title`='".strPrepare($this->title)."'";
		
		return $str;
	}
	
	
	
	function delete()
	{
		$sql = "
		DELETE FROM `".self::TBL."` WHERE id=".intval($this->id);
		DB::query($sql);
		echo mysql_error();
		$path = ROOT.$this->src();
		unlink($path);	
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
	
	
	
	
	
	#	формирует src да ресайза АПЛОУДЕНОЙ картинки (для возможности смены урла - дописать передачу вторым параметром)
	function img($src)
	{
		//return ABS_PATH_TO_RESIZER_SCRIPT.'?file=/'.UPLOAD_IMAGES_REL_DIR.$src;
		return self::img2($src);
	}
	
	
	function img2($pic)
	{
		return ABS_PATH_TO_RESIZER_SCRIPT_NEW.'?img=../'.UPLOAD_IMAGES_REL_DIR.$pic;
	}
	
	function src($relative=false)
	{
		
		return (!$relative ? '/'.UPLOAD_IMAGES_REL_DIR : '').AdvMedia::ADV_MEDIA_SUBDIR.'/'.$this->path;
	}
	
	function resizeSrc()
	{
		return self::img(AdvMedia::ADV_MEDIA_SUBDIR.'/'.$this->path);
	}
	
	function noPhotoSrc()
	{
		return ABS_PATH_TO_RESIZER_SCRIPT_NEW.'?img=../'.NO_PHOTO_REL_PATH;
		//return self::img(NO_PHOTO_REL_PATH);
	}
	
	
	
	
	
	
	function savePic($file, $destDir, $newFileName)
	{
		$problem = '';
		
		if(!trim($newFileName))
			$newFileName = Funx::generateName();

		if($file )
		{
			$dot=strrpos($file['name'], '.');
			$name=(substr($file['name'], 0, $dot));
			$ext=strtolower(substr($file['name'],  $dot+1));
			
			$tmpFile = $file["tmp_name"];
			if(is_uploaded_file($tmpFile))
			{
				mkdir($destDir, 0777, $recursive=true);
				
				$destFile=$destDir.'/'.$newFileName.'.'.$ext;
				//vd($destFile);
				
				if( move_uploaded_file($tmpFile, $destFile))	
				{
					#	всё ок, проблем не возвращаем
					echo '<hr><hr><hr>';
					//vd($destFile);
					# 	проверка , не огромен ли файл, и его ужимка
					$tmp = getimagesize($destFile);
					//$weight = filesize($destFile);
					$w = $tmp[0];
					$h=$tmp[1];
					if($w>$h)
					{
						if($w > MAX_PIC_WIDTH)
						{
							$image = new ImageResize($destFile);
							$image->resizeToWidth(MAX_PIC_WIDTH);
							$image->quality_jpg = 100;
							$image->quality_png = 9;
							$image->save($destFile);
						}
					}
					else
					{
						
						if($h > MAX_PIC_HEIGHT)
						{
							$image = new ImageResize($destFile);
							$image->resizeToHeight(MAX_PIC_HEIGHT);
							$image->quality_jpg = 100;
							$image->quality_png = 9;
							$image->save($destFile);
							//vd($destFile);
						}
					}
				}
				else 
					$problem = Slonne::setError('', 'Не удалось загрузить файл <b>'.$file['name'].'</b>');
			}
			else
				$problem = Slonne::setError('', 'Файл <b>'.$file['name'].'</b> не загружен..');
		}
		else
			$problem = Slonne::setError('', 'Не удалось загрузить файл <b>'.$file['name'].'</b>');
			
		$result['problem'] = $problem;
		$result['newFileName'] = $newFileName.'.'.$ext;
		 
		return $result;
	}
	
	
	
	
	function getSubdirsByFile($file)
	{
		//vd($file);
		if(strpos($file, '.')!==false)
			$file = mb_substr($file, 0, strpos($file, '.'));
		
		$tmp1 = mb_substr($file, -2 );
		//vd($tmp1);
		$tmp2 = mb_substr($file, -4, 2 );
		//vd($tmp2);
		
		$ret = $tmp2.'/'.$tmp1;
		
		//vd($ret);
		return $ret;
	}
	
	
	
}
?>