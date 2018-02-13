<?php
class News extends Entity2 
{
	const ESSENCE = 'news';
	
	const LIST_ORDER = 'dt DESC';

	
	function get($id)
	{
		global $CORE;
		return parent::adapt(__CLASS__, (Entity2::get(self::ESSENCE, $id, Entity2::TYPE_ELEMENTS, $CORE->lang->code)));
	}
	

	function getChildren($pid, $limit, $additionalClauses, $order = self::LIST_ORDER)
	{
		global $CORE;
		
		$params = array(
			'essenceCode'=>self::ESSENCE,
			'pid'=>$pid,
			'limit'=>$limit,
			'type'=>Entity2::TYPE_ELEMENTS, 
			'order'=>$order, 
			'lang'=>$CORE->lang->code, 
			'additionalClauses'=>$additionalClauses,
			'activeOnly'=>true,
		);

		$entities = Entity2::getList($params); 
		foreach($entities as $key=>$val)
			$ret[] = parent::adapt(__CLASS__, $val);

		return $ret;
	}
	
	
	
	
	function url()
	{
		global $CORE, $_CONFIG;
	
		$route = Route::getByName(Route::NOVOSTI_KARTOCHKA);
		$ret = $route->url($this->urlPiece());
		return $ret;
		//return ($CORE->lang->code != $_CONFIG['default_lang']->code?'/'.$CORE->lang->code.'':'') . '/'.URL_ADVS_LIST.'/'.$this->urlPiece();
	}

	
} 
?>