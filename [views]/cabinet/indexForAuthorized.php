<?php
$user = $MODEL['user'];
//vd($user); 
$advs = $MODEL['advs'];
//vd($advs);
foreach($advs as $item)
	$stats[$item->status->code]++;
	//if($item->status->code == Status::ACTIVE)
	
//vd($stats);
?>


<!--крамбсы-->
<? Core::renderPartial(SHARED_VIEWS_DIR.'/crumbs.php', $MODEL['crumbs']);?>
<!--//крамбсы-->


<?php 
if($user)
{?>


<?php Core::renderPartial('cabinet/menu.php');?>


<div class="auth-user-index">

	<div class="row">
		<div class="col left">
			<table class="person-info icons-table">
				<tr>
					<td class="icon"><i class="fa fa-user"></i></td>
					<td class="text"><b style="font-size: 16px; "><?=$user->surname.' '.$user->name.' '.$user->fathername?></b></td>
				</tr>
				<tr>
					<td class="icon"><i class="fa fa-calendar"></i></td>
					<td class="text"><span class="label">На сайте</span> с <?=mb_strtolower(Funx::mkDate($user->registrationDate), 'utf-8')?></td>
				</tr>
				<tr>
					<td class="icon"><i class="fa fa-map-marker"></i></td>
					<td class="text"><span class="label">Город:</span> <?=$user->city->name?></td>
				</tr>
			</table>
			
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?=Route::getByName(Route::CABINET_PROFILE_EDIT)->url()?>" class="edit-link">редактировать</a>
			
			<hr />
			
			<div class="contacts">
				<h4>КОНТАКТНАЯ ИНФОРМАЦИЯ</h4>
				<table class="icons-table">
					<tr>
						<td class="icon"><i class="fa fa-phone-square"></i></td>
						<td class="text"><span class="label">Тел.:</span> <?=$user->phone?></td>
					</tr>
					<tr>
						<td class="icon"><i class="fa fa-envelope"></i></td>
						<td class="text"><span class="label">Email:</span> <?=$user->email?></td>
					</tr>
				</table>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?=Route::getByName(Route::CABINET_PROFILE_EDIT)->url()?>" class="edit-link">редактировать</a>
			</div>
		</div>
		
		<div class="col">
			<div class="advs-stats">
				<h4>МОИ ОБЪЯВЛЕНИЯ</h4>
				<ul>
					<li><a href="<?=Route::getByName(Route::CABINET_MY_ADVS)->url()?>?status=<?=Status::ACTIVE?>">Опубликовано на сайте <span class="red">(<?=$stats[Status::ACTIVE]?$stats[Status::ACTIVE]:0?>)</span></a></li>
					<li><a href="<?=Route::getByName(Route::CABINET_MY_ADVS)->url()?>?status=<?=Status::MODERATION?>">В модерации <span class="red">(<?=$stats[Status::MODERATION]?$stats[Status::MODERATION]:0?>)</span></a></li>
					<!-- <li><a href="<?=Route::getByName(Route::CABINET_MY_ADVS)->url()?>?status=<?=Status::DELETED?>">Удалено <span class="red">(<?=$stats[Status::DELETED]?$stats[Status::DELETED]:0?>)</span></a></li> -->
					<li><a href="<?=Route::getByName(Route::CABINET_MY_ADVS)->url()?>?status=<?=Status::INACTIVE?>">Неактивные <span class="red">(<?=$stats[Status::INACTIVE]?$stats[Status::INACTIVE]:0?>)</span></a></li>
				</ul>
				
				<a href="<?=Route::getByName(Route::CABINET_MY_ADVS)->url()?>" class="btn-square  btn-m" style="margin-top: -10px; ">Смотреть все</a>&nbsp;
				<a href="<?=Route::getByName(Route::CABINET_ADV_EDIT)->url()?>" class="btn-square btn-green btn-l" style="margin: 20px 0 0 0; display: block; ">+ ДОБАВИТЬ ОБЪЯВЛЕНИЕ</a>
			</div>
		</div>
	</div>
	

	
	
</div>

<?php 
}
else 
{?>
	Вы не авторизованы. 
<?php 
}?>