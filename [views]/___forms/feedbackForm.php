<?php 
$formId = 'feedback-form'; 
?>
<div class="form">
	<h1><?=$_CONST['ОБРАТНАЯ СВЯЗЬ']?></h1>
	
	<form id="<?=$formId?>" method="post" target="iframe1" action="/<?=LANG?>/forms/feedback/submit" onsubmit="return Forms.FeedbackForm.check();" >
			
		<span class="row"><span class="label"><?=$_CONST['ИМЯ']?>: </span><input type="text" name="name" placeholder="<?=$_CONST['ИМЯ']?>" /></span>
		<span class="row"><span class="label"><?=$_CONST['ТЕЛЕФОН']?>: </span><input type="text" name="tel"  placeholder="<?=$_CONST['ТЕЛЕФОН']?>" /></span>
		
		<div>
			<textarea name="msg" id="msg" style="height: 80px; width: 400px; " placeholder="Введите сообщение..."></textarea>
		</div>
		
		
		<div class="loading"  style="display: none;"><?=$_CONST['ЗАГРУЗКА']?></div>
		<input type="submit"  value="<?=$_CONST['ОТПРАВИТЬ']?>" >
		<div class="info" ></div>	
	</form>
	
	<div id="<?=$formId?>-success"  style="display: none;">
		<h2>Ваше сообщение отправлено!</h2>
		Мы обязательно в скором времени его прочтём!
	</div>
</div>