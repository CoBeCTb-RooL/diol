<?php
$cat = $MODEL['cat'];
$items = $MODEL['items']; 
$totalCount = $MODEL['totalCount']; 
$dealType = $MODEL['dealType'];
$brands = $MODEL['brands'];
$chosenBrand = $MODEL['chosenBrand']; 
$artnums = $MODEL['artnums'];
$chosenArtnum = $MODEL['chosenArtnum'];

$cities = $MODEL['cities'];
$city = $MODEL['city'];
//vd($chosenArtnum);
//vd($MODEL);
//vd($chosenBrand);
//vd($cat);
//vd(Route::getByName(Route::ARTNUMS_LIST_BY_BRAND));
?>


<?php ob_start()?>
<script>

var CHOSEN_CAT=<?=$cat ? $cat->id : '""'?>;
var CHOSEN_BRAND='';

function filterSubmit()
{
	$('#filter-form').submit()
}


function switchType(type)
{
	//alert(type)
	$('.deal-types >*').removeClass('active');
	if(type!='')
		$('#type-'+type).addClass('active')
	else
		$('#type-all').addClass('active')

	$('#filter-form input[name="type"]').val(type)
}	




function switchBrand(brandId)
{
	if(brandId=='')
		$('#filter-artnums').slideUp('fast')
	else
	{
		CHOSEN_BRAND=brandId
		$.ajax({
			url: "<?=Route::getByName(Route::ARTNUMS_LIST_BY_BRAND)->url()?>",
			data:  "cat="+CHOSEN_CAT+"&brand="+CHOSEN_BRAND,
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

</script>
<?php $CONTENT->sectionHeader .= ob_get_clean()?>


<?php
if($cat || 1)
{?>

<!--крамбсы-->
<? Core::renderPartial(SHARED_VIEWS_DIR.'/crumbs.php', $MODEL['crumbs']);?>
<!--//крамбсы-->



	<h1 style="vertical-align: middle; ">
		<?=$cat ? $cat->name : 'Объявления'?>
		<a href="<?=Route::getByName(Route::CABINET_ADV_EDIT)->url($cat ? 'cat_'.$cat->urlPiece() : null)?>" class="btn-square btn-blue btn-s" style="letter-spacing: 0px; display: inline-block; margin: -5px 0 0 10px; vertical-align: middle;  ">+ Подать объявление</a>
	</h1>
	
	
	
	
	<!--фильтры-->
	<?php 
	if($cat->class || 1)
	{?>
	<div class="filters">
		<form id="filter-form" action="?" method="get" onsubmit="return filterSubmit(); return false; ">
			<input type="hidden" name="type" value="<?=$dealType->code?>" />
			<h1>Фильтры:</h1>
			
		
			<!--тип-->
			<div class="prop deal-types switch-btns-wrapper" style="text-align: center; ">
			<?php
			foreach(DealType::$items as $dt)
			{?>
				<a href="#type_<?=$dt->code?>" class="<?=$dealType->code==$dt->code ? ' active' : ''?> " id="type-<?=$dt->code?>" onclick="switchType('<?=$dt->code?>'); return false; " ><?=$dt->title?></a>	
			<?php 
			}?>
				<a href="#type_all" onclick="switchType(''); return false;" id="type-all" class="<?=!$dealType ? ' active' : ''?> " style="font-size: .9em; ">ВСЕ</a>	
			</div>
			<!--тип-->
			
			<!--поисковая фраза-->
			<?
			if(1)
			{?>
			<div class=" prop" id="filter-search" style="position: relative;" >
				<div class="label">Поисковое слово:</div>
				<div class="input cities-wrapper">
					<input type="text" name="s" value="<?=$MODEL['s']?>" />
				</div>
			</div>
			<?php 
			}?>
			<!--//поисковая фраза-->
			
			
			<!--города-->
			<?
			if(count($cities))
			{?>
			<div class=" prop" id="filter-brands" style="position: relative;" >
				<div class="label">Город:</div>
				<div class="input cities-wrapper">
					<select name="cityId" id="cityId">
						<option value="">-выберите-</option>
					<?php 
					foreach($cities as $c)
					{?>
						<option value="<?=$c->id?>" <?=$c->id == $city->id ? ' selected="selected" ':''?> style="<?=$c->isLarge ? 'font-size: 17px; font-weight: bold; ' : ' font-size: 15px; '?>"><?=$c->name?></option>
					<?php 	
					}?>
					</select>
				</div>
			</div>
			<?php 
			}?>
			<!--//города-->
			
			
			<div style="height: 20px; "></div>
			
			
			<?php 
			if($cat)
			{?>
				<!--бренды-->
				<?
				if(count($brands) || 1)
				{?>
				<div class=" prop" id="filter-brands" style="position: relative;" >
					<img src="/img/preloader.gif" alt="загрузка.." id="filter-artnums-preloader" style="width: 30px; position: absolute; top: 22px; left: -32px; display: none; " />
					<div class="label">Производитель:</div>
					<div class="input brands-wrapper">
						<?php Core::renderPartial(Adv::FRONTEND_CONTROLLER.'/filtersPartial/selectBrandsPartial.php', $a=array('brands'=>$brands, 'chosenBrand'=>$chosenBrand, 'onChange'=>'switchBrand($(\'option:selected\', this).val())'))?>
					</div>
				</div>
				<?php 
				}?>
				<!--//бренды-->
				
				
				
				
				<!--арт.номера-->
				<div class=" prop" id="filter-artnums" style="display: <?=(!count($artnums) ? 'none' : 'block')?>; position: relative;  " >
					
					<div class="label">Артикульный номер:</div>
					<div class="input artnums-wrapper">
						<?php Core::renderPartial(Adv::FRONTEND_CONTROLLER.'/filtersPartial/selectArtnumsPartial.php', $a=array('artnums'=>$artnums, 'chosenArtnum'=>$chosenArtnum))?>
					</div>
				</div>
				<!--//арт.номера-->

				<div style="height: 20px; "></div>

				<?
				foreach($cat->class->props as $key=>$prop)
				{
					if($prop->type == 'table')
						continue; 
					//vd($prop->type);?>
					<div class="prop">
						<div class="label"><?=$prop->nameOnSite?>:</div>
						<div class="input">
						<?php
						switch($prop->type)
						{
							case "smalltext":?>
							<input type="text" name="<?=$prop->code?>" value="<?=$_REQUEST[$prop->code]?>" class="<?=$prop->type?>" />
								<?php 
								break;
								
							case "num":?>
								<input type="text" name="<?=$prop->code?>" value="<?=$_REQUEST[$prop->code]?>" size="2" class="<?=$prop->type?>" />
								<?php 
								break;
								
							case "select":
								?>
								<select name="<?=$prop->code?>[]" <?=$prop->multiple ? ' multiple="multiple" ' : ''?> class="<?=$prop->type?>" >
									<option value="">-выберите-</option>
								<?php
								foreach($prop->options as $key=>$opt)
								{?>
									<option value="<?=$opt->id?>" <?=in_array($opt->id, $_REQUEST[$prop->code]) ? ' selected="selected" ' : '' ?>><?=$opt->value?></option>
								<?php
								}?>
								</select>
								<?php  
								break;
						} 
						?>
						</div>
					</div>
				<?php 
				}?>
			
			
			<?php 
			}?>
			
			<input type="submit" value="Искать" name="go" />
			<input type="reset" value="Сбросить"  onclick="location.href='<?=$_SERVER['PATH_INFO']?>';  return false; " />
		</form>
	</div>
	<?php 
	}?>
	<!--//фильтры-->
	
	
	
	
	
	
	
	
	<div  class="adv-list">
		<!--<a href="javascript:history.go(-1)">&larr; назад</a><p>-->
		<?php
		if(count($items))
		{?>
			<div class="heading">Найдено <b><?=$totalCount?> <?=Funx::okon($totalCount, array('объявлений', 'объявление', 'объявления'))?></b>: </div>
			
			<div class="wrapper">
			<?php 
			foreach($items as $key=>$item)
			{?>
				<div class="row">
					<div class="item" >
						
						<div class="cell pic">
							<a href="<?=$item->url()?>" title="<?=$item->name?>">
								<img class="img-desktop" src="<?=$item->media[0] ? $item->media[0]->resizeSrc(): AdvMedia::noPhotoSrc()?>&width=130&height=100" alt="<?=$item->name?>">
								<img class="img-mobile" src="<?=$item->media[0] ? $item->media[0]->resizeSrc(): AdvMedia::noPhotoSrc()?>&width=600&height=400" alt="<?=$item->name?>">
							</a>
						</div>
						<div class="cell info">
							<div class="date"><?=mb_strtolower(Funx::mkDate($item->dateCreated, 'with_time_without_seconds'), 'utf-8')?></div>
							<a href="<?=$item->url()?>" class="title"><?=$item->name?></a>
							<div class="details">
								<div><span class="label">Категория: </span> <?=$item->cat->name?></div>
								<?php 
								if($item->brand)
								{?>
								<div><span class="label">Бренд: </span> <?=$item->brand->name?></div>
								<?php 
								}?>
								
								
								
							</div>
							
						</div>
						
						<div class="cell price">
						<!--<?php 
						if($item->price > 0)
						{?>
							<div>
								<span class="value"><?=Funx::formatPrice($item->price)?> <?=$item->currency->sign?></span> 
								<span class="volume"><?=$item->productVolumeUnit->name?></span>
							</div>
						<?php 
						}
						else
						{?>
							<div class="no-price">Цена не указана</div>
						<?php 	
						}?>-->
						<!-- цена -->
						<?=Core::renderPartial(SHARED_VIEWS_DIR.'/priceMiniPartial.php', $arr = array('item'=>$item))?>
						<!-- //цена -->
						</div>
					
						
					</div>
				</div>
				
			<?php 	
			}?>
			</div>
			<div ><?=Funx::drawPages($MODEL['totalCount'], $MODEL['page'], $MODEL['elPP']);?></div>
		<?php 
		}
		else
		{?>
			Объявлений не найдено. 
			<p><a href="<?=Route::getByName(Route::SPISOK_KATEGORIY)->url()?>" >&larr; Назад к категориям</a>
		<?php 	
		} 
		?>
	</div>
<?php 	
} 
else
{?>
	Категория не найдена. 
<?php 	
}
?>