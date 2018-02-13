

<script>
function list()
{
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/essence/list/',
		data: '',
		beforeSend: function(){$.fancybox.showLoading(); $('.essences .inner').css('opacity', '.3')},
		success: function(data){
			$('.essences .inner').html(data)
		},
		error: function(){alert('Возникла ошибка...Попробуйте позже!')},
		complete: function(){$.fancybox.hideLoading(); $('.essences .inner').css('opacity', '1') }
	});
}


function edit(id)
{
	if(typeof id == 'undefined') id = ''
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/essence/edit/',
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


function essenceEditSubmitStart()
{
	$('#edit-form *').removeClass('field-error')
	$.fancybox.showLoading()
	//$('#edit-form .info').html('')
}
function essenceEditSubmitComplete(errors)
{
	$.fancybox.hideLoading()
	
	if(errors)
		showErrors(errors)
	else
	{
		$.fancybox.close()
		notice('Сохранено!')
		list()
	}
}


function delete1(id)
{
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/essence/delete/',
		data: 'id='+id,
		dataType: 'json',
		beforeSend: function(){$.fancybox.showLoading(); $('.essences .inner').css('opacity', .3); },
		success: function(data){
			if(!data.errors)
			{
				$('#row-'+id).fadeOut()
				notice('Удалено!')
			}
			else
				showErrors(data.errors)
		},
		error: function(){alert('Возникла ошибка...Попробуйте позже!')},
		complete: function(){$.fancybox.hideLoading(); $('.essences .inner').css('opacity', 1); }
	});	
}

</script>



<div class="essences"> 
	<h1><i class="fa fa-puzzle-piece"></i> Сущности</h1>
	<div class="inner"></div>
	<div class="loading" style="visibility: hidden; "> <img src="/<?=ADMIN_DIR?>/img/tree-loading.gif" > </div>
</div>


<div class="fields"> 
	<div class="inner"></div>
	<div class="loading" style="visibility: hidden; "> <img src="/<?=ADMIN_DIR?>/img/tree-loading.gif" > </div>
</div>
<div class="clear"></div>

<!--форма редактирования-->
<div id="float"  style="display: none; min-width: 450px; max-width: 700px;">!!</div>

<script>
$(document).ready(function(){
	list()
})
</script>