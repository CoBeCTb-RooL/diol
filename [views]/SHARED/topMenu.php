<?php
$uri = $_SERVER['PATH_INFO'];
$section = '';

if(strpos($uri, Route::getByName(Route::SUGGESTIONS)->url()) === 0)
	$section = 'suggestions';
elseif(strpos($uri, Route::getByName(Route::NEWS)->url()) === 0)
	$section = 'news';
elseif(strpos($uri, Route::getByName(Route::CABINET)->url()) === 0)
	$section = 'cabinet';

elseif(strpos($uri, Route::getByName(Route::HELP)->url()) === 0)
	$section = 'help';

elseif(		strpos($uri, Route::getByName(Route::SPISOK_KATEGORIY)->url()) === 0
		||	strpos($uri, Route::getByName(Route::SPISOK_OBYAVLENIY_KATEGORII)->url()) === 0
		||	strpos($uri, Route::getByName(Route::KARTOCHKA_OBYAVLENIYA)->url()) === 0
	)
	$section = 'cats';
?>


<ul class="menu">
	<li>
		<a class="<?=$section == 'cats' ? 'active' : ''?>" href="<?=Route::getByName(Route::SPISOK_KATEGORIY)->url()?>">Объявления</a>
	</li>
	<li>
		<a class="<?=$section == 'news' ? 'active' : ''?>" href="<?=Route::getByName(Route::NEWS)->url()?>">Наши новости</a>
	</li>
	<li>
		<a class="<?=$section == 'suggestions' ? 'active' : ''?>" href="<?=Route::getByName(Route::SUGGESTIONS)->url()?>">Вопросы, предложения</a>
	</li>
	
	<?php 
	if($USER)
	{?>
	<li>
		<a class="<?=$section == 'cabinet' ? 'active' : ''?>" href="<?=Route::getByName(Route::CABINET)->url()?>">Кабинет</a>
	</li>
	<?php 
	}?>

    <li>
        <a class="<?=$section == 'help' ? 'active' : ''?>" href="<?=Route::getByName(Route::HELP)->url()?>">Помощь</a>
    </li>

	<li class="clear"></li>
</ul>