<?php
$e = $MODEL['entity'];
$essence = $MODEL['essence'];
$error = $MODEL['error'];
$type = $MODEL['type'];
$lang = $MODEL['lang'];

//vd($e);
//vd($essence);
//vd($essence);
$modelForTopPartial = array('essence'=>$essence, 'entity'=>$e, 'type'=>$type); 
?>



<?php
if(!$error)
{
	?>
	<div class="view">
		
		
		
		<?=Core::renderPartial('entities/entityTopPartial.php', $modelForTopPartial );?>
		
		
		<div class="field-wrapper">
			<span class="label">Активен: </span>
			<span class="value"><?=$e->active ? 'ДА' : 'НЕТ'?></span>
			<div class="clear"></div>
		</div>
		<?php
		
		foreach($essence->fields[$type] as $key=>$field)
		{?>
			<div class="field-wrapper">
				<span class="label"><?=$field->name?>: </span>
				<span class="value"><?=$field->displayValueInView($e->attrs[$field->code], $lang)?></span>
				<div class="clear"></div>
			</div>
			
		<?php 
		} 
		?>
	</div>	
	
<?php 	
}
else 
{
	echo $error;
}
?>

