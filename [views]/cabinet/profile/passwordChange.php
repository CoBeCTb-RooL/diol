<?php
//vd($MODEL);
$user = $MODEL['user']; 
?>



<!--крамбсы-->
<? Core::renderPartial(SHARED_VIEWS_DIR.'/crumbs.php', $MODEL['crumbs']);?>
<!--//крамбсы-->

<?php 
if($user)
	Core::renderPartial('cabinet/menu.php'); 
?>




<script>
function savePass()
{
	//alert(123)
	$('#pass-form .info').html('')
	$('#pass-form *').removeClass('field-error')
	
	
	var errors = []

	var oldPass = $('#old_pass').val()
	var pass = $('#pass').val()
	var pass2 = $('#pass2').val()

	if(oldPass == "")
		errors.push({field: 'old_pass', error: 'Введите старый пароль!'})
	if(pass == "")
		errors.push({field: 'pass', error: 'Введите новый пароль!'})
	if(pass2 == "")
		errors.push({field: 'pass2', error: 'Подтвердите новый пароль!'})
	if(pass!='' && pass2!='' && pass!=pass2)
	{
		errors.push({field: 'pass', error: 'Новый пароль и подтверждение не совпадают!'})
		errors.push({field: 'pass2', error: ''})
	}

	if(errors.length>0)
	{
		markErrors(errors, '#pass-form', true)
		$('#pass-form .info').html(errors[0].error)
	}
	else
	{
		$.ajax({
			url: "/cabinet/profile/passwordChangeSubmit",
			data: 'old_pass='+encodeURIComponent(oldPass)+'&pass='+encodeURIComponent(pass)+'&pass2='+encodeURIComponent(pass2),
			type: "post",
			dataType: "json",
			beforeSend: function(){$('#pass-form .loading').slideDown('fast'); $('#pass-form .info').slideUp('fast')},
			success: function(data){
				if(data.errors.length==0){
					$('#pass-form').slideUp();
					$('#success').slideDown();
				}
				else{
					markErrors(data.errors, '#pass-form', true)
					$('#pass-form .info').html(data.errors[0].error)
				}
			},
			error: function(){},
			complete: function(){$('#pass-form .loading').slideUp('fast'); $('#pass-form .info').slideDown('fast')}
		});
	}
}
</script>


<h2>Смена пароля</h2>



<?php 
if($user )
{?>
	<form action="/<?=LANG?>/cabinet/profile/passwordChangeSubmit" class="cabinet" id="pass-form" onsubmit="savePass(); return false; ">
		<div class="row">
			<span class="label">Старый пароль<i class="req">*</i>:</span>
			<span class="input">
				<input type="password" name="old_pass" id="old_pass">
			</span>
		</div>
		
		<div class="row" style="margin: 25px 0 0 0; ">
			<span class="label">Новый пароль<i class="req">*</i>:</span>
			<span class="input">
				<input type="password" name="pass" id="pass">
			</span>
		</div>
		<div class="row" style="margin: 4px 0 0 0 ;">
			<span class="label">Ещё раз<i class="req">*</i>:</span>
			<span class="input">
				<input type="password" name="pass2" id="pass2">
			</span>
		</div>
		
		<input type="submit" value="Изменить пароль" style="margin: 20px 0 0 10px; " />
		<div class="info"></div>
		<div class="loading" style="display: none; ">Секунду...</div>
		
	</form>
	
	<div id="success" style="display: none; ">
		Пароль успешно изменён.
	</div>
<?php 
}
else
{?>
	Вы не авторизованы.
<?php 	
}?>