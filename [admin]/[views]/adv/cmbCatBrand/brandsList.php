<?php
$error = $MODEL['error'];
$catBrands = $MODEL['catBrands'];
$brandsList = $MODEL['brandsList']; 
foreach($catBrands as $b)
	$catBrandIds[] = $b->brandId;
//vd($currentCat); 
//vd($brandIds);

?>



<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>



<style>
	label{display: block; margin: 0 0 9px 0; }
</style>



<div class="wrapper">
<?php 

foreach($brandsList as $key=>$brand)
{?>
	<label id="brand-wrapper-<?=$brand->id?>" class="status-<?=$brand->status->code?>"> <input type="checkbox" id="brand-cb-<?=$brand->id?>" <?= in_array($brand->id, $catBrandIds) ? ' checked="checked" ' : "" ?> onclick="switchBrandCheckbox(<?=$brand->id?>)" /> <?=$brand->name?> (<?=$brand->id?>)</label>
<?php 
}?>
</div>
