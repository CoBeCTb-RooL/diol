<script>
	function propsList()
	{
		$.ajax({
			url: '/<?=ADMIN_URL_SIGN?>/props/list',
			data: '',
			beforeSend: function(){$.fancybox.showLoading()},
			success: function(data){
				$('#props').html(data)
			},
			error: function(){alert('Возникла ошибка...Попробуйте позже!')},
			complete: function(){$.fancybox.hideLoading()}
		});
	}




	function propsEdit(id)
	{
		$.ajax({
			url: '/<?=ADMIN_URL_SIGN?>/props/edit',
			data: 'propId='+id,
			beforeSend: function(){$.fancybox.showLoading()},
			success: function(data){
				$('#float').html(data)
				$.fancybox('#float');
			},
			error: function(e){alert('Возникла ошибка на сервере... Попробуйте позже.')},
			complete: function(){$.fancybox.hideLoading()}
		});
	}


	function propEditSubmitStart()
	{
		$('#prop-edit-form *').removeClass('field-error')
		$.fancybox.showLoading() 
	}
	function propEditSubmitComplete(data)
	{
		$.fancybox.hideLoading()
		if(!data.errors)
		{
			propsList()
			$.fancybox.close()
			notice('Сохранено')
		}
		else
		{
			showErrors(data.errors)
		}
	}


	function switchPropStatus(id)
	{
		$.ajax({
			url: '/<?=ADMIN_URL_SIGN?>/props/switchStatus',
			data: 'propId='+id,
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
			error: function(e){alert( 'Возникла ошибка на сервере... Попробуйте позже.')},
			complete: function(){$.fancybox.hideLoading()}
		});
	}


	function deleteProp(id)
	{
		$.ajax({
			url: '/<?=ADMIN_URL_SIGN?>/props/propsDelete/',
			data: 'id='+id,
			dataType: 'json',
			beforeSend: function(){$.fancybox.showLoading()},
			success: function(data){
				if(!data.errors)
				{
					//$('#row-'+id).remove()
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



	function switchColumnStatus(id)
	{
		$.ajax({
			url: '/<?=ADMIN_URL_SIGN?>/props/switchTableColumnStatus',
			data: 'columnId='+id,
			dataType: 'json',
			beforeSend: function(){$.fancybox.showLoading()},
			success: function(data){
				if(!data.errors)
				{
					$('#column-status-btn-'+id).html(data.status.icon+' '+data.status.name)
					$('#table-column-'+id).removeAttr('class').addClass('table-column-status-'+data.status.num)
				}
				else
					showErrors(data.errors)
			},
			error: function(e){alert('Возникла ошибка на сервере... Попробуйте позже.')},
			complete: function(){$.fancybox.hideLoading()}
		});
	}


	function deleteColumn(id)
	{
		$.ajax({
			url: '/<?=ADMIN_URL_SIGN?>/props/columnDelete',
			data: 'id='+id,
			dataType: 'json',
			beforeSend: function(){$.fancybox.showLoading()},
			success: function(data){
				if(!data.errors)
				{
					$('#table-column-'+id).fadeOut()
				}
				else
					showErrors(data.errors)
			},
			error: function(e){alert('Возникла ошибка на сервере... Попробуйте позже.')},
			complete: function(){$.fancybox.hideLoading()}
		});
		
	}


	function propsOptionDelete(optId)
	{
		$.ajax({
			url: '/<?=ADMIN_URL_SIGN?>/props/optionDelete/',
			data: 'id='+optId,
			dataType: 'json',
			beforeSend: function(){$.fancybox.showLoading()},
			success: function(data){
				if(!data.errors)
				{
					notice('Опция удалена!')
					$('#opt-row-'+optId).fadeOut()
				}
				else
					showErrors(data.errors)
			},
			error: function(){alert('Возникла ошибка...Попробуйте позже!')},
			complete: function(){$.fancybox.hideLoading()}
		});	
	}



// 	добавить ещё поле для таблицы (тип свойств - ТАБЛИЦА)
	colsCount=0
	function typeTableAppendRow(operation, object)
	{
		//alert(123)
		var id=''
		var name = ''
		var idx = ++colsCount*10
		var status=''
			
		var statusWrapperHTML = '';
		
		if(typeof object!='undefined')
		{
			id=object.id
			name=object.name
			//idx=object.idx
			status=object.status.num

			if(object.status.num == <?=Status::code(Status::ACTIVE)->num?>)
				statusWrapperHTML = '<i class="fa fa-toggle-on"></i> <?=Status::code(Status::ACTIVE)->name?>' 
			if(object.status.num == <?=Status::code(Status::INACTIVE)->num?>)
				statusWrapperHTML = '<i class="fa fa-toggle-off"></i> <?=Status::code(Status::INACTIVE)->name?>' 
		}
		
		var str=$('#tmpl-add-column').html()
		
		str=str.replace(/_NUM_/g, colsCount)
		str=str.replace(/_OPERATION_/g, operation)
		
		str=str.replace(/_ID_/g, id)
		str=str.replace(/_NAME_/g, name)
		str=str.replace(/_IDX_/g, idx)
		str=str.replace(/_STATUS_/g, status)
		str=str.replace(/_STATUSHTML_/g, statusWrapperHTML)
		

		$('#columns-wrapper').append(str)
	}



	function changeFieldType(type)
	{
		$('.dop-info').slideUp()
		if(type != '')
			$('.dop-info.'+type+'').slideDown();
	}


	function propsOptionEdit(optId)
	{
		var val=$('#opt-'+optId+'-input input').val()
		//alert(val);
		$.ajax({
			url: '/<?=ADMIN_URL_SIGN?>/props/optionValueSubmit/',
			data: 'id='+optId+'&val='+encodeURIComponent(val),
			dataType: 'json',
			beforeSend: function(){$.fancybox.showLoading()},
			success: function(data){
				if(!data.errors)
				{
					notice('Сохранено!')
					$('#opt-'+optId+'-value').slideDown().html(val); 
					$('#opt-'+optId+'-input').slideUp();
				}
				else
					showErrors(data.errors)
			},
			error: function(){alert('Возникла ошибка...Попробуйте позже!')},
			complete: function(){$.fancybox.hideLoading()}
		});	
	}
</script>




<?php Core::renderPartial('adv/menu.php', $model);?>



<div id="props">Загрузка....</div>




<!--форма редактирования-->
<div id="float" class="view " style="min-width: 1000px; max-width: 1200px;" >!!</div>

<script>
$(document).ready(function(){
	//Slonne.Catalog.Props.propsList();
	propsList();
	<?php
	if($id=$_REQUEST['propId'])
	{?>
		propsEdit(<?=$id?>);
	<?php 	
	}?>
})
</script>