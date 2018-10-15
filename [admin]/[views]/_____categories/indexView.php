


<script>
	var CHOSEN_CAT = ''

		
	function list(id)
	{
		CHOSEN_CAT = id
		
		$.ajax({
			url: '/<?=ADMIN_URL_SIGN?>/categories/list',
			data: 'catId='+id,
			beforeSend: function(){$.fancybox.showLoading()},
			success: function(data){
				$('#cats').html(data)
			},
			error: function(e){},
			complete: function(){
				$("#cats").css("opacity", "1");
				
				$('#cats').slideDown('fast'); 
				$('#items').slideUp('fast')
				$.fancybox.hideLoading()
			}
		});
	} 



	function edit(id, currentCat)
	{
		$.fancybox.showLoading()
		
		$.ajax({
			url: '/<?=ADMIN_URL_SIGN?>/categories/edit',
			data: 'catId='+id+'&currentCat='+currentCat,
			beforeSend: function(){$.fancybox.showLoading()},
			success: function(data){
				$('#float').html(data)
				$.fancybox('#float');
			},
			error: function(e){alert('Возникла ошибка на сервере... Попробуйте позже.')},
			complete: function(){$.fancybox.hideLoading()}
		});
	}


	function editSubmitStart()
	{ 
		$.fancybox.showLoading()
	}
	function editSubmitComplete(data)
	{
		$.fancybox.hideLoading()
		if(!data.errors)
		{
			list(CHOSEN_CAT)
			$.fancybox.close()
			notice('Сохранено')
		}
		else
			showErrors(data.errors)
	}


	function listSubmitStart()
	{ 
		$.fancybox.showLoading()
		$('#cats').css('opacity', .4)
	}
	function listSubmitComplete(data)
	{
		$.fancybox.hideLoading()
		$('#cats').css('opacity', 1)
		if(!data.errors)
		{
			list(CHOSEN_CAT)
			$.fancybox.close()
			notice('Сохранено')
		}
		else
			showErrors(data.errors)
	}

	

	function switchStatus(id)
	{
		$.ajax({
			url: '/<?=ADMIN_URL_SIGN?>/categories/switchStatus',
			data: 'catId='+id,
			dataType: 'json',
			beforeSend: function(){$.fancybox.showLoading()},
			success: function(data){
				//alert(data.errors)
				if(!data.errors)
				{
					$('#status-switcher-'+id).html(data.status.icon)
					$('#cat-'+id).removeAttr('class').addClass('status-'+data.status.code)
					notice('Сохранено')
				}
				else
					showErrors(data.errors)
			},
			error: function(e){alert('Возникла ошибка на сервере... Попробуйте позже.')},
			complete: function(){$.fancybox.hideLoading()}
		});
	}



	function editUnits(id)
	{
		$.ajax({
			url: '/<?=ADMIN_URL_SIGN?>/categories/unitsList',
			data: 'id='+id,
			//dataType: 'json',
			beforeSend: function(){$.fancybox.showLoading()},
			success: function(data){
				$('#float2').html(data)
				$.fancybox('#float2');
			},
			error: function(e){alert('Возникла ошибка на сервере... Попробуйте позже.')},
			complete: function(){$.fancybox.hideLoading()}
		});
		//alert(id)
	}



	function unitClick(unitId, catId)
	{
		$.ajax({
			url: '/<?=ADMIN_URL_SIGN?>/categories/unitClick',
			data: 'unitId='+encodeURIComponent(unitId)+'&catId='+encodeURIComponent(catId)+'&checked='+ encodeURIComponent($('#unit-'+unitId).is(':checked') ? 1:0),
			dataType: 'json',
			beforeSend: function(){$.fancybox.showLoading()},
			success: function(data){
				if(!data.errors)
				{
					if(data.checked)
						$('#unit-'+unitId).attr('checked', 'checked')
					else
						$('#unit-'+unitId).removeAttr('checked')
					
					var uw = $('#cats-list-units-wrapper-'+data.cat.id).html('')
					for(var i in data.cat.productVolumeUnits)
					{
						var u = data.cat.productVolumeUnits[i];
						uw.append('<div class="unit-item unit-status-'+u.status.code+'"> - '+u.name+'</div>')
					}
				}
				else 
					showErrors(data.errors)
			},
			error: function(e){alert('Возникла ошибка на сервере... Попробуйте позже.')},
			complete: function(){$.fancybox.hideLoading()}
		});
	}

	


	$(document).ready(function(){
		list(<?=$_REQUEST['catId']?>)
	});
</script>



<?php Core::renderPartial('adv/menu.php', $model);?>


<div id="cats">Загрузка....</div>
<div id="items" style="display: none; "> Загрузка....</div>




<!--форма редактирования-->
<div id="float" class="view " style="min-width: 1000px; max-width: 1200px;" >!!</div>
<div id="float2" class="view " style="" ></div>
	
	