<script>
function list()
{
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/adv/article_numbers/list/',
		data: '',
		beforeSend: function(){$.fancybox.showLoading(); $('.article-numbers .inner').css('opacity', .3)},
		success: function(data){
			$('.article-numbers .inner').html(data)
		},
		error: function(){alert('Возникла ошибка...Попробуйте позже!')},
		complete: function(){$.fancybox.hideLoading(); $('.article-numbers .inner').css('opacity', 1)}
	});
}


function edit(id)
{
	if(typeof id == 'undefined') id = ''
	
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/adv/article_numbers/edit/',
		data: 'id='+id,
		beforeSend: function(){$.fancybox.showLoading(); },
		success: function(data){
			$('#float').html(data)
			$.fancybox('#float');
		},
		error: function(){alert('Возникла ошибка...Попробуйте позже!')},
		complete: function(){$.fancybox.hideLoading(); }
	});
}



function editSubmitStart()
{
	$('#edit-form input').removeClass('field-error');
	$('#edit-form .info').html('');
	$.fancybox.showLoading();
}
function editSubmitComplete(errors)
{
	$.fancybox.hideLoading();
	if(!errors)
	{
		notice("Сохранено")
		$.fancybox.close()
		list();
	}
	else
		showErrors(errors)
}


function switchBrandStatus(id)
{
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/adv/article_numbers/articleNumbersSwitchStatus',
		data: 'id='+id,
		dataType: 'json',
		beforeSend: function(){$.fancybox.showLoading(); },
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
		complete: function(){$.fancybox.hideLoading(); }
	});
}


function delete1(id)
{
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/adv/article_numbers/delete/',
		data: 'id='+id,
		dataType: 'json',
		beforeSend: function(){$.fancybox.showLoading(); $('.article-numbers .inner').css('opacity', .3); },
		success: function(data){
			if(!data.errors)
			{
				$('#row-'+id).fadeOut()
			}
			else
				showErrors(data.errors)
		},
		error: function(){alert('Возникла ошибка...Попробуйте позже!')},
		complete: function(){$.fancybox.hideLoading(); $('.article-numbers .inner').css('opacity', 1);}
	});	
}



function listSubmitStart()
{
	$.fancybox.showLoading();
	$('.article-numbers .inner').css('opacity', .3);
}
function listSubmitComplete(errors)
{
	$.fancybox.hideLoading();
	$('.article-numbers .inner').css('opacity', 1);
	if(!errors)
	{
		list()
		notice('Сохранено')
		$.fancybox.close();
	}
	else
		showErrors(errors)
}

</script>




<?php Core::renderPartial('adv/menu.php', $model);?>

<h1><i class="fa fa-list-ol"></i> База артикульных номеров</h1>

<div id="brands-list" class="article-numbers"> 	
	<div class="inner"></div>
</div>

<iframe name="frame7" style="display: none; width: 98%; border: 1px dashed #0e0e0e; height: 400px;">1</iframe>


<!--форма редактирования-->
<div id="float"  style="display: none; min-width: 700px; max-width: 700px;">!!</div>




<?php 
if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR))
{?>
<hr /><a href="?from_file">Из файла</a><p>
<?php 
}?>


<?php 
if(isset($_REQUEST['from_file']) )
{?>
 sdf sdf sdf 
 <p>
 <a href="?from_file&grab_from_file=1">СПАРСИТЬ НОВЫЕ</a>
<?php 
}?>



<?php 
if(!isset($_REQUEST['from_file']))
{?>
<script>
$(document).ready(function(){
	list()
})
</script>
<?php 
}?>