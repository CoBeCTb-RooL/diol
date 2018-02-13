<?php
$error = $MODEL['error'];
$brandArtnums = $MODEL['brandArtnums'];
$artnumsList = $MODEL['artnumsList']; 
foreach($brandArtnums as $a)
	$brandArtnumIds[] = $a->artnumId;

$fromExcelStr = $MODEL['fromExcelStr'];
//vd($fromExcelStr);
$fromExcelArr= $MODEL['fromExcelArr'];
$found = $MODEL['found'];
//vd($fromExcelArr);
//vd($currentCat); 
//vd($brandIds);

?>




<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>




<style>
	label{display: block; margin: 0 0 9px 0; }
	.status-<?=Status::INACTIVE?>{color: #ccc; }
	.highlighted{background: yellow; }
	.not-found{background: red;}
</style>




<div class="wrapper" >
<?php 
foreach($artnumsList as $key=>$artnum)
{?>
	<label id="artnum-wrapper-<?=$artnum->id?>" class="status-<?=$artnum->status->code?> <?=(in_array($artnum->name, $found) ? 'highlighted' : '')?>"> <input type="checkbox" id="artnum-cb-<?=$artnum->id?>" <?= in_array($artnum->id, $brandArtnumIds) ? ' checked="checked" ' : "" ?> onclick="switchArtnumCheckbox(<?=$artnum->id?>)" /> <?=$artnum->name?> (<?=$artnum->id?>)</label>
<?php 
}?>

	<div class="txt" style="position: fixed; top: 30px; right: 90px; border: 0px solid green; width: 300px; height: 500px; padding: 30px;">
		<h3>Вставьте из Excel</h3>
		<textarea id="from-excel" cols="50" rows="7"><?=$fromExcelStr?></textarea>
		<input type="button" value="go" onclick="changeBrand(CHOSEN_BRAND)"  />
		<div class="info">
		<?php
		if($fromExcelStr)
		{?>
			<p>
			Найдено <?=count($found)?> из <?=count($fromExcelArr)?>:
			<p>
			<div style="max-height: 180px; overflow: auto; ">
			<?php 
			foreach($fromExcelArr as $key=>$val)
			{?>
				<div class="<?=(!in_array($val, $found) ? 'not-found' : '')?>" ><?=$val?></div>
			<?php 
			}?>
			</div>
			<hr />
			<div style="width: 400px;">
				Удачно найденные: <a href="#check" onclick="if(confirm('Уверены?')){changeBrand(CHOSEN_BRAND, 'on')}; return false; ">выделить</a> или <a href="#uncheck" onclick="if(confirm('Уверены?')){changeBrand(CHOSEN_BRAND, 'off')}; return false; ">снять выделение</a>
			</div>
		<?php 
		}?>
		</div>
	</div>
</div>
	
