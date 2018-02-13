<?php 
class Media{
	
	const TBL = 'media';
	
	var   $id
		, $idx
		, $pid
		, $active
		, $essenceCode
		, $fieldCode
		, $type
		, $path
		, $title
		, $titleJson;
		
	
	
	
	function getList($essenceCode, $type, $pid, $fieldCode/*необязательно*/)
	{
		//vd($essenceCode);
		if(!($essenceCode && $type && $pid))
			return;
			
		$sql = "SELECT * FROM `".mysql_real_escape_string(self::TBL)."` 
		WHERE `essenceCode` = '".strPrepare($essenceCode)."'
		AND `type` = '".strPrepare($type)."'
		AND `pid` = '".intval($pid)."'
		".($fieldCode ? "AND `fieldCode` = '".strPrepare($fieldCode)."'" : "" )."
		ORDER BY idx";
		$qr=DB::query($sql);
		echo mysql_error();
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
		{
			$res[] = self::init($next);
		}
		
		return $res;
	}
	
	
	
	
	function init($arr)
	{
		$m = new self();
		$m->id = $arr['id'];
		$m->idx = $arr['idx'];
		$m->pid = $arr['pid'];
		$m->active = $arr['active'];
		$m->essenceCode = $arr['essenceCode'];
		$m->fieldCode = $arr['fieldCode'];
		$m->type = $arr['type'];
		$m->path = $arr['path'];
		$m->titleJson = $arr['titleJson'];
		
		if($arr['titleJson'])
			$m->title = json_decode($arr['titleJson'], true);
		
        return $m;
	}
	
	
	
	
	
	
	function get($id)
	{
		if($id = intval($id))
		{
			$sql = "SELECT * FROM `".self::TBL."` WHERE id = ".$id;
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
		`active`='".($this->active ? 1 : 0)."'
		, idx=".intval($this->idx)."
		, `pid`='".intval($this->pid)."'
		, `essenceCode`='".strPrepare($this->essenceCode)."'
		, `fieldCode`='".strPrepare($this->fieldCode)."'
		, `type`='".strPrepare($this->type)."'
		, `path`='".strPrepare($this->path)."'
		, `titleJson`='".strPrepare(json_encode($this->title))."'";
		
		return $str;
	}
	
	
	
	function delete()
	{
		$sql = "
		DELETE FROM `".self::TBL."` WHERE id=".intval($this->id);
		DB::query($sql);
		echo mysql_error();
		$path = $_SERVER['DOCUMENT_ROOT'].'/'.UPLOAD_IMAGES_REL_DIR.$this->path;
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
		return '/include/resize.slonne.php?img=../'.UPLOAD_IMAGES_REL_DIR.$pic;
	}
	
	
	
	
	#	пока нигде не юзается
	function getByPath($path)
	{
		if(strPrepare($path))
		{
			$sql = "SELECT * FROM `".self::TBL."` WHERE path = '".strPrepare($path)."'";
			$qr=DB::query($sql);
			echo mysql_error();
			if($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				return self::init($next);
		}
	}
	
	
	
}
?>