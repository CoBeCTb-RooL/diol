<?php
class Timer{
	const TYPE_CUSTOM 			= 'custom';
	const TYPE_QUERY 			= 'query';
	const TYPE_GLOBAL_TIMER 	= 'global';
	
	public $title;
	public $time;
	public $type;
	public $memory;
	
	public $timeStart;
	public $timeEnd;


	function __construct($title, $type=self::TYPE_CUSTOM)
	{
		$this->title = $title;
		$this->type = $type;
		$this->time = $this->timeStart = microtime($get_as_float = true);
		$this->memory = memory_get_usage();
	}

	function stop()
	{
		global $_GLOBALS;
		$this->timeEnd = microtime($get_as_float = true);
		$this->time = round(  (microtime($get_as_float = true) - $this->time), 4 ) ;
		$this->memory = memory_get_usage()-$this->memory-sizeof($this->memory);
		$_SESSION['timersNewPortion'][] = $this;
	}
	
	function fixSessionTimers()
	{
		$tmp = null;
		foreach($_SESSION['timers'] as $key=>$t)
			$tmp[$key] = Slonne::cast('Timer', $t);
		$_SESSION['timers'] = $tmp;
		unset($tmp);
	}
	
	
	
}