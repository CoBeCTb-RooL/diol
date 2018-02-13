<?php
$list = $MODEL['users']; 
$totalCount = $MODEL['totalCount'];
$p = $MODEL['p'];
$elPP = $MODEL['elPP'];
$orderBy = $MODEL['orderBy'];
$desc = $MODEL['desc'];
//vd($MODEL);
$status = $MODEL['status'];
$email = $MODEL['email'];

//vd($MODEL);

//vd($MODEL['status']);

$upArrowSign = '▲';
$downArrowSign = '▼';
?>




<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>




<style>
	.status-active{}
	.status-inactive{opacity: .4;  }
	.delete-btn{display: none; }
	.status-inactive .delete-btn{display: block; }
	
</style>



<script>
function switchStatus(id)
{
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/adv/brands/switchStatus',
		data: 'brandId='+id,
		dataType: 'json',
		beforeSend: function(){},
		success: function(data){
			if(data.error=='')
			{
				$('#status-switcher-'+id).html(data.status.icon)
				$('#row-'+id).removeAttr('class').addClass('status-'+data.status.code)
			}
			else
				this.error(data.error)
		},
		error: function(e){alert(e=='' ? e : 'Возникла ошибка на сервере... Попробуйте позже.')},
		complete: function(){}
	});
}
</script>



<!-- фильтры -->
<div class="filters">
	
	<div class="section statuses">
		<h1>Статус:</h1>
		<a class="item <?=!$status ? 'active' : ''?>" href="#" onclick="OPTS.status=''; OPTS.p=1;  usersList(); return false; ">ВСЕ</a>
	<?php 
	$statuses = array(
			Status::code(Status::ACTIVE),
			Status::code(Status::INACTIVE),
			Status::code(Status::DELETED),
	);
	foreach($statuses as $s)
	{?>
		<a class="item <?=$status->num == $s->num ? 'active' : ''?>" href="#" onclick="OPTS.p=1; OPTS.status=<?=$s->num?>; usersList(); return false; "><?=$s->icon?> <?=$s->name?></a>
	<?php 
	}?>
	</div>
	
	<div class="section email">
		<h1>E-mail:</h1>
		<input type="text" name="email" id="email" value="<?=$email?>" style="width: 120px;" />
		&nbsp;&nbsp;<input type="button" value="взять" onclick="OPTS.p=1; OPTS.email=$('#email').val(); usersList(); return false; " />&nbsp;<input type="button" value="&times;" onclick="OPTS.p=1; OPTS.email=''; usersList(); return false; " />
	</div>
	
	
</div>
<!-- //фильтры -->


<?php 
if($totalCount)
{?>


<h4>Найдено: <?=$totalCount?></h4>
<form id="list-form" method="post" action="" target="frame1"  >
	<table class="t">
		<tr>
			<th>#</th>
			<th><a href="#" onclick="OPTS.p=1; OPTS.orderBy='id'; OPTS.desc='<?=$orderBy=='id' ? ($desc ? 0 : 1) : 0?>'; usersList(); return false; ">id <?=$orderBy=='id' ? ($desc ? $downArrowSign : $upArrowSign) : '' ?></a></th>
			<!-- <th></th> -->
			
			<th><a href="#" onclick="OPTS.p=1; OPTS.orderBy='surname'; OPTS.desc='<?=$orderBy=='surname' ? ($desc ? 0 : 1) : 0?>'; usersList(); return false; ">ФИО <?=$orderBy=='surname' ? ($desc ? $downArrowSign : $upArrowSign) : '' ?></a></th>
			<th>Дата рег.</th>
			<th>Email</th>
			<th><a href="#" onclick="OPTS.p=1; OPTS.orderBy='cityId'; OPTS.desc='<?=$orderBy=='cityId' ? ($desc ? 0 : 1) : 0?>'; usersList(); return false; ">Город <?=$orderBy=='cityId' ? ($desc ? $downArrowSign : $upArrowSign) : '' ?></a></th>
			<!--  <th>Статус</th>-->
			
			
			<!--<th>Удалить</th>-->
		</tr>
		<?php 
		$i=($p-1)*$elPP;
		foreach($list as $key=>$u)
		{?>
			<tr id="row-<?=$u->id?>" class="status-<?=$u->status ? $u->status->code : ''?> "  >
				<td style="width:1px; font-size: 8px; text-align: center; "><?=(++$i)?>. </td>
				<td><?=$u->id?></td>
				<!-- <td width="1"  class="status-switcher">
					<a href="#" id="status-switcher-<?=$u->id?>" onclick="switchStatus(<?=$u->id?>); return false; " ><?=$u->status->icon?></a>
				</td> -->
				<td><a href="?userId=<?=$u->id?>" onclick="userView(<?=$u->id?>); return false;" style="font-weight: bold; "><?=$u->fullName?></a></td>
				<td style="font-size: 10px; "><?=$u->registrationDate?></td>
				
				<td><?=$u->email?></td>
				<td>
					<?php 
					if($u->city)
					{?>
						<a href="/<?=ADMIN_URL_SIGN?>/city?cityId=<?=$u->city->id?>" target="_blank"><?=$u->city->name?></a>
					<?php 	
					}
					else 
					{?>
						--
					<?php 	
					}?>
				</td>
				
				<!-- <td><?=$u->status->name?></td>-->
				
				
				<!-- <td><a href="#delete" class="delete-btn" onclick="Slonne.Adv.Brands.delete1(<?=$u->id?>); return false;">удалить</a></td> -->
			</tr>
		<?php 
		}?>
	</table>
</form>

<p>
	<?=drawPages($totalCount, $p-1, $elPP, $onclick="OPTS.p=###; usersList()", $class="pages");?>
	
	
<?php
}
else
{?>
	Ничего нет.
<?php 	
} 
?>
