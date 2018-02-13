<?php
$item = $MODEL['item'];
//$groups = $MODEL['groups'];
$chosenGroup = $MODEL['chosenGroup'];

//vd($chosenGroup);
$error = $MODEL['error'];
//vd($item);
$title = $item ? 'Редактирование:' : 'Создание:';
?>


<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>



<?php 
if(!$error)
{?>
	<div class="view">
		<h1><?=$title?></h1>
		<form id="form" method="" action="" onsubmit="taskSave(); return false; ">
			<input type="hidden" name="id" value="<?=$item->id?>" />
			<input type="hidden" name="groupId" value="<?=$chosenGroup->id?>" />
			<div class="field-wrapper">
				<span class="label">Задача<span class="required">*</span>: </span>
				<span class="value"><textarea name="title" style="width: 500px ; height: 35px; "><?=$item->title?></textarea></span>
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