<?php 
class ArtNum{
	
	const TBL = 'adv__article_numbers';
	const MEDIA_SUBDIR = 'artnums';
	
	var   $id
		, $status
		, $dateCreated
		, $idx
		, $name
		, $pic
		, $picRaw
		;
		
	
	function init($arr)
	{
		$m = new self();
	
		$m->id = $arr['id'];
		$m->name = $arr['name'];
		$m->idx = $arr['idx'];
		$m->dateCreated = $arr['dateCreated'];
		//$m->pic = $arr['pic'] ? self::MEDIA_SUBDIR.'/'.$arr['pic'] : '';
		$m->picRaw = $arr['pic'];
		$m->pic = $m->picRaw ? self::MEDIA_SUBDIR.'/'.$arr['pic'] : '';;
		$m->status = Status::num($arr['status']);
		
		//vd($m);
		return $m;
	}
	
	
	
	function getList($status, $brandId, $catId)
	{
		//$sql = "SELECT * FROM `".mysql_real_escape_string(self::TBL)."` WHERE 1  ORDER BY id";
		//vd($catId);
		$brandId = intval($brandId);
		$catId = intval($catId);
		//vd($catId);
		
		$sql = "SELECT artnums.* FROM `".mysql_real_escape_string(self::TBL)."` AS artnums ";
		
		if($catId && $brandId)
			$sql.=" INNER JOIN  `".CatBrandArtnumCmb::TBL."` AS cmb  ON cmb.artnumId=artnums.id  
					INNER JOIN  `".BrandArtnumCmb::TBL."` AS cmb2  ON cmb2.artnumId=artnums.id  AND cmb2.brandId=cmb.brandId";
		elseif($brandId)
			$sql.=" INNER JOIN  `".BrandArtnumCmb::TBL."` AS cmb  ON cmb.artnumId=artnums.id ";
		
		$sql.=" WHERE 1 ";
		
		if($brandId)
			$sql.=" AND cmb.brandId=".$brandId." ";
		if($catId)
			$sql.=" AND cmb.catId=".$catId." ";
		
		if($status)
			$sql.=" AND status='".strPrepare($status->num)."' ORDER BY id ";
		
		#!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		//$sql.=" LIMIT 10";	
			
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
			$res[] = self::init($next);
		
		return $res;
	}
	
	
	
	function getListByIds($ids)
	{
		$sql = "SELECT * FROM `".mysql_real_escape_string(self::TBL)."` WHERE id IN(".mysql_real_escape_string(join(',', $ids)).") ORDER BY id";
		//vd($sql); 
		$qr=DB::query($sql);
		echo mysql_error();
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
			$res[] = self::init($next);
	
		return $res;
	}
	
	
	
	
	
	
	
	
	
	function get($id)
	{
		if($id =intval($id))
		{
			$sql = "SELECT * FROM `".self::TBL."` WHERE id = ".$id;
			$qr=DB::query($sql);
			echo mysql_error();
			if($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				return self::init($next);
		}
	}
	
	
	
	
	function getByName($name)
	{
		if($name =trim($name))
		{
			$sql = "SELECT * FROM `".self::TBL."` WHERE name = '".strPrepare($name)."'";
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
		SET `dateCreated` = NOW(), 
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
		  `status`='".intval($this->status->num)."'
		, `idx`='".intval($this->idx)."'
		, `name`='".strPrepare($this->name)."'
		, `pic`='".strPrepare($this->picRaw)."'
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
	
	
	
	function setIdx($id, $val)
	{
		if($id=intval($id))
		{
			$sql = "UPDATE `".self::TBL."` SET idx='".intval($val)."' WHERE id=".$id;
			//vd($sql);
			DB::query($sql);
			echo mysql_error();
		}
	}
	
	
	
	
	
	function validate()
	{
		if(!trim($this->name))
			$errors[] = new Error('Введите название', 'name');
		
		return $errors;
	}
	
	
	
	
	function getNextIdx()
	{
		$sql = "SELECT MAX(idx) as res  FROM `".mysql_real_escape_string(self::TBL)."`";
		$qr = DB::query($sql);
		echo mysql_error();
		
		$next = mysql_fetch_array($qr, MYSQL_ASSOC);
		$res = $next['res'];
		
		$res = $res % 10 ? $res + (10-$res%10) : $res+10;
		
		return $res;
	}
	
	
	
	
	
}
?>