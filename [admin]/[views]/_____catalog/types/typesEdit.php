<?php
$type = $MODEL['type'];
$new = $type ? false : true;

if($new)	
{
	$titlePrefix = 'Тип каталога';
	$titlePostfix = ' : добавление';
}
else
{
	$titlePrefix = $type->name;
	$titlePostfix = ' : редактирование';
}
?>



<?php
if($type || 1)
{?>
	<div class="view" >
		<form id="edit-form" method="post" action="/<?=ADMIN_URL_SIGN?>/catalog/types/editSubmit" target="frame_type_edit" onsubmit="Slonne.Catalog.Types.typeEditSubmitStart();" >	
			<input type="hidden" name="id" value="<?=$type->id?>">
				<h1><?=$titlePrefix?><span class="title-gray"><?=$titlePostfix?></span></h1>
					
				
					<div class="field-wrapper">
						<span class="label">Название<span class="required">*</span>: </span>
						<span class="value">
							<input type="text" name="name" value="<?=htmlspecialchars($type->name)?>">
						</span>
						<div class="clear"></div>
					</div>
					
					<div class="field-wrapper">
						<span class="label">Код<span class="required">*</span>: </span>
						<span class="value">
							<input type="text" name="code" <?=($type ? 'disabled="disabled"' : '')?> value="<?=htmlspecialchars($type->code)?>">
						</span>
						<div class="clear"></div>
					</div>
					
				
			
			<input type="submit" value="Сохранить">
				
			<div class="loading" style="display: none;">Секунду...</div>
			<div class="info"></div>
		</form>
	</div>
	<iframe name="frame_type_edit" style="display: none; width: 98%; border: 1px dashed #0e0e0e; height: 400px;">1</iframe>
	
<?php 	
}
else 
{
	echo 'Модуль не найден! ['.$_REQUEST['id'].']';
}
?>

