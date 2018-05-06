<?php
class Media2{

	const TBL = 'media2';

	public $id;
	public $objId;
	public $objType;
	public $title;
	public $path;
	public $idx;



	function init($arr)
	{
		$m = new self();

		$m->id = $arr['id'];
		$m->objId = $arr['objId'];
		$m->objType = $arr['objType'];
		$m->title = $arr['title'];
		$m->path = $arr['path'];
		$m->idx= $arr['idx'];
		$m->createdAt = $arr['createdAt'];
		$m->updatedAt= $arr['updatedAt'];

		return $m;
	}

	function getList($params)
	{
		$sql="SELECT * FROM `".self::TBL."` WHERE 1 ".self::getListInnerSql($params);
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		while($next=mysql_fetch_array($qr, MYSQL_ASSOC))
		{
			$ret[] = self::init($next);
		}

		return $ret;
	}





	function getCount($params, $status)
	{
		$sql="SELECT COUNT(*) FROM `".self::TBL."` WHERE 1 ".self::getListInnerSql($params);
		$qr=DB::query($sql);
		echo mysql_error();
		$next = mysql_fetch_array($qr);

		return $next[0];
	}






	function getListInnerSql($params)
	{
		$sql="";

		if(isset($params['objType']) && $params['objType'])
			$sql.=" AND objType='".strPrepare($params['objType'])."' ";
		if(isset($params['objId']) && $params['objId'])
			$sql.=" AND objId='".strPrepare($params['objId'])."' ";

		if(isset($params['orderBy']) && $params['orderBy'])
			$sql.=" ORDER BY ".strPrepare($params['orderBy'])." ";

		if(isset($params['from']) && isset($params['count']))
			$sql.=" LIMIT  ".$params['from'].", ".$params['count']." ";

		return $sql;
	}





	function get($id)
	{
		if($id =intval($id))
		{
			$sql = "SELECT * FROM `".self::TBL."` WHERE id = ".$id." ";
			$qr=DB::query($sql);
			echo mysql_error();
			if($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				return self::init($next);
		}
	}




	function insert()
	{
		$sql = "
		INSERT INTO `".self::TBL."` 
		SET 
		createdAt=NOW(),
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
		  `objType`='".strPrepare($this->objType)."'
		, `objId`='".intval($this->objId)."'
		, `title`='".strPrepare($this->title)."'
		, `path`='".strPrepare($this->path)."'
		, `idx`='".intval($this->idx)."'
		, `updatedAt` = NOW()
		";

		return $str;
	}




	function delete()
	{
		unlink($_SERVER['DOCUMENT_ROOT'].$this->src() );
		$sql = "
		DELETE FROM `".self::TBL."` WHERE id=".$this->id;
		DB::query($sql);
		echo mysql_error();

	}





	function setIdx($id, $val)
	{
		if($id=intval($id))
		{
			$sql = "UPDATE `".self::TBL."` SET idx='".intval($val)."' WHERE id=".$id;
			vd($sql);
			DB::query($sql);
			echo mysql_error();
		}
	}





	function validate()
	{
		if(!trim($this->objType))
			$problems[] = Slonne::setError('objType', 'Не указан тип объекта');
		if(!trim($this->objId))
			$problems[] = Slonne::setError('objId', 'Не указан id объекта');
		if(!trim($this->path))
			$problems[] = Slonne::setError('path', 'Не указан путь к медиа');


		return $problems;
	}





	function getNextIdx($params)
	{
		$sql = "SELECT MAX(idx) as res  FROM `".strPrepare(self::TBL)."` WHERE 1 ".self::getListInnerSql($params);
		$qr = DB::query($sql);
		echo mysql_error();

		$next = mysql_fetch_array($qr, MYSQL_ASSOC);
		$res = $next['res'];

		$res = $res % 10 ? $res + (10-$res%10) : $res+10;

		return $res;
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
				vd($destFile);

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




	public function src()
	{
		return trim($this->path) ? '/'.UPLOAD_IMAGES_REL_DIR.$this->path : '';
	}

}
?>