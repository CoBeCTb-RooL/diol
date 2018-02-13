<?php
class DB{
	
	#	коннект к базе. данные берутся из констант в конфиге
	function connect()
	{
		if(mysql_connect(DB_HOST, DB_USER, DB_PASSWORD))
		{
			if(mysql_select_db(DB_NAME))
			{
				mysql_query("SET NAMES 'utf8'");
				if ($err=mysql_error())
					die("$err");
			
				return;
			}
			else
				die("no such database");
		}
		else
			die("no db connect1ion");
	}
	
	
	
	
	function query($sql)
	{
		if(Admin::isAdmin())
			$t=new Timer($sql, $type=Timer::TYPE_QUERY);
		//usleep(700000);
		$tmp = mysql_query($sql);
		
		if(Admin::isAdmin())
			$t->stop();
		
		return $tmp;
		
	}
	
	
	
	function transactionStart()
	{
		mysql_query("SET AUTOCOMMIT=0");
		mysql_query("START TRANSACTION");
	}
	
	function commit()
	{
		mysql_query("COMMIT");
	}
	
	function rollback()
	{
		mysql_query("ROLLBACK");
	}
} 
?>