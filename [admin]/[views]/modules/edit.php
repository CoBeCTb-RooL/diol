<?php
$module = $MODEL;
$new = $module ? false : true;

if($new)	
{
	$titlePrefix = 'Модуль';
	$titlePostfix = ' : добавление';
}
else
{
	$titlePrefix = $module->name;
	$titlePostfix = ' : редактирование';
}
?>



<?php
if($module || 1)
{?>
	<div class="view" >
		<form id="edit-form" method="post" action="/<?=ADMIN_URL_SIGN?>/module/editSubmit" target="frame1" onsubmit="Slonne.Modules.editSubmitStart();" >	
			<input type="hidden" name="id" value="<?=$module->id?>">
				<h1><?=$titlePrefix?><span class="title-gray"><?=$titlePostfix?></span></h1>
					<div class="field-wrapper">
						<span class="label">Активен: </span>
						<span class="value" >
							<input type="checkbox" name="active" <?=($module->active || $new ? ' checked="checked" ' : '')?>>
						</span>
						<div class="clear"></div>
					</div>
				
					<div class="field-wrapper">
						<span class="label">Название<span class="required">*</span>: </span>
						<span class="value">
							<input type="text" name="name" value="<?=htmlspecialchars($module->name)?>">
						</span>
						<div class="clear"></div>
					</div>
					
					<div class="field-wrapper">
						<span class="label">Иконка: </span>
						<span class="value">
							<input type="text" name="icon" value="<?=htmlspecialchars($module->icon)?>">
						</span>
						<div class="clear"></div>
					</div>
					
					
					
					<div class="field-wrapper">
						<span class="label">Путь<span class="required">*</span>: </span>
						<span class="value">
							<input type="text" name="path" value="<?=htmlspecialchars($module->path)?>">
						</span>
						<div class="clear"></div>
					</div>
				
					
					
					
					<div class="field-wrapper">
						<span class="label">Действия: </span>
						<span class="value">
							<textarea name="actions" style="height: 140px; width: 400px;"><?=htmlspecialchars($module->actions)?></textarea>
							<br>
							<div class="hint" style="font-size: .8em ;color: #707070; ">
								<b>Через ENTER</b> 
								<br>Пример:
								<br>&nbsp;&nbsp;list=Просмотр
								<br>&nbsp;&nbsp;edit_blocks=Изменение 
							</div>
						</span>
						<div class="clear"></div>
					</div>
				
				
			
			<input type="submit" value="Сохранить">
				
			<div class="loading" style="display: none;">Секунду...</div>
			<div class="info"></div>
		</form>
	</div>
	
<?php 	
}
else 
{
	echo 'Модуль не найден! ['.$_REQUEST['id'].']';
}
?>

