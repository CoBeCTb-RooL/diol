<?php

 #	УБРАТЬ ОТСЮДА! во вьюхи 
if($_SESSION['user'])
{?>
<div >	
	<!-- <h1 style="margin: 0;">Личный кабинет</h1> -->
	<div class="cab-menu">
		<a class="<?=(!$CORE->params[0] ? 'active' : '')?>" href="<?=Route::getByName(Route::CABINET)->url()?>">Кабинет</a> 
		<a class="<?=($CORE->params[0] == 'advs' ? 'active' : '')?>" href="<?=Route::getByName(Route::CABINET_MY_ADVS)->url()?>">Мои объявления</a> 
		<a class="<?=($CORE->params[0] == 'profile' && $CORE->params[1] == 'edit' ? 'active' : '')?>" href="<?=Route::getByName(Route::CABINET_PROFILE_EDIT)->url()?>">Редактировать личные данные</a>
		<a class="<?=($CORE->params[0] == 'profile' && $CORE->params[1] == 'password_change' ? 'active' : '')?>" href="<?=Route::getByName(Route::CABINET_PROFILE_CHANGE_PASSWORD)->url() ?>">Смена пароля</a> 
		
		
		&nbsp;&nbsp;<a class="btn" href="javascript:void(0)" onclick="if(confirm('Вы хотите выйти?')){Cabinet.logout()}; return false; ">Выйти</a>
	</div>
	<div style="margin: 16px 0 15px 0  ;"></div>
</div>	
<?php 	
} 
?>

