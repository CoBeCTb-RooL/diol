
<script>
function groupsList()
{
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/adminGroup/list2/',
		data: '',
		beforeSend: function(){$.fancybox.showLoading(); $('.admins .inner').css('opacity', .3); },
		success: function(data){
			$('.admins .inner').html(data)
		},
		error: function(){alert('Возникла ошибка...Попробуйте позже!')},
		complete: function(){$.fancybox.hideLoading(); $('.admins .inner').css('opacity', 1);}
	});
}

function groupsSwitchStatus(id)
{
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/adminGroup/switchStatus',
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



function groupsEdit(id)
{
	if(typeof id == 'undefined') id = ''
	
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/adminGroup/edit2/',
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



function groupsEditSubmitStart()
{
	$('#edit-form input').removeClass('field-error')
	$.fancybox.showLoading()
	//$('#edit-form .info').html('')
}
function groupsEditSubmitComplete(errors)
{
$.fancybox.hideLoading()
	
	if(errors)
		showErrors(errors)
	else
	{
		$.fancybox.close()
		notice('Сохранено!')
		groupsList()
	}
}


function groupsDelete(id)
{
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/adminGroup/setStatus/',
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




<h1><span class="fa fa-users "></span> Группы администраторов</h1>


<div class="admins"> 
	<!-- <div class="switcher">
		<a href="#admins" onclick="Slonne.Admins.list(); return false; ">Администраторы</a>
		<a href="#admin_groups" onclick="Slonne.AdminGroups.list();return false; ">Группы</a>
		<a href="/<?=ADMIN_URL_SIGN?>/adminGroup" >Группы 2.0</a>
	</div>-->
	
	<? Core::renderPartial('admins/menu.php', $MODEL=array('sectionActive'=>'groups'));?>
		
	<div class="inner"></div>
</div>

<!--форма редактирования-->
<div id="float"  style="display: none; min-width: 700px; max-width: 700px;">!!</div>

<script>
$(document).ready(function(){
	groupsList()
	<?php 
	if($_REQUEST['id'])
	{?>
		groupsEdit(<?=$_REQUEST['id']?>)
	<?php 	
	}?>
	
})
</script>