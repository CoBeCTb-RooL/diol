<?php
$error = $MODEL['error'];
$item = $MODEL['item'];
$user = $MODEL['user']; 
$chosenCat = $MODEL['chosenCat'];
$brands = $MODEL['brands'];
$chosenBrand = $MODEL['chosenBrand'];
$artnums = $MODEL['artnums'];
$chosenArtnum = $MODEL['chosenArtnum'];
$cities = $MODEL['cities'];
$chosenCity = $MODEL['chosenCity'];
//vd($brands);
//vd($chosenCat);
$title = 'ДОБАВЛЕНИЕ ОБЪЯВЛЕНИЯ в категорию "'.$chosenCat->name.'"';
if($item)
	$title = 'РЕДАКТИРОВАНИЕ ОБЪЯВЛЕНИЯ';

//vd($item);
//vd($chosenCat->productVolumeUnits);
?>


<!--крамбсы-->
<? Core::renderPartial(SHARED_VIEWS_DIR.'/crumbs.php', $MODEL['crumbs']);?>
<!--//крамбсы-->


<?php 
# 	если есть ошибка - всё!
if($error)
{
	echo $error;
	return; 
}?>



<?php ob_start()?>
<style>
	.success{text-align: left; padding: 0px 0 0 30px;}
</style>

<script>
var CHOSEN_CAT=<?=$chosenCat->id?>;
var CHOSEN_BRAND='';

function switchBrand(brandId)
{
	if(brandId=='')
		$('#filter-artnums').slideUp('fast')
	else
	{
		CHOSEN_BRAND=brandId
		$.ajax({
			url: "<?=Route::getByName(Route::ARTNUMS_LIST_BY_BRAND)->url()?>",
			data:  "cat="+CHOSEN_CAT+"&brand="+CHOSEN_BRAND+"&otherOption=1",
			dataType: "json",
			beforeSend: function(){$('#filter-artnums').css('opacity', .3); $('#filter-artnums-preloader').css('display', 'block');},
			success: function(data){
				//alert(data);
				if(data.count > 0)
				{ 
					$('#filter-artnums').slideDown('fast').css('opacity', 1); 
					$('#filter-artnums .artnums-wrapper').html(data.html)
				}
				else
					$('#filter-artnums').slideUp('fast')
			},
			error: function(err){alert("Возникла ошибка на сервере... Попробуйте позднее")},
			complete: function(){$('#filter-artnums-preloader').css('display', 'none');}
		});
	}
}


function toggleArtnumOtherOption()
{
	//alert($('#filter-artnums option:selected').val())
	if($('#filter-artnums option:selected').val()=='other')
		$('#artnum-other').slideDown();
	else
	{
		$('#artnum-other').slideUp();
		$('#artnum-other-input').val('')
	}
}



function submitStart()
{
	$('#adv-form').css('opacity', '.4')
	$('#adv-form .info').html('').slideUp('fast');
	$('#adv-form .loading').slideDown('fast')
	$('#adv-form *').removeClass('field-error')
}


function submitEnd()
{
	$('#adv-form').css('opacity', '1')
	$('#adv-form .loading').slideUp('fast')
}



function deleteMediaItem(id)
{
	//alert(id)
	$.ajax({
		url: "/cabinet/advs/deleteAdvMediaItem",
		data:  "id="+id+"",
		dataType: "json",
		beforeSend: function(){/*$('#pic-'+id).css('opacity', .3);*/ $('#loading-'+id).css('display', 'block') },
		success: function(data){
			if(data.error=='')
				$('#pic-'+id).fadeOut();
			else
				alert(data.error)
		},
		error: function(err){alert("Возникла ошибка на сервере... Попробуйте позднее")},
		complete: function(){/*$('#pic-'+id).css('opacity', 1);*/ $('#loading-'+id).css('display', 'none')}
	});
}



function switchDealType(dt)
{
	//alert(dt)
	if(dt == '<?=DealType::SELL?>'){
		$('#dop-info').slideDown('fast')
		$('#price-wrapper').slideDown('fast')
	}
	else{
		$('#dop-info').slideUp('fast')
		$('#price-wrapper').slideUp('fast')
	}
}


</script>
<?php $CONTENT->sectionHeader .= ob_get_clean()?>





<?php Core::renderPartial('cabinet/menu.php');?>




<form class="adv-form" id="adv-form" method="post" action="/cabinet/advs/advEditSubmit" target="frame4" onsubmit="submitStart()" enctype="multipart/form-data">
	<input type="hidden" name="catId" value="<?=$chosenCat->id?>" />
	<input type="hidden" name="id" value="<?=$item->id?>" />
	
	
	<?php
	if(!$item)
	{?>
	<h2>Создание объявления</h2>
	<h3>Шаг 2. Заполните форму</h3>
	<a href="#back" onclick="history.go(-1); return false; " style="display: block; margin: 13px 0; ">&larr;назад</a>
	<?php 
	}?>
	
	<div class="cell left">
		<h3><?=$title?></h3>
		<?php 
		if($item)
		{?>
			&nbsp;&nbsp;&nbsp;<a href="<?=$item->url()?>"  target="_blank"><i class="fa fa-newspaper-o"></i> Посмотреть объявление на сайте</a><p>
		<?php 
		}?>
		
		
		
		<div name="type"  class="row" style="width: 230px;">
			<div >
				<?php
				foreach(DealType::$items as $typeCode=>$dt)
				{?>
					<label style="font-size: 19px; " ><input type="radio" name="type" value="<?=$dt->num?>" <?=$item->dealType->code==$dt->code || (!$item&&$dt->code==DealType::SELL) ? ' checked="checked"  ' : ''?> onclick="switchDealType('<?=$dt->code?>')"/> <?=$dt->title?></label>	&nbsp;&nbsp;
				<?php 
				}?>	
			</div>
			
			<div class="hint">Вы собираетесь <b>купить</b>, или <b>продать</b>?</div>
		</div>
		
		
		<div class="row">
			Город: <select name="city" id="cityId">
				<option value="">-выберите-</option>
			<?php 
			foreach($cities as $c)
			{?>
				<option value="<?=$c->id?>" <?=$c->id == $chosenCity->id ? ' selected="selected" ':''?> style="<?=$c->isLarge ? 'font-size: 17px; font-weight: bold; ' : ' font-size: 15px; '?>"><?=$c->name?></option>
			<?php 	
			}?>
			</select>
		</div>
		
		
		<div class="row">Категория: <b><?=$item ? $item->cat->name : $chosenCat->name?></b></div>
		
		
		
		<div class="row">
			<div class="label">Короткий заголовок<span class="req">*</span>: </div>
			<div class="input"><input type="text" name="name" id="name" value="<?=$item->name?>" /></div>
			<div class="hint">Введите короткий заголовок в несколько слов, например "Срочно продам или поменяю ЛДСП"</div>
		</div>
		
		
		<!--бренды-->
		<?php 
		if(count($brands))
		{?>
		<div class="row">
			<div class="label">Производитель: </div>
			<div class="input">
				<?php Core::renderPartial(Adv::FRONTEND_CONTROLLER.'/filtersPartial/selectBrandsPartial.php', $a=array('brands'=>$brands, 'chosenBrand'=>$chosenBrand, 'onChange'=>'switchBrand($(\'option:selected\', this).val())'))?>
			</div>
		</div>
		<?php 
		}?>
		<!--//бренды-->
		
		<?php //vd(Adv::FRONTEND_CONTROLLER.'/filtersPartial/selectArtnumsPartial.php')?>
		
		<!--арт.номера-->
		<div class=" row" id="filter-artnums" style="display: <?=(!count($artnums) ? 'none' : 'block')?>; position: relative;  "  onChange="toggleArtnumOtherOption();">
			<div class="label">Артикульный номер:</div>
			<div class="input artnums-wrapper">
				<?php Core::renderPartial(Adv::FRONTEND_CONTROLLER.'/filtersPartial/selectArtnumsPartial.php', $a=array('artnums'=>$artnums, 'chosenArtnum'=>$chosenArtnum, 'otherOption'=>true, 'otherOptionSelected'=>trim($item->artnumOther) ))?>
			</div>
			
			<div id="artnum-other" style="margin: 10px 0 0 0 ;<?=!($item&&trim($item->artnumOther))?'display: none; ':''?>">
				Введите артикульный номер, но прежде, убедитесь, что его нет в списке!
				<input type="text" name="artnumOther" id="artnum-other-input" value="<?=$item->artnumOther?>" placeholder="Введите артикульный номер" style="width: 200px ; min-width: 200px;" />	
			</div>
			
		</div>
		<!--//арт.номера-->
		
		
		<div class="row">
			<div class="label">Коментарий: </div>
			<div class="input"><textarea name="descr" id="descr" style="height: 100px; "><?=$item->descr?></textarea></div>
			<div class="hint">Небольшой сопроводительный текст объявления.</div>
		</div>
		
		
		<div class="row">
			<div class="label">Фотографии: </div>
			<div class="pics">
			<?php 
			if(count($item->media))
			{
				foreach($item->media as $media)
				{?>
				<div class="item" id="pic-<?=$media->id?>">
					<a class="pic-link" href="<?=$media->src()?>" onclick="return hs.expand(this)" class="highslide ">
						<img src="<?=$media->resizeSrc()?>&width=135&height=90">
						<?php //vd($media)?>
					</a>
					<div class="delete-wrapper"><a href="#delete_pic" onclick="if(confirm('Удалить картинку?')){deleteMediaItem(<?=$media->id?>);} return false; ">удалить</a></div>
					<img src="/img/preloader.gif" width="30" alt="загрузка..." class="loading" id="loading-<?=$media->id?>" />
				</div>
				<?php 
				}
			}
			elseif($item)
			{?>
				<span style="font-size: .9em; ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;нет фото.</span>
			<?php 
			}?>
			</div>
			<div class="input">Добавить: <input type="file" name="pics[]" multiple="multiple" /></div>
		</div>
		
		<hr />
		
		
		
		
		<div id="price-wrapper">
			Цена: <input type="text" name="price" value="<?=$item->price && $item->price!=0 ? Funx::formatPrice($item->price) : ''?>" style="width:80px; min-width: auto; height: 30px; font-size: 1.3em;  " />
			<?php 
			?>
			<!-- ВАЛЮТА -->
			<?php
			$currencies = $_CONFIG['currencies'];
			if(count($currencies) > 1)
			{
			?>
				<select name="currency" >
				<?php
				foreach($currencies as $cur)
				{?>
					<option value="<?=$cur->code?>" <?=( $item &&  $cur->code == $item->currency->code ? ' selected="selected" ' : '')?>><?=$cur->sign?></option>
				<?php 	
				}?>
				</select>
			<?php 
			}
			else
			{?>
				<input type="hidden" name="currency" value="<?=$currencies[0]->code?>" />
				<span style="font-size: 17px; padding: 0 0 0 3px;  "><?=$currencies[0]->sign?></span>	
			<?php 	
			}?>
			<!-- //ВАЛЮТА -->
			
			<!-- ОБЪЁМ -->
			<?php 
			if(count($chosenCat->productVolumeUnits)>1)
			{?>
				<select name="productVolumeUnitId" >
				<?php 
				foreach($chosenCat->productVolumeUnits as $unit)
				{?>
					<option value="<?=$unit->id?>" <?=( $item &&  $unit->id == $item->productVolumeUnitId? ' selected="selected" ' : '')?>><?=$unit->name?></option>
				<?php 	
				}?>
				</select>
			<?php 
			}
			elseif($unit= array_pop($chosenCat->productVolumeUnits))
			{?>
				<input type="hidden" name="productVolumeUnitId" value="<?=$unit->id?>" />
				<span style="padding: 0 0 0 5px; "><?=$unit->name?></span>
			<?php 	
			}?>
			
			<div class="hint">Пожалуйста, укажите стоимость, и объём товара за эту сумму.</div>
			<!-- //ОБЪЁМ --> 
		</div>
		
	</div>
	

	
	<div class="cell right">
		<h3>КОНТАКТНАЯ ИНФОРМАЦИЯ</h3>
		<div class="row">
			<div class="label">Контактное лицо: </div>
			<div class="input"><input type="text" name="contactName" id="contactName" value="<?=$item ? $item->contactName : $user->fullName?>" /></div>
		</div>
		<div class="row">
			<div class="label">Телефоны<span class="req">*</span>: </div>
			<div class="input"><textarea name="phone" id="phone" style="height: 40px; "><?=$item ? $item->phone : $user->phone?></textarea></div>
			<div class="hint">Пожалуйста, введите актуальные номера телефонов,<br>именно по ним с Вами будут пытаться связаться насчёт этого объявления.</div>
		</div>
		<div class="row">
			<div class="label">E-mail: </div>
			<div class="input"><input type="text" name="email" id="email" value="<?=$item ? $item->email : $user->email?>" /></div>
			<div class="hint">Вы можете указать e-mail, по которому с Вами можно связаться по этому объявлению.</div>
		</div>
		
		
		<?php
		if(count($chosenCat->class->props))
		{?>
			<div class="dop-info" id="dop-info" style="display: <?=!$item || $item->dealType->code==DealType::SELL ? 'block' : 'none'?>; ">
				<hr />	
				<h3>УКАЖИТЕ ДАННЫЕ</h3>
			<?php 
			//vd($item);
			foreach($chosenCat->class->props as $prop)
			{?>
				<div class="row">
					<div class="label"><?=$prop->nameOnSite?><?=$prop->required ? '<span class="req">*</span>' : ''?>: </div>
					<div class="input"><?=$prop->frontendInput($item->propValues[$prop->code], LANG)?></div>
					<div class="hint"></div>
				</div>
			<?php 	
			}?>
			</div>
		<?php 	
		}?>
		
		
		
	</div>
	
	
	
	<p></p>
	
	<input type="submit" value="СОХРАНИТЬ" />
	<span class="info"></span>
	<span class="loading" style="display: none; ">Загрузка...</span>
	
</form>

<div class="success">
	<?php 
	if($item)
	{?>
		Изменения сохранены! После модерации объявление появится на сайте.
	<?php 
	}
	else 
	{?>
		<h3>Объявление успешно добавлено! И после модерации появится на сайте.</h3>
	<?php 
	}?>
	
	<div class="warnings"></div>
	
	<div style="margin: 10px 40px ">
		<a href="WILL_COME_FROM_SCRIPT" id="view-adv-btn"><i class="fa fa-newspaper-o"></i> Посмотреть объявление</a><br>
		<a href="<?=Route::getByName(Route::CABINET_MY_ADVS)->url()?>"><i class="fa fa-list"></i> К моим объявлениям</a><br>
	</div>
	
	
	
</div>




<iframe  name="frame4" style="display: none; width: 90%; height: 500px; border: 1px dashed #000; background: #eee;"></iframe>
