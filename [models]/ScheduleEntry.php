<?php 
class ScheduleEntry{
	
	const TBL = 'schedule';
	
	const URL_SIGN = 'category';

	public $id;
	public $clientId;
	public $doctorId;
	public $serviceId;
	public $dt;
	public $price;
	public $comment;
	public $dateCreated;
	public $dateUpdated;



	const TIME_FROM = 8;
	const TIME_TILL = 20;
	const MINUTES_STEP = 30;


	function init($arr)
	{
		$m = new self();

		$m->id = $arr['id'];
		$m->clientId = $arr['clientId'];
		$m->doctorId = $arr['doctorId'];
		$m->serviceId = $arr['serviceId'];
		$m->dt = $arr['dt'];
		$m->price = $arr['price'];
		$m->comment = $arr['comment'];
		$m->createdAt = $arr['createdAtAt'];
		$m->updatedAt= $arr['updatedAt'];
		$m->statusId = $arr['status'];
		$m->status = Status::num($m->statusId);

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
		if(gettype($params) != 'array' )
			$params = array('pid'=>$params, 'status'=>$status);
		
		$sql="SELECT COUNT(*) FROM `".self::TBL."` WHERE 1 ".self::getListInnerSql($params);
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		$next = mysql_fetch_array($qr);
		//vd($next);
		
		return $next[0];
	}
	

	
	
	
	
	function getListInnerSql($params)
	{
		//vd($params);
		$sql="";

		if(isset($params['status']) && $params['status'])
			$sql.="AND status='".intval($params['status']->num)."";

		if(isset($params['clientId']) && $params['client'])
			$sql.=" AND clientId=".intval($params['clientId'])." ";

		if(isset($params['serviceId']) && $params['serviceId'])
			$sql.=" AND serviceId=".intval($params['serviceId'])." ";
		if(isset($params['serviceIds']) && $params['serviceIds'])
			$sql.=" AND serviceId IN(".$params['serviceIds'].") ";

		if(isset($params['doctorId']) && $params['doctorId'])
			$sql.=" AND doctorId=".intval($params['doctorId'])." ";

		if(isset($params['date']) && $params['date'])
			$sql.=" AND DATE(dt)='".strPrepare($params['date'])."' ";
		if(isset($params['dateFrom']) && $params['dateFrom'])
			$sql.=" AND DATE(dt)>='".strPrepare($params['dateFrom'])."' ";
		if(isset($params['dateTo']) && $params['dateTo'])
			$sql.=" AND DATE(dt)<='".strPrepare($params['dateTo'])."' ";


		if(isset($params['from']) && isset($params['count']))
			$sql.=" LIMIT  ".$params['from'].", ".$params['count']."";

		return $sql;
	}
	
	
	
	function getByIdsList($ids, $status)
	{
		if($ids)
		{
			foreach($ids as $key=>$val)
				$ids[$key] = intval($val);
					
			$sql="SELECT * FROM `".strPrepare(self::TBL)."` WHERE 1  AND id IN (".join(', ', $ids).") ";
			if($status)
				$sql.=" AND status='".intval($status->num)."' ";
				//vd($sql);
			$qr=DB::query($sql);
			echo mysql_error();
			while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				$ret[$next['id']] = self::init($next);
		}
		return $ret;
	}
	
	
	

	
	function fio()
	{
		return trim($this->surname.' '.$this->name.' '.$this->fathername);
	}
	

	
	
	function get($id, $status)
	{
		if($id =intval($id))
		{
			$sql = "SELECT * FROM `".self::TBL."` WHERE id = ".$id."  
				   ".($status ? " AND status='".intval($status->num)."' " : "")." 
				   ";
			$qr=DB::query($sql);
			echo mysql_error();
			if($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				return self::init($next);
		}
	}
	
	
	
	
	function getElderCats()
	{
		$pid = $this->pid;
		while($pid)
		{
			$tmp = Client::get($pid);
			$pid = $tmp->pid;
			$ret[] = $tmp;
		}
		
		$this->elderCats = array_reverse($ret);
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
		  `clientId`='".intval($this->clientId)."'
		, `doctorId`='".intval($this->doctorId)."'
		, `serviceId`='".intval($this->serviceId)."'
		, `dt`='".strPrepare($this->dt)."'
		, `price`='".floatval($this->price)."'
		, `comment`='".strPrepare($this->comment)."'
		
		, `status` = '".intval($this->status->num)."'
		, `updatedAt` = NOW()
		";
		
		return $str;
	}



	function setData($arr)
	{
		$this->clientId = intval($arr['clientId']);
		$this->doctorId = intval($arr['doctorId']);
		$this->serviceId = intval($arr['serviceId']);
		$this->dt = strPrepare($arr['dt']);
		$this->price = floatval($arr['price']);
		$this->dt = trim($arr['date'].' '.trim($arr['time']));
		$this->comment = strPrepare($arr['comment']);
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
	
	

	function initMedia()
	{
		$arr = [];

		$arr = Media2::getList([
			'objType'=>Object::SERVICE,
			'objId' =>$this->id,
			'orderBy' => 'idx',
		]);

		$this->media = $arr;
	}



	function initClient()
	{
		$this->client = Client::get($this->clientId);
	}

	function initService()
	{
		$this->service = Service::get($this->serviceId);
	}

	function initDoctor()
	{
		$this->doctor = Admin::get($this->doctorId);
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
		if(!trim($this->clientId))
			$problems[] = Slonne::setError('clientId', 'Укажите клиента');
		if(!trim($this->doctorId))
			$problems[] = Slonne::setError('doctorId', 'Укажите врача');
		if(!trim($this->serviceId))
			$problems[] = Slonne::setError('serviceId', 'Укажите процедуру');
		if(!trim($this->dt))
			$problems[] = Slonne::setError('dt', 'Укажите дату записи');
		if(!trim($this->price))
			$problems[] = Slonne::setError('price', 'Укажите стоимость');

		
		return $problems;
	}





	function timeArr()
	{
		$ret = [];

		for($h=self::TIME_FROM; $h<self::TIME_TILL; $h++)
		{
			for ($m=0; $m<60; $m+=self::MINUTES_STEP)
			{
				$valH = $h;
				if(mb_strlen($valH) == 1)
					$valH = '0'.$valH;
				$valM = $m;
				if(mb_strlen($valM) == 1)
					$valM = '0'.$valM;

				$totalTimeStr = $valH.':'.$valM;
				$ret[] = $totalTimeStr;

			}
		}

		return $ret;
	}





	function getTime()
	{
		return date('H:i', strtotime($this->dt));
	}
	

	
	
}
?>