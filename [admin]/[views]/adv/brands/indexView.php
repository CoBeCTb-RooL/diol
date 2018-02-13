<?php Core::renderPartial('adv/menu.php', $MODEL);?>

<h1><i class="fa fa-cc-stripe"></i> Бренды</h1>



<script>
function list()
{
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/adv/brands/list',
		data: '',
		beforeSend: function(){$.fancybox.showLoading(); },
		success: function(data){
			$('#brands-list .inner').html(data)
		},
		error: function(){alert('Возникла ошибка...Попробуйте позже!')},
		complete: function(){ $.fancybox.hideLoading(); }
	});
}



function edit(id)
{
	if(typeof id == 'undefined') id = '' 
	
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/adv/brands/edit/',
		data: 'id='+id,
		beforeSend: function(){$.fancybox.showLoading(); },
		success: function(data){
			$('#float').html(data)
			$.fancybox('#float');
		},
		error: function(){alert('Возникла ошибка...Попробуйте позже!')},
		complete: function(){$.fancybox.hideLoading();}
	});
}


function editSubmitStart()
{
	$.fancybox.showLoading()
}

function editSubmitComplete(errors)
{
	$.fancybox.hideLoading()
	if(!errors)
	{
		$.fancybox.close();
		list();
		notice("Сохранено")
	}
	else
		showErrors(errors)
}


function delete1(id)
{
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/adv/brands/delete/',
		data: 'id='+id,
		dataType: 'json',
		beforeSend: function(){$.fancybox.showLoading(); $('.brands .inner').css('opacity', .3); },
		success: function(data){
			if(!data.errors)
			{
				$('#row-'+id).fadeOut()
			}
			else
				showErrors(data.errors)
		},
		error: function(){alert('Возникла ошибка...Попробуйте позже!')},
		complete: function(){$.fancybox.hideLoading(); $('.brands .inner').css('opacity', 1); }
	});	
}


function listSubmitStart()
{
	$.fancybox.showLoading()
	$('.brands .inner').css('opacity', .3);
}
function listSubmitComplete(errors)
{
	$.fancybox.hideLoading()
	$('.brands .inner').css('opacity', 1);
	if(!errors)
	{
		list();
		notice("Сохранено")
	}
	else
		showErrors(errors)
}


function switchBrandStatus(id)
{
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/adv/brands/switchBrandStatus',
		data: 'brandId='+id,
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


<div id="brands-list" class="brands"> 	
	<div class="inner"></div>
	<div class="loading" style="visibility: hidden; "> <img src="/<?=ADMIN_DIR?>/img/tree-loading.gif" > </div>
</div>

<iframe name="frame7" style="display: none; width: 98%; border: 1px dashed #0e0e0e; height: 400px;">1</iframe>


<!--форма редактирования-->
<div id="float"  style="display: none; min-width: 700px; max-width: 700px;">!!</div>

<script>
$(document).ready(function(){
	list()
})
</script>