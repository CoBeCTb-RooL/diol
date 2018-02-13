<?php
$item = $MODEL['item'];
$error = $MODEL['error'];
//vd($item);
$title = $item ? 'Редактирование:' : 'Создание:';
?>



<?php 
if(!$error)
{?>
	<div class="view">
		<h1><?=$title?></h1>
		<form id="form" method="" action="" onsubmit="saveCity(); return false; ">
			<input type="hidden" name="id" value="<?=$item->id?>" />
			<div class="field-wrapper">
				<span class="label">Название<span class="required">*</span>: </span>
				<span class="value"><input type="text" name="name" value="<?=htmlspecialchars($item->name)?>"></span>
			</div>
			<div class="field-wrapper">
				<span class="label">Крупный? </span>
				<span class="value"><input type="checkbox" name="isLarge" <?=$item->isLarge ? ' checked="checked" ' : ''?>></span>
			</div>
			<p>
			<input type="submit" value="сохранить" />
		</form>
	</div>
<?php 	
}
else
{?>
	<?=$error?>
<?php 	
}?>