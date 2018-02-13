<?php
class Content{
	
	
	public $title;
	public $content;
	
	public $metaKeywords;
	public $metaDescription;
	
	public $sectionHeader;
	
	public $titlePostfix;
	public $titleSeparator;
	public $isTitleStartsWithPostfix;
	
	
	function __construct()
	{
		global $_CONFIG;
			
	}
	
	
	function setTitle($str)
	{
		global $_GLOBALS, $_CONFIG;

		if(!$this->isTitleStartsWithPostfix)
			$title = $str.''.$this->titleSeparator.''.$this->titlePostfix;
		else
			$title = $this->titlePostfix.''.$this->titleSeparator.''.$str;
		
		$this->title = $title;
	}
	
	
	
}