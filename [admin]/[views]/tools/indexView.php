<?php Core::renderPartial('adv/menu.php');?>

<script>
advsCountRecache = function()
{
	//alert(123)
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/tools/recache',
		data: '',
		dataType: 'json',
		beforeSend: function(){$('#recache-form .loading').slideDown('fast'); $('#recache-form .info').slideUp('fast'); },
		success: function(data){
			if(data.error=='')
			{
				$('#recache-form').slideUp('fast');
				//$('#recache-form .info').html('Готово!').slideDown();
				$('#recache-form + .success').slideDown('fast');
			}
			else
			{
				$('#recache-form .info').html(data.error).slideDown('fast');
			}
		},
		error: function(e){alert('Возникла ошибка на сервере... Попробуйте позже.')},
		complete: function(){$('#recache-form .loading').slideUp('fast'); $('#recache-form .info').slideDown('fast'); }
	});
}
</script>



<div class="col">
	<h1>Количество объявлений по категориям</h1>
	
	<fieldset style="width: 500px;">
		<legend style="font-weight: bold; ">Сбросить кэш?</legend>
		<b>Количество объявлений по категориям - </b>число в скобках возле каждой категории в разделе ОБЪЯВЛЕНИЯ. Сброс кэша количества объявлений по категориям пересчитает и сохранит актуальное количество.
		
		<form action="" id="recache-form" onsubmit="if(confirm('Сбросить кэш?')){advsCountRecache()}; return false; ">
			<p><input type="submit" onclick="" value="СБРОСИТЬ КЭШ" />	
			<div class="loading" style="display: none; ">Секунду...</div>
			<div class="info"></div>
		</form>
		<div class="success" style="display: none; font-weight: bold; ">Готово!</div>
	</fieldset>
</div>




