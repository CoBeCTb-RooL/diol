<?php
$u = $MODEL['user'];
//vd($u);
?>


<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>


<style>
.row1{margin: 0 0 6px 0; }
.row1 .label{width: 160px; text-align: right; display: inline-block; padding: 0 10px 0 0 ; }
.row1 b{}
.status{font-size: 17px; }
.user-status-<?=Status::ACTIVE?>{color: green;}
.user-status-<?=Status::INACTIVE?>{opacity: .4; }

.status{display: none; }
.current-status-<?=Status::ACTIVE?> .user-status-<?=Status::ACTIVE?>{display: inline-block; }
.current-status-<?=Status::INACTIVE?> .user-status-<?=Status::INACTIVE?>{display: inline-block; }
</style>



<script>
function setStatus(id, status)
{
	/*if(!confirm('Сменить ? '))
		return;*/
//alert(status)
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/user/setStatus',
		data: 'userId='+id+'&status='+status,
		dataType: 'json',
		beforeSend: function(){},
		success: function(data){
			if(data.errors==null)
			{
				$('.user').removeClass('current-status-<?=Status::ACTIVE?>').removeClass('current-status-<?=Status::INACTIVE?>').addClass('current-status-'+data.status)

				// 	для списка тоже нужно изменить
				$('#row-'+id).removeClass('status-<?=Status::ACTIVE?>').removeClass('status-<?=Status::INACTIVE?>').addClass('status-'+data.status)
			}
			else
				alert(data.errors[0].error)
		},
		error: function(e){alert('Возникла ошибка на сервере... Попробуйте позже.')},
		complete: function(){}
	});
}
</script>


<div class="user current-status-<?=$u->status->code?>">
	<h1>
	<a style="opacity: .4; font-size: .8em; text-decoration: none; " onclick="wList.slideDown('fast'); wView.slideUp('fast'); if($('#users-list').html()==''){usersList()}; return false; " href="?"><span  ><i class="fa fa-backward"></i> список</span></a> | 
		<?php 
		if($u)
		{?>
			<span style="font-size: 15px; font-weight: bold; opacity: .5; ">id : <?=$u->id?> &nbsp; </span><br><?=$u->fullName?>
		<?php 	
		}
		else
		{?>
			<span style="font-size: 13px; text-shadow: none;">Пользователь не найден.</span>
		<?php 	
		}?>
	</h1>
	
	
	
	
	
	
	<?php 
	if($u)
	{?>
	<div class="info">
		<div class="row1">
			<span class="label">Дата регистрации:</span> <b><?=Funx::mkDate($u->registrationDate, 'with_time')?></b>
		</div>
		<div class="row1">
			<span class="label">Статус:</span> 
			<b class="status user-status-<?=Status::ACTIVE?>"><a href="#" onclick="setStatus('<?=$u->id?>', '<?=Status::INACTIVE?>'); return false;"><?=Status::code(Status::ACTIVE)->icon?></a> <?=Status::code(Status::ACTIVE)->name?></b>
			<b class="status user-status-<?=Status::INACTIVE?>"><a href="#" onclick="setStatus('<?=$u->id?>', '<?=Status::ACTIVE?>'); return false;"><?=Status::code(Status::INACTIVE)->icon?></a> <?=Status::code(Status::INACTIVE)->name?></b>
		</div>
		<div class="row1">
			<span class="label">E-mail:</span> <b><a href="mailto:<?=$u->email?>"><?=$u->email?></a></b>
		</div>
		<div class="row1">
			<span class="label">Объявления:</span> <b><a href="/<?=ADMIN_URL_SIGN?>/<?=Adv::BACKEND_MODULE?>/items/itemsList/?chosenUserId=<?=$u->id?>" target="_blank">смотреть</a></b>
		</div>
	</div>
	<?php 	
	}?>
</div>