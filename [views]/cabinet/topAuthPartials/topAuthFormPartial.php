<?php $formId = 'top-auth-form' ?>
<div class="unauthed-wrapper">
	
	<a href="#login" onclick="$('#top-auth-form').fadeToggle('fast'); return false; "><i class="fa fa-sign-in" aria-hidden="true"></i> Вход</a> <span class="desktop-only">| <a href="<?=Route::getByName(Route::CABINET_PROFILE_EDIT)->url()?>">Регистрация</a></span>
	
	
	<form method="post" action="" id="top-auth-form" target="iframe1" id="<?=$formId?>" onsubmit="return Cabinet.checkAuthForm('top-auth-form'); ">
		<div class="inner">
			<a href="#" onclick="$('#top-auth-form').fadeOut('fast'); return false; " class="close">&times;</a>
			<div class="row"><span class="label">E-mail: </span><input type="text" name="email" placeholder="E-mail" /></div>
			<div class="row"><span class="label">Пароль: </span><input type="password" name="password" placeholder="Пароль"  /></div>
			
			<div class="buttons">
				<input type="submit" value="Войти">
				<span>или</span>
				<!--<input type="submit" value="Зарегистрироваться">-->
				<a href="<?=Route::getByName(Route::CABINET_PROFILE_EDIT)->url()?>">Зарегистрироваться</a>
			</div>
			
			<div class="loading" style="display: none; ">Секунду...</div>
			<div class="info"></div>
			<a href="<?=Route::getByName(Route::CABINET_PASSWORD_RESET_CLAIM)->url()?>" class="forgot">забыли пароль?</a>
		</div>
	</form>
	
	
</div>