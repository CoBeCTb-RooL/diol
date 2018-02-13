<?php
$advs = $MODEL['advs'];
$totalCount = $MODEL['totalCount'];
$page = $MODEL['page'];
$elPP = $MODEL['elPP'];
$j=0;  

$statusesToShow = $MODEL['statusesToShow'];
$chosenStatus = $MODEL['chosenStatus'];

$dealTypes = $MODEL['dealTypes'];
$chosenDealType = $MODEL['chosenDealType'];
//vd($statusesToShow);
//vd($advs);
?>


<?php ob_start();?>
<script>
function switchStatus(id)
{
	$.ajax({
		url: '/cabinet/advs/advSwitchStatus',
		data: 'id='+id,
		dataType: 'json',
		beforeSend: function(){},
		success: function(data){
			if(data.error=='')
			{
				$('#status-switcher-'+id).html(data.status.icon+' '+data.status.name)
				$('#row-'+id).removeAttr('class').addClass('item').addClass('status-'+data.status.code)

				if(data.status.code == '<?=Status::INACTIVE?>')
					error('Объявление <b>скрыто</b>, и не отображается на сайте.')
				else
					error('Объявление <b>возвращено на сайт</b>.')
			}
			else
				this.error(data.error)
		},
		error: function(e){alert(e!='' ? e : 'Возникла ошибка на сервере... Попробуйте позже.')},
		complete: function(){}
	});
}
</script>
<?php $CONTENT->sectionHeader .= ob_get_clean();?>


<!--крамбсы-->
<? Core::renderPartial(SHARED_VIEWS_DIR.'/crumbs.php', $MODEL['crumbs']);?>
<!--//крамбсы-->






<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>





<?php Core::renderPartial('cabinet/menu.php');?>

<?php 
/*for($a=0; $a<=30; $a++)
{
	echo'<br/>'.$a.' '.Funx::okon($a, array('объявлений', 'объявление', 'объявления'));
}*/?>





	
	<div class="filters">
		<form action="<?=Route::getByName(Route::CABINET)->url()?>advs" method="get" id="filters-form">
		
			<div class="row">
				<div class="label cuprum">Статус:</div>
				<select name="status" id="" onChange="$('#filters-form').submit()" >
					<option value="">-все-</option>
				<?php
				foreach($MODEL['statusesToShow'] as $status)
				{?>
					<option value="<?=$status?>" <?=$chosenStatus->code==$status ? 'selected="selected" ' : ""?>><?=Status::code($status)->name?></option>
				<?php 	
				}?>
				</select>
			</div>
			
			<p>
			
			<div class="row">
				<div class="label cuprum">Тип: </div>
				<!--<label onclick="$('#filters-form').submit()"><input type="radio" name="dealType" value="" checked="checked" /> Все</label><br/>
				<?php 
				foreach($dealTypes as $code=>$type)
				{?>
					<label onclick="$('#filters-form').submit()"><input type="radio" name="dealType" value="<?=$type->code?>" <?=$type->code == $chosenDealType->code ? ' checked="checked" ' : ""?> /><?=$type->title?></label><br/>
				<?php 
				}?>-->
				<select name="dealType" id="" onChange="$('#filters-form').submit()" >
					<option value="">-все-</option>
				<?php
				foreach($dealTypes as $code=>$type)
				{?>
					<option value="<?=$type->code?>" <?=$type->code == $chosenDealType->code ? ' selected="selected" ' : ""?> ><?=$type->title?></option>
				<?php 	
				}?>
				</select>
			</div>
			
			<hr />
			<button type="button" onclick="location.href='<?=Route::getByName(Route::CABINET_ADV_EDIT)->url()?>'">+ ПОДАТЬ ОБЪЯВЛЕНИЕ</button>
			
			
		</form>
	</div>

	<div  class="adv-list self-advs">
		<!--<a href="javascript:history.go(-1)">&larr; назад</a><p>-->
		<h2 style="margin: 5px 0 15px 0; padding: 0; ">МОИ ОБЪЯВЛЕНИЯ:</h2>
		<?php
		if(count($advs))
		{?>
			<div class="heading">У Вас <b><?=$totalCount?> <?=Funx::okon($totalCount, array('объявлений', 'объявление', 'объявления'))?></b>: </div>
			
			<div class="wrapper">
			<?php 
			foreach($advs as $key=>$item)
			{?>
				<div class="row">
					<div class="item status-<?=$item->status->code?>" id="row-<?=$item->id?>">
						
						<div class="cell num"><?=(++$j)+$elPP*$page?>. </div>
						
						<div class="cell pic">
							<a href="<?=$item->url()?>" title="<?=$item->name?>">
								<img src="<?=$item->media[0] ? $item->media[0]->resizeSrc(): AdvMedia::noPhotoSrc()?>&width=130&height=100" alt="<?=$item->name?>">
							</a>
						</div>
						
						<div class="cell info">
							<div class="date"><?=mb_strtolower(Funx::mkDate($item->dateCreated, 'with_time_without_seconds'), 'utf-8')?></div>
							<a href="<?=$item->url()?>" class="title"><?=$item->name?></a>
							<div class="short-info"></div>
							<div class="details">
								<div><span class="label">Категория: </span> <?=$item->cat->name?></div>
								<?php 
								if($item->brand)
								{?>
								<div><span class="label">Бренд: </span> <?=$item->brand->name?></div>
								<?php 
								}?>
								<!-- <div><span class="label">Цена: </span> 3 000 тг.</div> -->
								<div><span class="label"></span><b>г. <?=$item->city->name?></b></div>
							</div>
						</div>
						
						<div class="cell status border-left "  >
						<?php
						if($item->status->code == Status::code(Status::ACTIVE)->code || $item->status->code == Status::code(Status::INACTIVE)->code)
						{?>
							<a href="#change_status" onclick="switchStatus(<?=$item->id?>); return false; " style="text-decoration: none; " id="status-switcher-<?=$item->id?>" >
							<span><?=$item->status->icon?> <?=$item->status->name?></span>
							</a>
						<?php 
						}
						else
						{?>
							<span><?=$item->status->icon?> <?=$item->status->name?></span>
						<?php 
						}?>
						</div>
						
						
						<div class="cell edit  border-left border-right ">
							<a href="<?=$item->editUrl()?>" class="edit-btn"><i class="fa fa-cog"></i> ред.</a>
						</div>
					
						
					</div>
				</div>
				
			<?php 	
			}?>
			</div>
			<div style="margin-top: 20px;"><?=Funx::drawPages($MODEL['totalCount'], $MODEL['page'], $MODEL['elPP']);?></div>
		<?php 
		}
		else
		{?>
			Объявлений нет.
		<?php 	
		} 
		?>
	</div>

