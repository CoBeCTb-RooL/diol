<?php
$cat = $MODEL['cat'];
$catType = $MODEL['catType'];
$error = $MODEL['error'];
$lang = $MODEL['lang'];

//vd($e);
//vd($essence);
//vd($essence);
$modelForTopPartial = array('cat'=>$cat, 'catType'=>$catType, 'lang'=>$lang); 
?>



<?php
if(!$error)
{
	?>
	<div class="view">
		
		
		<?=Core::renderPartial('catalog/interface/catTopPartial.php', $modelForTopPartial)?>
		
		
		<div class="field-wrapper">
			<span class="label">Активен: </span>
			<span class="value"><?=$cat->active ? 'ДА' : 'НЕТ'?></span>
			<div class="clear"></div>
		</div>
		
		
		<div class="field-wrapper">
			<span class="label">Название: </span>
			<span class="value"><?=$cat->name?></span>
			<div class="clear"></div>
		</div>
		
		
	</div>	
	
<?php 	
}
else 
{
	echo $error;
}
?>

