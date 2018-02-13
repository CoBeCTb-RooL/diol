<?php
$user=$MODEL['user']; 

?>



<? ob_start()?>
<script>
function checkProfileForm(isNew){
	//return true
	// 	зачистка эрроров формы
	$('#edit-form *').each(function(n,element){
		$(element).removeClass('error')
	});
	//$('#edit-form .info').html('')
	
	var problems=[]
	var fillAllError = 'Пожалуйста, заполните необходимые поля!'
	
	//return true	
		
	// 	проверка на заполненность полей
	/*if($('#surname').val() == '')
		problems.push({field: 'surname', error: fillAllError})*/
	if($('#name').val() == '')
		problems.push({field: 'name', error: 'Укажите <b>имя</b>'})
	if($('#phone').val() == '')
		problems.push({field: 'phone', error: 'Введите <b>контактные телефоны</b>'})
	if($('#email').val() == '')
		problems.push({field: 'email', error: 'Укажите Ваш <b>e-mail</b>'})
	if($('#city').val() == '')
		problems.push({field: 'city', error: 'Укажите Ваш <b>город</b>'})
		
	if(isNew)
	{
		var pass=$('#pass').val()
		var pass2=$('#pass2').val()
		if(pass == '')
			problems.push({field: 'pass', error: 'Вы не ввели <b>пароль</b>'})
		if(pass2 == '')
			problems.push({field: 'pass2', error: 'Вы не ввели <b>подтверждение пароля</b>'})
		if(pass != pass2 != '')
		{
			problems.push({field: 'pass', error: 'Пароли не совпадают!'})
			problems.push({field: 'pass2', error: ''})
		}
		
		if($('#captcha').val() == '')
			problems.push({field: 'captcha', error: 'Введите код с картинки!'})
		
	}
	
	if(problems.length > 0)
	{
		showErrors(problems)
	}
	else
	{	
		$('#edit-form .loading').css("display", "block")
		return true
	}
	
	return false
}




</script>
<?php $CONTENT->sectionHeader.=ob_get_clean();?>





<!--крамбсы-->
<? Core::renderPartial(SHARED_VIEWS_DIR.'/crumbs.php', $MODEL['crumbs']);?>
<!--//крамбсы-->


<?php 
if($user)
	Core::renderPartial('cabinet/menu.php'); 
?>


<div class="cabinet" >	

	<h2><?=($user ? 'РЕДАКТИРОВАНИЕ ЛИЧНЫХ ДАННЫХ' : 'РЕГИСТРАЦИЯ')?></h2>
	
		
		<form  id="edit-form" method="post" action="/cabinet/profile/profileEditSubmit" target="frame6" onsubmit="return checkProfileForm(<?=$user?'false':'true'?>); ">
			<?if($user){?>
			<input type="hidden" name="id" value="<?=$user->id?>">
			<?}?>
			
			
			<?php 
			if(!$user)
			{?>
				<div style="margin: 0 0 10px 13px ">Начните работу с сайтом <strong class="red">OSTATKI.KZ</strong>, пройдя несложную регистрацию. Это позволит Вам подавать в систему <b>свои объявления</b>, а также расширит возможности использования портала.<p>Итак, приступим! </div>
			<?php 
			}?>
			
			
			<div class="row fio">
				<div class="section">
					<span class="label">Фамилия:</span>
					<span class="input"><input type="text" name="surname" id="surname" placeholder="Фамилия" value="<?=$user->surname?>"></span>
				</div>
				<div class="section">
					<span class="label">Имя<i class="req">*</i>:</span>
					<span class="input"><input type="text" name="name" id="name" placeholder="Имя" value="<?=$user->name?>"></span>
				</div>
				<div class="section">
					<span class="label">Отчество:</span>
					<span class="input"><input type="text" name="fathername" id="fathername" placeholder="Отчество" value="<?=$user->fathername?>"></span>
				</div>
			</div>
			
				
				<hr>
				
				<!--<div class="row">
					<span class="label"><?=$_CONST['ДАТА РОЖДЕНИЯ']?>:</span>
					<span class="input"><?=Cabinet::dateOfBirthInput($u->birthdate)?></span>
				</div>-->
				
				
				<div class="row">
					<span class="label">Контакты<i class="req">*</i>:</span>
					<span class="input">
						<textarea name="phone" id="phone" cols="30" rows="10"><?=$user->phone?></textarea>
						<div class="hint">Пожалуйста, введите актуальные номера телефонов, <br/>другие пользователи будут пытаться с Вами связаться именно по ним.</div>
					</span>
				</div>
				
				<div class="row">
					<span class="label">Эл. почта<i class="req">*</i>:</span>
					<span class="input">
						<input type="text" name="email" id="email" placeholder="E-mail" value="<?=$user->email?>" autocomplete="off">
						<div class="hint">Этот e-mail будет Вашим логином на сайте. <br/>Все уведомления с сайта будут приходить именно на него. </div>
					</span>
				</div>
				
				<div class="row">
					<span class="label">Город<i class="req">*</i>:</span>
					<span class="input">
						<select name="city" id="city">
							<option value="">-выберите-</option>
						<?php 
						foreach($_GLOBALS['cities'] as $city)
						{?>
							<option value="<?=$city->id?>" <?=$city->id == $user->cityId ? ' selected="selected" ' : '' ?>  style="<?=$city->isLarge ? 'font-size: 17px; font-weight: bold; ' : ' font-size: 15px; '?>"><?=$city->name?></option>
						<?php 
						}?>
						</select>
					</span>
				</div>
				
		<?php 
		if(!$user)
		{?>
				<hr>
				<div class="passwords">
					<div class="row ">
						<span class="label">Пароль<i class="req">*</i>:</span>
						<span class="input"><input type="password" name="pass" id="pass" placeholder="Пароль" autocomplete="off"></span>
					</div>
					<div class="row">
						<span class="label">Ещё раз<i class="req">*</i>:</span>
						<span class="input"><input type="password" name="pass2" id="pass2" placeholder="Ещё раз.." autocomplete="off"></span>
					</div>
				</div>
				<hr>
				<div class="row">
					
					<table border="0">
						<tr>
							<td width="1" valign="top">
								<img src="/<?=INCLUDE_DIR?>/kcaptcha/?<?=session_name()?>=<?=session_id()?>" id="captcha-pic">
								<br><a href="javascript:void(0)" onclick="$('#captcha-pic').attr('src', '/<?=INCLUDE_DIR?>/kcaptcha/?'+(new Date()).getTime());" id="re-captcha">Не вижу код</a>
							</td>
							<td valign="top" >
								Введите текст на изображении<i class="req">*</i>: <br>
								<input type="text" name="captcha" id="captcha" >
							</td>
						</tr>
					</table>
				</div>
				
				<!--галочка условия-->
				<!--<div style="margin: 30px 0 0 0;" id="i-approve">
					<label ><input type="checkbox" name="agree" id="agree"><?=$_CONST['галочка Я ПОДТВЕРЖДАЮ']?></label>
				</div>-->
		<?php 
		}?>
				
				
	
	<p>
	<input type="submit" value="<?=$user ? 'СОХРАНИТЬ' : 'ЗАРЕГИСТРИРОВАТЬСЯ'?>">
	<span class="loading" style="display: none ;">Секунду...</span>
	<span class="info"></span>
	
		
	</form>
	
	
	
	<div class="success" style="display: none;">
	<? if(!$user)
	{?>
		<h2>Вы успешно зарегистрированы!</h2> 
		Остался ещё небольшой шаг! На указанный Вами ящик отправлено письмо с инструкциями по активации Вашего аккаунта. 
	<?php 
	}
	else
	{?>
	 	Ваши данные успешно изменены!
	<?php 
	}?>
	</div>
</div>



<iframe class="frame" name="frame6"  style="display: none; "></iframe>

