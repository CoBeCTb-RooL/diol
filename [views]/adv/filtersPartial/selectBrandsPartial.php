<?php
$brands = $MODEL['brands'];
$chosenBrand = $MODEL['chosenBrand'];  
//vd($MODEL);
?>

<select name="brand" id="brands-wrapper" onchange="<?=$MODEL['onChange']?>">
	<option value="">-выберите-</option>
<?php
foreach($brands as $key=>$brand)
{?>
	<option value="<?=$brand->id?>" <?=( $brand->id == $chosenBrand->id ? ' selected="selected" ' : "" )?> ><?=$brand->name?></option>
<?php 
}?>
</select>