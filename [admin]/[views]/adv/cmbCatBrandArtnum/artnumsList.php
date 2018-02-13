<?php
$error = $MODEL['error'];
$brandArtnums = $MODEL['brandArtnums'];
$artnumsList = $MODEL['artnumsList']; 
$chosenArtnumIds = $MODEL['chosenArtnumIds'];

//vd($currentCat); 
//vd($brandIds);
//vd($chosenArtnumIds);
?>


<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>



<style>
	label{display: block; margin: 0 0 9px 0; }
	/*.status-<?=Status::INACTIVE?>{color: #ccc; }*/
</style>


<script>
	
</script>


<?php
if(!$error)
{?>
	<div class="wrapper">
	<?php
	if(count($artnumsList))
	{ 
		foreach($artnumsList as $key=>$artnum)
		{?>
			<label id="artnum-wrapper-<?=$artnum->id?>" class="status-<?=$artnum->status->code?>"> <input type="checkbox" id="artnum-cb-<?=$artnum->id?>" <?= in_array($artnum->id, $chosenArtnumIds) ? ' checked="checked" ' : "" ?> onclick="switchArtnumCheckbox(<?=$artnum->id?>)" /> <?=$artnum->name?> (<?=$artnum->id?>)</label>
		<?php 
		}
	}
	else
		echo 'Арт. номеров не сопоставлено. ';?>
	</div>
<?php 	
}
else
	echo $error;  
?>