<?php
//vd($MODEL); 
?>
<h1>Активация аккаунта</h1>


<?php
if(!$MODEL['error'])
{?>
	
	<div class="notice"><h2 style="padding: 0; margin: 0 ; ">Отлично, Ваш аккаунт успешно активирован!</h2> 
	<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Используйте указанные Вами данные для <a href="#auth" onclick="$('#top-auth-form').fadeIn('fast');; return false; ">авторизации на сайте</a>.</div>
<?php 	
} 
else 
{?>
	<div class="error"><?=$MODEL['error']?></div>
<?php 
}
?>
