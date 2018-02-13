<script>
	function classesList()
	{
		$.ajax({
			url: '/<?=ADMIN_URL_SIGN?>/classes/list',
			data: '',
			beforeSend: function(){$.fancybox.showLoading()},
			success: function(data){
				$('#classes').html(data)
			},
			error: function(){alert('Возникла ошибка...Попробуйте позже!')},
			complete: function(){$.fancybox.hideLoading()}
		});
	}




	function classesEdit(id)
	{
		$.ajax({
			url: '/<?=ADMIN_URL_SIGN?>/classes/edit',
			data: 'classId='+id,
			beforeSend: function(){$.fancybox.showLoading()},
			success: function(data){
				$('#float').html(data)
				$.fancybox('#float');
			},
			error: function(e){alert('Возникла ошибка на сервере... Попробуйте позже.')},
			complete: function(){$.fancybox.hideLoading()}
		});
	}


	function classEditSubmitStart()
	{
		$('#class-edit-form *').removeClass('field-error')
		$.fancybox.showLoading()
	}
	function classEditSubmitComplete(data)
	{
		$.fancybox.hideLoading()
		if(!data.errors)
		{
			classesList()
			$.fancybox.close()
			notice('Сохранено')
		}
		else
			showErrors(data.errors)
	}


	function switchStatus(id)
	{
		$.ajax({
			url: '/<?=ADMIN_URL_SIGN?>/classes/switchStatus',
			data: 'classId='+id,
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


	function deleteClass(id)
	{
		$.ajax({
			url: '/<?=ADMIN_URL_SIGN?>/classes/deleteClass/',
			data: 'id='+id,
			dataType: 'json',
			beforeSend: function(){$.fancybox.showLoading()},
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
			complete: function(){$.fancybox.hideLoading()}
		});	
	}


	function listSaveChangesStart()
	{
		$.fancybox.showLoading()
	}
	function listSaveChangesComplete(errors)
	{
		$.fancybox.hideLoading()
		if(!errors)
		{
			notice('Изменения сохранены')
			classesList()
		}
		else
			showErrors(errors)
	}
	
</script>



<?php Core::renderPartial('adv/menu.php', $model);?>


<div id="classes">Загрузка....</div>


<!--форма редактирования-->
<div id="float" class="view " style="min-width: 1000px; max-width: 1200px;" >!!</div>


<script>
$(document).ready(function(){
	classesList();
	<?php
	if($id=$_REQUEST['id'])
	{?>
		classesEdit(<?=$id?>);
	<?php 	
	}?>
})
</script>