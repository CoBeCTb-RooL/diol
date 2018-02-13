<?php
$field = $MODEL['field'];
$essence = $MODEL['essence'];
$type = $MODEL['type'];

$titlePrefix = 'Сущность';
$titlePostfix = ' : добавление';

?>

<?php
if($essence)
{?>
<div class="view" >
	<form id="field-edit-form" method="post" action="/<?=ADMIN_URL_SIGN?>/field/editSubmit" target="frame1" onsubmit="Slonne.Fields.editSubmitStart();" >	
		<input type="hidden" name="id" value="<?=$field->id?>">
		<input type="hidden" name="pid" value="<?=$essence->id?>">
		<input type="hidden" name="ownerType" value="<?=$type?>">
		
			<h1><?=$titlePrefix?><span class="title-gray"><?=$titlePostfix?></span></h1>
				
			
				<div class="field-wrapper">
					<span class="label">Название<span class="required">*</span>: </span>
					<span class="value">
						<input type="text" name="name" value="<?=htmlspecialchars($field->name)?>" >
					</span>
					<div class="clear"></div>
				</div>
				
				<div class="field-wrapper">
					<span class="label">Код<span class="required">*</span>: </span>
					<span class="value">
						<input type="text" name="code" value="<?=htmlspecialchars($field->code)?>" <?=$field ? ' disabled="disabled" ' : ''?>>
					</span>
					<div class="clear"></div>
				</div>
				
				
				
				<div class="field-wrapper">
					<span class="label">Тип<span class="required">*</span>: </span>
					<span class="value">
						<select name="type" onchange="Slonne.Fields.changeFieldType(this.value)" <?=$field ? ' disabled="disabled" ' : ''?>>
							<option value="">-выберите-</option>
						<?php
						foreach(Field2::$types as $typeCode=>$name)
						{?>
							<option value="<?=$typeCode?>" <?=($field->type == $typeCode ? ' selected="selected" ' : '')?>><?=$name?></option>
						<?php 	
						} 
						?>
						</select>
						
						<!--доп параметры-->
						<div class="dop-info smalltext">
							Длинна строки: <input type="text" name="size"  value="<?=($field->size ? $field->size : Field2::$defaultFieldsPresets['smalltext']['size']) ?>" size="2">
						</div>
						
						<?php 
						if($field->type == 'text')
						{
							list($w, $h) = explode('x', $field->size);
							$w = $w ? $w : Field2::$defaultFieldsPresets['text']['width'];
							$h = $h ? $h : Field2::$defaultFieldsPresets['text']['height'];
						}?>
						<div class="dop-info text">
							Размер: <input type="text" name="width" value="<?=$w?>" size="2"> x <input type="text" name="height" id="height" value="<?=$h?>" size="2">
						</div>
						
						<div class="dop-info select">
							Введите опции через ENTER:<br>
							<textarea name="options" ><?=join("\r\n", $field->options)?></textarea>
							<br>
							<label>Мульти-выбор <input type="checkbox" name="select_multiple" id="select_multiple"  <?=($field->multiple && $field->type == 'select'?' checked="checked" ':'')?>></label>
						</div>
						
						<div class="dop-info pic">
							<label>Несколько картинок <input type="checkbox" name="pic_multiple" <?=($field->multiple && $field->type =='pic' ? ' checked="checked" ':'')?>></label>
						</div>
						
						<div class="dop-info date">
							<label>Отображать время <input type="checkbox" name="withTime" <?=($field->withTime && $field->type =='date' ? ' checked="checked" ':'')?>></label>
						</div>
						
						<script>Slonne.Fields.changeFieldType('<?=$field->type?>')</script>
						
						<!--доп параметры-->
					</span>
					<div class="clear"></div>
				</div>
				
				
				
				<div class="field-wrapper">
					<span class="label">Обязательное: </span>
					<span class="value">
						<input type="checkbox" name="required" <?=($field->required ? ' checked="checked" ' : '')?>>
					</span>
					<div class="clear"></div>
				</div>
				
				<div class="field-wrapper">
					<span class="label">Отображать в списке: </span>
					<span class="value">
						<input type="checkbox" name="displayed" <?=($field->displayed ? ' checked="checked" ' : '')?>>
					</span>
					<div class="clear"></div>
				</div>
				
				<div class="field-wrapper">
					<span class="label">Отмечено: </span>
					<span class="value">
						<input type="checkbox" name="marked" <?=($field->marked ? ' checked="checked" ' : '')?>>
					</span>
					<div class="clear"></div>
				</div>
				
				
				
			
			
		
		<input type="submit" value="Сохранить">
			
		<div class="loading" style="display: none;">Секунду...</div>
		<div class="info"></div>
	</form>
</div>
<?php 
}?>