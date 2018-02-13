<?php
$cats = $MODEL['cats']; 
$type = $MODEL['dealType'];

$cities = $MODEL['cities'];
$city = $MODEL['city'];
//vd($city);
$chosenCityId = $MODEL['chosenCityId'];

?>

<!--крамбсы-->
<? Core::renderPartial(SHARED_VIEWS_DIR.'/crumbs.php', $MODEL['crumbs']);?>
<!--//крамбсы-->


<h1>Объявления</h1>


<div class="city" style="margin: 0 0 15px 0; ">
	Город: 
	<select name="city" onchange="location.href='?cityId='+$(this).val()+'&type=<?=$type->code?>'">
		<option value="">-выберите-</option>
		<?php 
		foreach($cities as $c)
		{?>
			<option value="<?=$c->id?>" <?=$c->id==$city->id?' selected="selected" ':''?> style="<?=$c->isLarge ? 'font-size: 17px; font-weight: bold; ' : ' font-size: 15px; '?>"><?=$c->name?></option>
		<?php 
		}?>
	</select>
	
	
	<span  class="on-advs-list switch-btns-wrapper" style="padding: 0 0 0 11px; ">
		<?php
		foreach(DealType::$items as $code=>$dt)
		{?>
			<a href="?cityId=<?=$city->id?>&type=<?=$code?>" class="<?=$type->code==$dt->code ? ' active' : ''?>" ><?=$dt->title?></a>
		<?php 
		} 
		?>
		<a href="?cityId=<?=$city->id?>" class="<?=!$type ? ' active' : ''?>" >ВСЕ</a>	
	</span>
</div>



<div class="cats-list">
<?php
foreach($cats as $key=>$cat)
{
	$urlRoot = $cat->url().'?'.($type ? 'type='.$type->code : '').($chosenCityId ? '&cityId='.$chosenCityId:'');
	?>
	<div class="section">
		
		<?
		if(count($cat->subCats))
		{?>
			<h2><?=$cat->name?> <?=($cat->advsCount ? '<b>('.$cat->advsCount.')</b>' : '')?></h2>
			<div class="subs" >
			<?php
			foreach($cat->subCats as $key=>$subcat)
			{
				$url = $subcat->url().'?'.($type->code ? '&type='.$type->code :'').($chosenCityId ? '&cityId='.$chosenCityId:'');
				?>
				<div>
					<!-- <a href="<?=$subcat->url()?>?type=<?=$type->code?>&cityId=<?=$chosenCityId?>"> -->
					<a href="<?=$url?>">
						<?=$subcat->name?>
						<?php 
						if($subcat->advsCount)
						{?>
							<sup><?=Funx::numberFormat($subcat->advsCount)?></sup>
						<?php 	
						}?>
					</a>
					
				</div>
			<?php 
			} 
			?>
			</div>
		<?php 	
		}
		else
		{?>
			<h2>
				<a href="<?=$urlRoot?>">
					<?=$cat->name?> 
					<?php 
					if($cat->advsCount)
					{?>
						<sup><?=Funx::numberFormat($cat->advsCount)?></sup>
					<?php 	
					}?>
				</a>
			</h2>
		<?php 	
		}?>
	</div>
<?php 	
} 
?>
</div>