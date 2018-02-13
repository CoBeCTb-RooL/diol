<div id="float-form-wrapper"  >
	<form name="form_float" id="float-form" method="post"  target="iframe1" action="/include/forms/forms.php" onsubmit="return Forms.FloatForm.check();" >
		<input type="hidden" name="action" value="floatForm">
		<h1 >Закажите обратный звонок</h1>
		<h2>и получите бесплатную консультацию<br> наших специалистов</h2>
		
		<input type="text" placeholder="Ваше имя" name="name" >
		<input type="text" placeholder="Ваш E-mail" name="email" >
		<input type="text" placeholder="Ваш телефон" name="phone" >
		
		
		<div class="loading" style="display: none; color: #000;" >Подождите...</div>
		<div class="info" style="display: none;">123</div>
		
		<input type="submit" class="yellow-btn"  value="ОТПРАВИТЬ ЗАПРОС">
	</form>
	
	<div id="float-form-success" style="display: none">
		<h1>Спасибо за заявку!</h1>
		<h2 class="center">В ближайшее время наши специалисты свяжутся с Вами!</h2>
	</div>
</div>	    