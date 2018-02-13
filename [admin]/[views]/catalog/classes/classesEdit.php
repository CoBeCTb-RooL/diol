<?php
$class = $MODEL['class'];
$props = $MODEL['props'];
$edit = $class ? true : false;

//vd($class);

if(!$edit)	
{
	$titlePrefix = 'Класс';
	$titlePostfix = ' : добавление';
}
else
{
	$titlePrefix = $class->name;
	$titlePostfix = ' : редактирование';
}
?>



<?php
if($class || 1)
{?>
	<div class="view" >
		<form id="edit-form" method="post" action="/<?=ADMIN_URL_SIGN?>/catalog/classes/editSubmit" target="frame_type_edit" onsubmit="Slonne.Catalog.Classes.classesEditSubmitStart();" >	
			<input type="hidden" name="id" value="<?=$class->id?>">
			<h1><?=$titlePrefix?><span class="title-gray"><?=$titlePostfix?></span></h1>
			
			
			<div class="field-wrapper">
				<span class="label">АКТИВЕН: </span>
				<span class="value">
					<input type="checkbox" name="active" <?=$class->active || !$edit ? ' checked="checked" ' : ''  ?> />
				</span>
				<div class="clear"></div>
			</div>	
			
			<div class="field-wrapper">
				<span class="label">Название<span class="required">*</span>: </span>
				<span class="value">
					<input type="text" name="name" value="<?=htmlspecialchars($class->name)?>" >
				</span>
				<div class="clear"></div>
			</div>
			
			
			<style>
				.inactive {opacity: .5; }
			</style>
			<div class="field-wrapper">
				<span class="label">Поля<span class="required">*</span>: </span>
				<span class="value">
					<?php
					foreach($props as $key=>$prop)
					{?>
						<div class="<?=($prop->active?'':'inactive')?>"><label><input type="checkbox" name="props[<?=$prop->id?>]"    <?=($class->props[$prop->id] ? ' checked="checked" ' : '')?>     <?=($prop->active?'':' disabled="disabled" ')?> > <?=$prop->name?></label></div>
					<?php 	
					} 
					?>
				</span>
				<div class="clear"></div>
			</div>
				
				
						
			<input type="submit" value="Сохранить">
				
			<div class="loading" style="display: none;">Секунду...</div>
			<div class="info"></div>
		</form>
	</div>
	<iframe name="frame_type_edit" style="display: none ; width: 98%; border: 1px dashed #0e0e0e; height: 400px;">1</iframe>
	
<?php 	
}
else 
{
	echo 'Модуль не найден! ['.$_REQUEST['id'].']';
}
?>

