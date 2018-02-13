<?php
SuggestionType::initArr();

class SuggestionType{
	
	static $items;
	
	public $code;
	public $name;
	public $namePlural;
	public $icon;
		
	const UNSPECIFIED 	= '';
	const SUGGESTION 	= 'suggestion';
	const QUESTION 		= 'question';
	
	
	
	public  function initArr()
	{
		$arr[self::SUGGESTION]		= new self( self::SUGGESTION,	'Предложение', 'Предложения', '<i class="fa fa-lightbulb-o" aria-hidden="true"></i>');
		$arr[self::QUESTION]		= new self( self::QUESTION, 	'Вопрос', 'Вопросы', '<i class="fa fa-question-circle" aria-hidden="true"></i>');
		
		
		self::$items = $arr;
	}
	
	
	
	function  __construct($code, $name, $namePlural, $icon)
	{
		$this->code=$code;
		$this->name=$name;
		$this->namePlural = $namePlural;
		$this->icon = $icon;
	}
	
	
	
	
	function code($code)
	{
		return self::$items[$code];
	}
	
	
	
}

