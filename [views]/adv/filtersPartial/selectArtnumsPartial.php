<?php
$artnums = $MODEL['artnums'];
$chosenArtnum = $MODEL['chosenArtnum'];
$otherOption = $MODEL['otherOption'];
$otherOptionSelected = $MODEL['otherOptionSelected'];
?>


<select name="artnumId" id="filter-artnums">
	<option value="">-выберите-</option>
<?php
foreach($artnums as $key=>$artnum)
{?>
	<option value="<?=$artnum->id?>" <?=( $artnum->id == $chosenArtnum->id ? ' selected="selected" ' : "" )?> ><?=$artnum->name?></option>
<?php 
}?>

<?php 
if($otherOption)
{?>
	<option value="other" <?=$otherOptionSelected ? ' selected="selected" ' : ''?>>Другое..</option>
<?php 
}?>
</select>