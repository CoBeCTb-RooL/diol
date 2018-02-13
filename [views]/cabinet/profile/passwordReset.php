<?php
//vd($MODEL);
$newPass = $MODEL['newPass']; 
?>
<h1>Восстановление пароля</h1>


<?php
if(!$MODEL['error'])
{?>
	
	<div class="notice">
		<h2 style="padding: 0; margin: 0 ; ">Ваш пароль успешно изменён!</h2>
	 
	 	<div style="margin: 4px 0 7px 0;">Новый пароль: <b><?=$newPass?></b></div>
	 	
		Используйте указанные Вами данные для <a href="#auth" onclick="$('#top-auth-form').fadeIn('fast');; return false; ">авторизации на сайте</a>.
		</div>
<?php 	
} 
else 
{?>
	<div class="error"><?=$MODEL['error']?></div>
<?php 
}
?>
