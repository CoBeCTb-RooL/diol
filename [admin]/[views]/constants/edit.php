<?php
$const = $MODEL['const'];

//vd($const);
$new = $const ? false : true;

if($new)	
{
	$titlePrefix = 'Константа';
	$titlePostfix = ' : добавление';
}
else
{
	$titlePrefix = $const->name;
	$titlePostfix = ' : редактирование';
}
?>



<?php
if($const || 1)
{?>
	<div class="view" >
		<form id="edit-form" method="post" action="/<?=ADMIN_URL_SIGN?>/constants/editSubmit" target="frame_const" onsubmit="Slonne.Constants.editSubmitStart();" >	
			<input type="hidden" name="id" value="<?=$const->id?>">
				<h1><?=$titlePrefix?><span class="title-gray"><?=$titlePostfix?></span></h1>
					
					<div class="field-wrapper">
						<span class="label">Константа<span class="required">*</span>: </span>
						<span class="value">
							<input type="text" name="name" value="<?=htmlspecialchars($const->name)?>">
						</span>
						<div class="clear"></div>
					</div>
					
					
		<?php
		foreach($_CONFIG['LANGS'] as $lang=>$val)
		{?>
					<div class="field-wrapper">
						<span class="label"><?=$lang?><span class="required">*</span>: </span>
						<span class="value" style="width: 100%; ">	
							<textarea style="width: 100%; " name="value_<?=$lang?>" ><?=htmlspecialchars($const->value[$lang])?></textarea>
							
						</span>
						<div class="clear"></div>
					</div>
		<?php 
		} 
		?>	
					
					
					
					
				
				
			
			<input type="submit" value="Сохранить">
				
			<div class="loading" style="display: none;">Секунду...</div>
			<div class="info"></div>
		</form>
	</div>
	<iframe name="frame_const" style="display: ; width: 98%; border: 1px dashed #0e0e0e; height: 400px;">1</iframe>
<?php 	
}
else 
{
	echo 'Модуль не найден! ['.$_REQUEST['id'].']';
}
?>

