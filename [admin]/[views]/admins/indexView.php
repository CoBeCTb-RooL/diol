<script>
function adminsList()
{
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/admin/list/',
		data: '',
		beforeSend: function(){$.fancybox.showLoading(); $('.admins .inner').css('opacity', .3); },
		success: function(data){
			$('.admins .inner').html(data)
		},
		error: function(){alert('Возникла ошибка...Попробуйте позже!')},
		complete: function(){$.fancybox.hideLoading(); $('.admins .inner').css('opacity', 1);}
	});
}


function adminsSwitchStatus(id)
{
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/admin/switchStatus',
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
				error(data.errors[0].error)
		},
		error: function(e){alert('Возникла ошибка на сервере... Попробуйте позже.')},
		complete: function(){$.fancybox.hideLoading()}
	});
}


function adminsEdit(id)
{
	if(typeof id == 'undefined') id = ''
	
	$.fancybox.showLoading()
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/admin/edit/',
		data: 'id='+id,
		beforeSend: function(){$.fancybox.showLoading()},
		success: function(data){
			$('#float').html(data)
			$.fancybox('#float');
		},
		error: function(){alert('Возникла ошибка...Попробуйте позже!')},
		complete: function(){$.fancybox.hideLoading()}
	});
}


function adminsEditSubmitStart()
{
	$('#edit-form *').removeClass('field-error')
	$.fancybox.showLoading()
	//$('#edit-form .info').html('')
}
function adminsEditSubmitComplete(errors)
{
	$.fancybox.hideLoading()
	
	if(errors)
		showErrors(errors)
	else
	{
		$.fancybox.close()
		notice('Сохранено!')
		adminsList()
	}
}


function adminsDelete(id)
{
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/admin/setStatus/',
		data: 'id='+id+'&status=<?=Status::DELETED?>',
		dataType: 'json',
		beforeSend: function(){$.fancybox.showLoading()},
		success: function(data){
			if(!data.errors)
				$('#row-'+data.item.id+'').fadeOut();
			else
				showErrors(data.errors)
		},
		error: function(){alert('Возникла ошибка...Попробуйте позже!')},
		complete: function(){$.fancybox.hideLoading()}
	});
}
</script>



<h1><?=$_GLOBALS['CURRENT_MODULE']->icon?> Администраторы</h1>



<div id="admins-list" class="admins"> 
	
	<? Core::renderPartial('admins/menu.php', $MODEL=array('sectionActive'=>'admins'));?>
		
	<div class="inner"></div>
	<div class="loading" style="visibility: hidden; "> <img src="/<?=ADMIN_DIR?>/img/tree-loading.gif" > </div>
</div>

<!--форма редактирования-->
<div id="float"  style="display: none; min-width: 700px; max-width: 700px;">!!</div>

<script>
$(document).ready(function(){
	adminsList()
	
})
</script>