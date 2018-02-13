<?php
$u = $MODEL; 
?>

<div class="top-greeting">
	<span class="desktop-only">Здравствуйте, </span> <b><?=$u->name?></b>
	<div class="links">
		<a href="<?=Route::getByName(Route::CABINET)->url()?>"><i class="fa fa-user"></i> <span class="desktop-only">Личный</span> кабинет</a>
		| <a href="#logout" class="logout" onclick="if(confirm('Вы хотите выйти?')){Cabinet.logout();} return false; ">Выйти</a>
	</div>
</div>