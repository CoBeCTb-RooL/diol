<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="/<?=ADMIN_DIR?>/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/<?=ADMIN_DIR?>/favicon.ico" type="image/x-icon">
	<meta charset="utf-8">
	
	<title><?=$_GLOBALS['TITLE']?></title>
	
	<meta name="description" content="" />
	<meta name="keywords" content="" />

	<script type="text/javascript" src="/js/libs/jquery-1.11.0.min.js"></script>
	<script src="/js/jquery-ui-1.9.2.custom.min.js" type="text/javascript"></script>
	
	<link href="/css/font-awesome-4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	
	<link rel="stylesheet/less" type="text/css" href="/<?=ADMIN_DIR?>/css/style.less" />
	
	<script src="/<?=ADMIN_DIR?>/js/slonne.js" type="text/javascript"></script>
	<script src="/<?=ADMIN_DIR?>/js/slonne.admins.js" type="text/javascript"></script>
	
	
	<!--LESS-->
	<script src="/js/libs/less/less-1.7.3.min.js" type="text/javascript"></script>
	
	<!--stickr-->
	<script src="/js/plugins/jquery.stickr.js" type="text/javascript"></script>
	
	
	<!--стандартные js Slonne-->
	<script type="text/javascript" src="/js/common.js"></script>
	
	
	
	<!--Шрифт OPEN SANS-->
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:600,300&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=PT+Sans:400,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Mystery+Quest' rel='stylesheet' type='text/css'>


</head>

	
	
	<script>
	function auth()
	{
		var email = $('#auth-form input[name=email]').val()
		var password = $('#auth-form input[name=password]').val()

		var errors = []
		if(email == '' )
			errors.push({field:'email'}) 
		if(password == '' )
			errors.push({field:'password'}) 
		
		if(errors.length > 0)
		{
			for(var i in errors)
			{
				highlight('auth-form input[name='+errors[i].field+']')
			}
			error('Пожалуйста, заполните все поля! ')
			return;
		}
		$.ajax({
			url: '/<?=ADMIN_URL_SIGN?>/auth/auth',
			data: 'email='+email+'&password='+password,
			dataType: 'json',
			beforeSend: function(){
				$('#auth-form .loading').css('display', 'block');
			},
			success: function(data){
				if(data.error == '')
				{
					//alert('ew')
					if(data.result == 'ok')
					{
						notice('Ок!')
						highlight('auth-form input[name=email]', true)
						highlight('auth-form input[name=password]', true)
						location.href='/<?=ADMIN_URL_SIGN?>'
					}
					
					if(data.result == 'not_found')
					{
						highlight('auth-form input[name=email]')
						highlight('auth-form input[name=password]')
						error('Неверный e-mail / пароль!')
					}
					
					if(data.result == 'tries_limit')
					{
						highlight('auth-form input[name=email]')
						highlight('auth-form input[name=password]')
						error('Превышен лимит попыток! Повторите через <b>'+data.delay+'</b>')
					}
					
					
				}
				else
					error(data.error)
			},
			error: function(){alert('Возникла ошибка...Попробуйте позже!')},
			complete: function(){
				$('#auth-form .loading').slideUp('fast');
			}
		});	
	}
	</script>
	
	
	
	
	
<body >
	
	<div class="auth">
		<div class="wrapper">
			<div  class="mystery">
				<img style="margin: 0 10px -20px 0;" src="/<?=ADMIN_DIR?>/img/slonik.gif" width="70" alt="" > 
				<span style="font-size: 32px;" >SLoNNe CMS</span>
			</div>
			
			<form  id="auth-form" method="post" action="" onsubmit="auth();  return false; ">
				<div class="inputs-wrapper">
					<span class="label">E-mail: </span><input type="text" name="email" >
					<br>
					<span class="label">Пароль: </span><input type="password" name="password">
				</div>
				<button type="submit" ><i class="fa fa-sign-in"></i></button>
				<div class="loading" style="display: none; ">Загрузка....</div>
				<div class="clear"></div>
				<iframe  name="login_form_iframe" style=" display: none; border: 1px solid black; width: 600px ; height: 600px;"></iframe >
			</form>
			
			<div style="margin-left: 170px; font-size: 12px;" id="login-form-idea-label" class="mystery">проект <span style="font-size: 15px;">"Hanna Zuckerbrod"</span></div>
		</div>
	</div>
		<?=$_GLOBALS['CONTENT']?>
</body>


<script>
$(document).ready(function(){
	$('#auth-form input[name=email]').focus()
})
</script>

</html>