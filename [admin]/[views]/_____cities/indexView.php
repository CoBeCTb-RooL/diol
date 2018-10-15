<script>
$(document).ready(function(){
	citiesList()
	
	<?php 
	if($_REQUEST['cityId'])
	{?>
		edit(<?=$_REQUEST['cityId']?>)
	<?php 	
	}?>
});



function citiesList()
{
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/city/list/',
		data: '',
		beforeSend: function(){$.fancybox.showLoading(); },
		success: function(data){
			$('#cities-list').html(data)
		},
		error: function(){alert('Возникла ошибка...Попробуйте позже!')},
		complete: function(){ $.fancybox.hideLoading(); }
	});
}


function switchStatus(id)
{
	//alert(id); return
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/city/switchStatus',
		data: 'id='+id,
		dataType: 'json',
		beforeSend: function(){$.fancybox.showLoading()},
		success: function(data){
			if(data.error==''){
				$('#status-switcher-'+id).html(data.status.icon)
				$('#row-'+id).removeClass('status-<?=Status::ACTIVE?>').removeClass('status-<?=Status::INACTIVE?>').addClass('status-'+data.status.code)
			}
			else
				this.error(data.error)
		},
		error: function(e){alert(e=='' ? e : 'Возникла ошибка на сервере... Попробуйте позже.')},
		complete: function(){$.fancybox.hideLoading()}
	});
}

function edit(id)
{
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/city/edit',
		data: 'id='+id,
		beforeSend: function(){$.fancybox.showLoading()},
		success: function(data){
			$('#float').html(data)
			$.fancybox('#float');
		},
		error: function(e){alert(e=='' ? e : 'Возникла ошибка на сервере... Попробуйте позже.')},
		complete: function(){$.fancybox.hideLoading()}
	});
}

function saveCity()
{
	var form = $('#form')
	var isLarge = form.find('input[name=isLarge]').is(':checked');
	
	var errors = []
	var name = form.find('input[name=name]').val() 
	if(name=='')
		errors.push({"field": "name", error: 'Введите значение!'})
		
	if(errors.length>0){
		error(errors[0].error)
		return
	}

	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/city/editSubmit',
		data: 'id='+form.find('input[name=id]').val()+'&name='+encodeURIComponent(name)+'&isLarge='+(isLarge?1:0)+'',
		dataType: 'json',
		beforeSend: function(){$.fancybox.showLoading()},
		success: function(data){
			if(data.errors==null){
				citiesList()
				$.fancybox.close()
				notice('Сохранено!')
			}
			else
				error(data.errors[0].error)
		},
		error: function(e){alert('Возникла ошибка на сервере... Попробуйте позже.')},
		complete: function(){$.fancybox.hideLoading()}
	});
}

</script>


<?php Core::renderPartial('adv/menu.php');?>


<h1><i class="fa fa-globe"></i> Города</h1>

<div id="cities-list" class="users"></div>

<iframe name="frame1" style="display: none; width: 98%; border: 1px dashed #0e0e0e; height: 400px;">1</iframe>


<!--форма редактирования-->
<div id="float"  style="display: ; min-width: 700px; max-width: 700px;">!!</div>



<hr /><a href="?from_file">Из файла</a><p>


<?php 
if(isset($_REQUEST['from_file']))
{?> 
 <p>
 <a href="?from_file&grab_from_file=1">СПАРСИТЬ НОВЫЕ</a>
<?php 
}?>
