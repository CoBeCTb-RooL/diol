<?php
class Error{
	public $error;
	public $field; 
	
	const NO_ACCESS_ERROR = 'Нет доступа.';
	
	function __construct($error, $field)
	{
		$this->error = $error;
		$this->field = $field;
	}
	
	
	function merge($arr1, $arr2)
	{
		$ret =  array_merge($arr1 ? $arr1 : array(), $arr2 ? $arr2 : array());
		$ret = count($ret) ? $ret : null;
		return $ret;
	}
	
}