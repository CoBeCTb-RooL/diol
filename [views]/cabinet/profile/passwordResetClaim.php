<?php
//vd($MODEL);
?>

<script>
function sendClaim()
{
	$('#email-form .info').html('')
	$('#email-form *').removeClass('field-error')
	
	var errors = []
	var email = $('#email').val()
	
	if(email == "")
		errors.push({field: 'email', error: 'Введите Ваш e-mail!'})
	
	if(errors.length>0)
	{
		markErrors(errors, '#email-form', true)
		$('#email-form .info').html(errors[0].error)
			
	}
	else
	{
		$.ajax({
			url: "/cabinet/profile/passwordResetSendClaimAjax",
			data: 'email='+encodeURIComponent(email),
			type: "post",
			dataType: "json",
			beforeSend: function(){$('#email-form .loading').slideDown('fast'); $('#email-form .info').slideUp('fast')},
			success: function(data){
				if(data.errors.length==0){
					$('#email-form').slideUp();
					$('#success').slideDown();
				}
				else{
					markErrors(data.errors, '#email-form', true)
					$('#email-form .info').html(data.errors[0].error)
				}
			},
			error: function(){},
			complete: function(){$('#email-form .loading').slideUp('fast'); $('#email-form .info').slideDown('fast')}
		});
	}
}
</script>


<h1>Восстановление пароля</h1>




	<form action="" class="cabinet" id="email-form" onsubmit="sendClaim(); return false; ">
		
		<div class="txt">Если Вы забыли свой пароль, начните процедуру его восстановления. <br>Укажите электронный ящик, который Вы указали при регистрации:</div>
		
		<div class="row">
			<span class="label">Ваш e-mail<i class="req">*</i>:</span>
			<span class="input">
				<input type="email" name="email" id="email">
			</span>
		</div>
		
		<input type="submit" value="Начать восстановление" style="margin: 20px 0 0 10px; " />
		<div class="info"></div>
		<div class="loading" style="display: none; ">Секунду...</div>
		
	</form>
	
	<div id="success" style="display: none; ">
		На указанный ящик выслано письмо с инструкциями по восстановлению пароля!
	</div>
