<?php
$item = $MODEL['item']; 
$comments = $MODEL['comments'];

//vd($comments);

$isOwner = $item->userId == $USER->id;
$isAdmin = Admin::isAdmin();





?>

<?php ob_start();?>
<style>
	.status-<?=Status::ACTIVE?>{}
	.status-<?=Status::INACTIVE?>{/* background: #eee;*/ }
	.status-<?=Status::MODERATION?>{/*background: #EDF1FF;*/ }
	.status-<?=Status::DELETED?>{}
	.status-<?=Status::EXPIRED?>{}

	.status-label{display: none; font-size: 40px; font-weight: bold; font-family: cuprum; background: #55AFCF; padding: 2px 7px; color: #fff;  }
	.status-label-<?=Status::MODERATION?>{background: #55AFCF;}
	.status-<?=Status::MODERATION?> .status-label-<?=Status::MODERATION?>{display: inline-block; }
	
	.status-label-<?=Status::INACTIVE?>{background: #777;}
	.status-<?=Status::INACTIVE?> .status-label-<?=Status::INACTIVE?>{display: inline-block; }
	
	.status-label-<?=Status::DELETED?>{background: #000;}
	.status-<?=Status::DELETED?> .status-label-<?=Status::DELETED?>{display: inline-block; }
	
	.status-label-<?=Status::EXPIRED?>{background: #EDB602;}
	.status-<?=Status::EXPIRED?> .status-label-<?=Status::EXPIRED?>{display: inline-block; }
	
</style>




<script>

function showInfo(id)
{
	//alert(id)
	$('.contacts .loading').slideDown('fast')
	$('.contacts .inf').html('').slideUp('fast')


	$.ajax({
		url: '/adv/item/contactInfoAjax',
		data: 'id='+id,
		dataType: 'json',
		beforeSend: function(){},
		success: function(data){
			if(data.error=='')
			{
				$('.contacts .to-hide').slideUp('fast')
				//$('.contacts .inf').html(data.html).slideDown('fast')
				$('#hidden-name').html(data.name.substring(<?=AdvItem::SALT_LENGTH?>))
				$('#hidden-phone').html(data.phone.substring(<?=AdvItem::SALT_LENGTH?>))
				$('#hidden-email').html(data.email.substring(<?=AdvItem::SALT_LENGTH?>))
			}
			else
				$('.contacts .inf').html(data.error)
		},
		error: function(e){alert('Возникла ошибка на сервере... Попробуйте позже.')},
		complete: function(){}
	});
}





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
				$('.status-switcher').html(data.status.icon+' '+data.status.name)
				$('#wrapper').removeAttr('class').addClass('status-'+data.status.code)
			}
			else
				this.error(data.error)
		},
		error: function(e){alert(e!='' ? e : 'Возникла ошибка на сервере... Попробуйте позже.')},
		complete: function(){}
	});
}






function prolong(id)
{
	$.ajax({
		url: '/cabinet/advs/prolong',
		data: 'id='+id,
		dataType: 'json',
		beforeSend: function(){},
		success: function(data){
			if(!data.errors)
			{
				//alert('ok')
				error('Объявление успешно продлено ещё <b>на <?=AdvItem::DAYS_TILL_EXPIRATION?> <?=Funx::okon(AdvItem::DAYS_TILL_EXPIRATION, array('дней', 'день', 'дня'))?></b>!')
				setTimeout('location.reload()', 2000)
			}
			else
				showErrors(data.errors)
		},
		error: function(e){alert('Возникла ошибка на сервере... Попробуйте позже.')},
		complete: function(){}
	});
}





<?php 
if($isAdmin)
{?>
	
	function approve(id)
	{
		$.ajax({
			url: '/cabinet/advs/approve',
			data: 'id='+id,
			dataType: 'json',
			beforeSend: function(){$.fancybox.showLoading()},
			success: function(data){
				if(!data.errors)
				{
					$('.status-switcher').html(data.status.icon+' '+data.status.name)
					$('#wrapper').removeAttr('class').addClass('status-'+data.status.code)
					$('.approve-btn').css('display', 'none')
				}
				else
					showErrors(data.errors)
			},
			error: function(e){alert(e!='' ? e : 'Возникла ошибка на сервере... Попробуйте позже.')},
			complete: function(){$.fancybox.hideLoading()}
		});
	}
	

	function adminDelete(id)
	{
	    var reason = $('.reason-delete textarea').val()

		$.ajax({
			url: '/cabinet/advs/advAdminDeleteItem',
			data: {id: id, reason: reason},
			dataType: 'json',
			beforeSend: function(){$.fancybox.showLoading()},
			success: function(data){
				if(!data.errors)
				{
					$('.status-switcher').html(data.status.icon+' '+data.status.name)
					$('#wrapper').removeAttr('class').addClass('status-'+data.status.code)
					
					$('.delete-btn').css('display', 'none')
					$('.edit-btn').css('display', 'none')

                    $('.reason-delete').slideUp('fast')
				}
				else
					showErrors(data.errors)
			},
			error: function(e){alert('Возникла ошибка на сервере... Попробуйте позже.')},
			complete: function(){$.fancybox.hideLoading()}
		});
	}
<?php 
}?>
</script>

<?php $CONTENT->sectionHeader .= ob_get_clean();?>




<?php 
if(!$MODEL['error'])
{?>


<!--крамбсы-->
<? Core::renderPartial(SHARED_VIEWS_DIR.'/crumbs.php', $MODEL['crumbs']);?>
<!--//крамбсы-->




<!--ADMIN-->
<?php 
if($isAdmin)
{?>
<div class="tool-panel admin">
	<div class="col" ><h1>Админ:</h1></div>
	<div class="col">
		Статус: <span class="status-switcher"><?=$item->status->icon?> <?=$item->status->name?></span>
	</div>
	
	<?php 
	if($item->status->code==Status::MODERATION)
	{?>
        <div class="col approve-btn">
            <a href="#" onclick="if(confirm('Одобрить объявление? ')){approve(<?=$item->id?>)}; return false;"><i class="fa fa-thumbs-o-up"></i> одобрить</a>
        </div>

        <div class="col reject-btn">
            <a href="#" onclick="$('.reason-rejection').slideToggle('fast'); return false; "><i class="fa fa-thumbs-o-down"></i> отклонить</a>
        </div>
        

	<?php 
	}?>
	
	<?php 
	if($item->status->code!=Status::DELETED)
	{?>
	<div class="col delete-btn" style="padding-left: 30px;">
		<a href="#" onclick="$('.reason-delete').slideToggle('fast'); return false; "><i class="fa fa-times"></i> удалить</a>
	</div>
	<?php 
	}?>

    <div class="reason-rejection" style="display: none; ">
        Причина отклонения:<br>
        <textarea style="width: 400px; height: 60px; "></textarea>
        <input type="button" value="Отклонить" onclick="alert('reject!'); ">
    </div>

    <div class="reason-delete" style="display: none; ">
        Причина удаления:<br>
        <textarea style="width: 400px; height: 60px; "></textarea>
        <input type="button" value="Удалить" onclick=" if(confirm('Удалить объявление? ')){adminDelete(<?=$item->id?>);}; return false; ">
    </div>
	
</div>
<?php 
}?>
<!--//ADMIN-->





<!--OWNER-->
<?php 
if($isOwner)
{?>
<div class="tool-panel owner">
	<div class="col" style=" "><h1>Это Ваше объявление:</h1></div>
	<div class="col">Сменить статус: 
		<?php 
		if($item->status->code == Status::ACTIVE || $item->status->code == Status::INACTIVE)
		{?>
		<a href="#" class="status-switcher" onclick="switchStatus(<?=$item->id?>); return false; "><?=$item->status->icon?> <?=$item->status->name?></a>
		<?php 
		}
		else
		{?>
		<span class="status-switcher"><?=$item->status->icon?> <?=$item->status->name?></span>
			<?php 
			if($item->status->code == Status::EXPIRED)
			{?>
				<a href="#" onclick="if(confirm('Продлить объявление? ')){prolong(<?=$item->id?>);} return false; " style="font-weight: bold; ">[ продлить ]</a>
			<?php 	
			}?>
		<?php 
		}?>
	</div>
	
	<?php 
	if($item->status->code!=Status::code(Status::DELETED)->code)
	{?>
	<div class="col edit-btn"><a href="<?=$item->editUrl()?>"><i class="fa fa-pencil-square-o"></i> редактировать</a></div>
	<?php 
	}?>
</div>
<?php 
}?>
<!--//OWNER-->





<div class="adv-view">
	<div id="wrapper"  class="status-<?=$item->status->code?>">
	
		<div class="status-label status-label-<?=Status::MODERATION?>"><?=Status::code(Status::MODERATION)->icon?> МОДЕРАЦИЯ</div>
		<div class="status-label status-label-<?=Status::INACTIVE?>"><?=Status::code(Status::INACTIVE)->icon?> НЕАКТИВНО</div>
		<div class="status-label status-label-<?=Status::DELETED?>"><?=Status::code(Status::DELETED)->icon?> УДАЛЕНО</div>
		<div class="status-label status-label-<?=Status::EXPIRED?>"><?=Status::code(Status::EXPIRED)->icon?> ПРОСРОЧЕНО</div>
		
		
		<div class="clear"></div>
		
		<div class="cell left">
		
			<div class="top-info">
				<span class="date" ><?=mb_strtolower(Funx::mkDate($item->dateCreated), 'utf-8')?>,</span>
				<span class="city"><?=$item->city->name?></span>
				<span> | <a href="<?=Route::getByName(Route::SPISOK_OBYAVLENIY_KATEGORII)->url($item->cat->urlPiece())?>" class="blue cat-name" ><?=$item->cat->name?></a></span>
			</div>
			
			
			<h1 class="title"><?=$item->name?></h1>
			
			
			<div class=" text">
			
				<?php
				if($item->descr)
				{?>
					<div class="descr"><?=$item->descr?></div>
				<?php
				}?>
				
				<div class="mini-info2">
					<div >
						Бренд:
						<?php 
						if($item->brand)
						{?>
							<?php 
							if($item->brand->pic)
							{?>
								<a  href="<?=Route::getByName(Route::SPISOK_OBYAVLENIY_KATEGORII)->url($item->cat->urlPiece())?>?brand=<?=$item->brand->id?>&go=1" title="<?=$item->brand->name?>"><img src="<?=Media::img($item->brand->pic.'&height=21')?>" alt="<?=$item->brand->name?>"  title="<?=$item->brand->name?>" style="vertical-align: middle; padding: 0 0 4px 0;  " /></a>
							<?php 	
							}
							else
							{?>
								<b><?=$item->brand->name?></b>
							<?php 	
							}?>
						<?php 	
						}
						else
						{?>
							<b>-не указан-</b>
						<?php 	
						}?>
					</div> 
					 <div class="artnum">
						Арт. номер: <b class="red" style="font-weight: normal; ">
						<?php 
						if($item->artnum)
						{?>
							<a href="<?=Route::getByName(Route::SPISOK_OBYAVLENIY_KATEGORII)->url($item->cat->urlPiece())?>?brand=<?=$item->brand->id?>&artnumId=<?=$item->artnum->id?>&go=1" class="red"><?=$item->artnum->name?></a>
							<?
							if($item->artnum->pic)
							{?>
							<a href="/<?=UPLOAD_IMAGES_REL_DIR?>/<?=$item->artnum->pic?>" class="highslide" onclick="return hs.expand(this)"><img src="<?=Media::img($item->artnum->pic.'&height=38')?>" alt="<?=$item->artnum->name?>"  title="<?=$item->artnum->name?>" style="vertical-align: middle; " /></a>
							<span style="color: #888; font-size: 11px; font-style: italic; font-weight: normal; ">(нажмите чтобы посмотреть)</span>
							<?php 	
							}?>
						<?php 	
						}
						else
						{?>
							<span class="red">-не указан-</span>
						<?php 	
						}?>
						</b>
					</div>
				</div>


				
				<?php 
				if(count($item->cat->class->props) && $item->dealType->code!=DealType::BUY)
				{?>
					<div class="props">
						<h3>Доп. информация:</h3>
					<?php
					foreach($item->cat->class->props as $key=>$prop)
					{//vd($item->propValuesObjs[$prop->code])?>
						<div class="row">
							<span class="label"><?=$prop->nameOnSite?>: </span>
							<span class="value"><?=$prop->frontendListOutput($item->propValuesObjs[$prop->code])?></span>
						</div>
					<?php 	
					}
					?>
					</div>
				<?php 
				}?>
			</div>
		</div>
		
		
		<div class="cell right contacts" >
			
			<!-- цена -->
			<?=Core::renderPartial(SHARED_VIEWS_DIR.'/pricePartial.php', $arr = array('item'=>$item))?>
			<!-- //цена -->
			
			<p></p>
			<h3>Контактная информация:</h3>
			
			<div class="inf2">
				<div class="name">
					<?=$item->contactName ? Funx::hideStr($item->contactName, $sign=null, $visibleLen=AdvItem::VISIBLE_NAME_LENGTH, $hiddenWrapperId='hidden-name') : '-не указан-'?> 
				</div>
				<div>
					<i class="fa fa-phone" style="padding: 0 0 0 2px; width: 19px; "></i>
					<?=$item->phone ? Funx::hideStr($item->phone, $sign=null, $visibleLen=AdvItem::VISIBLE_PHONE_LENGTH, $hiddenWrapperId='hidden-phone') : '-не указан-'?> 
				</div>
				<div class="email">
					<i class="fa fa-envelope-o" ></i>
					<?=$item->email ? Funx::hideStr($item->email, $sign=null, $visibleLen=AdvItem::VISIBLE_EMAIL_LENGTH, $hiddenWrapperId='hidden-email') : '-не указан-'?> 
				</div>
				
				<div class="city1">
					<i class="fa fa-globe" 	></i>
					г. <?=$item->city->name?> 
				</div>
				
				
			</div>
			
			<div class="to-hide">
				<a href="#" class="btn-square btn-blue btn-mini " style="font-size: 12px;" onclick="showInfo(<?=$item->id?>); return false; ">ПОСМОТРЕТЬ ДАННЫЕ</a>
				<span class="loading" style="font-size: 11px; display: none; ">Секунду...</span>
			</div>
			
			<div class=" pics">
				<!--<h3>ФОТО:</h3>-->
				<hr />
				<h3>Фотографии:</h3>
			<?php 
			if(count($item->media))
			{
				foreach($item->media as $media)
				{?>
					<div style="display: inline-block; ">
						<a class="item highslide" style="display: table-cell;" href="<?=$media->src()?>" onclick="return hs.expand(this)" class=" " title="Увеличить">
							<img style=" text-align: center; " src="<?=$media->resizeSrc()?>&width=90&height=70">
						</a>
					<?php 
					
					if($isAdmin)
					{
						$relSrc = $media->src($rel=false);
						$size = getimagesize(ROOT.$relSrc);
						//vd($size);
						$fileWeight = filesize(ROOT.$relSrc);
						$totalFilesWeight += $fileWeight;
						?>
						<span class="admin" style="display: table-cell;   vertical-align:middle; padding: 0 0 0 10px; ">
							<span style="font-size: 11px; color:  #777 ; "><?=$relSrc?> </span>
							<br><span><?=$size[0]?>&times;<?=$size[1]?> &asymp; <?=Funx::getFileSizeOkon($fileWeight)?></span> 
						</span>
						
					<?php 	
					}?>
					
					</div>
				<?php 
				}
				if($isAdmin)
				{?>
					<div class="admin">Итого размер всех картинок <b>&asymp; <?=Funx::getFileSizeOkon($totalFilesWeight)?></b> </div>
				<?php 
				}
			}
			else
			{?>
				<span style="font-size: .9em; ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;нет фото.</span>
			<?php 
			}?>
			</div>
			
		</div>
		
		
		
	</div>
	
	<div class="bottom-info">
		<img src="/img/eye.png" height="15" style="vertical-align: top; " alt="" /> Просмотров: <b><?=$item->views?></b>	
	</div>
	
	
	<div style="margin: 40px 0 0 0 ;">
	<? Core::renderPartial(SHARED_VIEWS_DIR.'/comments.php', $arr = array('comments'=>$comments, 'item'=>$item))?>
	</div>
	
</div>
<?php 
}
else
{?>
	Объявление не найдено. 
<?php 
}?>