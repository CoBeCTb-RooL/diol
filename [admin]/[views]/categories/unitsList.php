<?php
$cat = $MODEL['cat'];
$units = $MODEL['units'];
//vd($cat);
?>


<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>


<h1><span style="font-weight: normal; ">Меры объёма для</span> <b>"<?=$cat->name?>"</b></h1>
<?php 
if(count($units))
{?>
	<?php 
	foreach($units as $u)
	{?>
		<div class="unit-status-<?=$u->status->code?>">
			<label ><input type="checkbox" name="u" id="unit-<?=$u->id?>" onclick="unitClick(<?=$u->id?>, <?=$cat->id?>)" <?=$cat->productVolumeUnits[$u->id] ? ' checked="checked" ' : ''?> /> <?=$u->name?></label>
		</div>
	<?php 	
	}?>	
<?php 	
}
else
{?>
	В базе нет единиц.
<?php 	
}?>