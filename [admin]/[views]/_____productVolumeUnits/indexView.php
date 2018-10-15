<script>
$(document).ready(function(){
	unitsList()	
});



function unitsList()
{
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/product_volume_unit/list/',
		data: '',
		beforeSend: function(){$.fancybox.showLoading(); },
		success: function(data){
			$('#units-list').html(data)
		},
		error: function(){alert('Возникла ошибка...Попробуйте позже!')},
		complete: function(){ $.fancybox.hideLoading(); }
	});
}


function edit(id)
{
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/product_volume_unit/edit',
		data: 'id='+id,
		beforeSend: function(){$.fancybox.showLoading()},
		success: function(data){
			$('#float').html(data)
			$.fancybox('#float');
		},
		error: function(e){alert('Возникла ошибка на сервере... Попробуйте позже.')},
		complete: function(){$.fancybox.hideLoading()}
	});
}



function editSubmit()
{
	var form = $('#form')
	//alert(form.find('input[name=name]').val())

	var errors = []
	var name = form.find('input[name=name]').val() 
	if(name=='')
		errors.push({"field": "name", error: 'Введите значение!'})
		
	if(errors.length>0)
	{
		showErrors(errors)
		return
	}

	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/product_volume_unit/editSubmit',
		data: 'id='+form.find('input[name=id]').val()+'&name='+encodeURIComponent(name)+'',
		dataType: 'json',
		beforeSend: function(){$.fancybox.showLoading()},
		success: function(data){
			if(!data.errors){
				unitsList()
				$.fancybox.close()
				notice('Сохранено!')
			}
			else
				showErrors(data.errors)
		},
		error: function(e){alert('Возникла ошибка на сервере... Попробуйте позже.')},
		complete: function(){$.fancybox.hideLoading()}
	});
}



function switchStatus(id)
{
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/product_volume_unit/switchStatus',
		data: 'id='+id,
		dataType: 'json',
		beforeSend: function(){$.fancybox.showLoading()},
		success: function(data){
			if(!data.errors)
			{
				$('#status-switcher-'+id).html(data.status.icon)
				$('#row-'+id).removeAttr('class').addClass('status-'+data.status.code)
			}
			else
				showErrors(data.errors)
		},
		error: function(e){alert('Возникла ошибка на сервере... Попробуйте позже.')},
		complete: function(){$.fancybox.hideLoading()}
	});
}


</script>


<?php Core::renderPartial('adv/menu.php');?>


<h1><i class="fa fa-balance-scale"></i> Единицы объёмов товара</h1>

<div id="units-list" class="users"></div>

<iframe name="frame1" style="display: none; width: 98%; border: 1px dashed #0e0e0e; height: 400px;">1</iframe>


<!--форма редактирования-->
<div id="float"  style="display: ; min-width: 700px; max-width: 700px;">!!</div>
