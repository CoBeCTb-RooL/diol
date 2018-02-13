<script>
	var CHOSEN_CAT = ''

		
	function cat(id)
	{
		CHOSEN_CAT = id
		
		$.ajax({
			url: '/'+Slonne.ADMIN_URL_SIGN+'/adv/cats/list',
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



	function catEdit(id, currentCat)
	{
		$.fancybox.showLoading()
		
		$.ajax({
			url: '/'+Slonne.ADMIN_URL_SIGN+'/adv/cats/edit',
			data: 'catId='+id+'&currentCat='+currentCat,
			success: function(data){
				$('#float').html(data)
				$.fancybox('#float');
			},
			error: function(e){alert(e=='' ? e : 'Возникла ошибка на сервере... Попробуйте позже.')},
			complete: function(){}
		});
	}



	function catEditCheck()
	{
		catEditSubmitStart()
		return true;
	}
	function catEditSubmitStart()
	{
		$('#cat-edit-form .loading').slideDown('fast'); 
	}
	function catEditSubmitComplete(data)
	{
		$('#cat-edit-form .loading').slideUp('fast');
		if(data.errors==null)
		{
			cat(CHOSEN_CAT)
			$.fancybox.close()
			notice('Сохранено')
		}
		else
		{
			markErrors(data.errors, '#cat-edit-form', true)
			error(data.errors[0].error)
		}
	}




	$(document).ready(function(){
		cat(<?=$_REQUEST['catId']?>)
	});
</script>



<?php Core::renderPartial('adv/menu.php', $model);?>


<div id="cats">Загрузка....</div>
<div id="items" style="display: none; "> Загрузка....</div>




<!--форма редактирования-->
<div id="float" class="view " style="min-width: 1000px; max-width: 1200px;" >!!</div>
	
	