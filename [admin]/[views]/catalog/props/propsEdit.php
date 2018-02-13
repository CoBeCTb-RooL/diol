<?php
$prop = $MODEL['prop'];
$edit = $prop ? true : false;

if(!$edit)	
{
	$titlePrefix = 'Поле';
	$titlePostfix = ' : добавление';
}
else
{
	$titlePrefix = $prop->name;
	$titlePostfix = ' : редактирование';
}
?>



<?php
if($prop || 1)
{?>
	<div class="view" >
		<form id="edit-form" method="post" action="/<?=ADMIN_URL_SIGN?>/catalog/props/editSubmit" target="frame_type_edit" onsubmit="Slonne.Catalog.Props.propsEditSubmitStart();" >	
			<input type="hidden" name="id" value="<?=$prop->id?>">
				<h1><?=$titlePrefix?><span class="title-gray"><?=$titlePostfix?></span></h1>
					
				
				<div class="field-wrapper">
					<span class="label">АКТИВЕН: </span>
					<span class="value">
						<input type="checkbox" name="active" <?=$prop->active || !$edit ? ' checked="checked" ' : ''  ?> />
					</span>
					<div class="clear"></div>
				</div>
				
				
				<div class="field-wrapper">
					<span class="label">Название<span class="required">*</span>: </span>
					<span class="value">
						<input type="text" name="name" value="<?=htmlspecialchars($prop->name)?>" >
					</span>
					<div class="clear"></div>
				</div>
				
				
				<div class="field-wrapper">
					<span class="label">Название на сайте<span class="required">*</span>: </span>
					<span class="value">
						<input type="text" name="nameOnSite" value="<?=htmlspecialchars($prop->nameOnSite)?>" >
					</span>
					<div class="clear"></div>
				</div>
				
				
				<div class="field-wrapper">
					<span class="label">Код<span class="required">*</span>: </span>
					<span class="value">
						<input type="text" name="code" value="<?=htmlspecialchars($prop->code)?>" <?=$prop ? ' disabled="disabled" ' : ''?>>
					</span>
					<div class="clear"></div>
				</div>
				
				
				
				<div class="field-wrapper">
					<span class="label">Тип<span class="required">*</span>: </span>
					<span class="value">
						<select name="type" onchange="Slonne.Fields.changeFieldType(this.value)" <?=$prop ? ' disabled="disabled" ' : ''?>>
							<option value="">-выберите-</option>
						<?php
						foreach(Prop::$types as $typeCode=>$name)
						{?>
							<option value="<?=$typeCode?>" <?=($prop->type == $typeCode ? ' selected="selected" ' : '')?>><?=$name?></option>
						<?php 	
						} 
						?>
						</select>
						
						<!--доп параметры-->
						<div class="dop-info smalltext num">
							Длинна строки: <input type="text" name="size"  value="<?=($prop->size ? $prop->size : Prop::$defaultFieldsPresets['smalltext']['size']) ?>" size="2">
						</div>
						
						<?php
						if($prop->type == 'text')
						{
							list($w, $h) = explode('x', $prop->size);
							$w = $w ? $w : Prop::$defaultFieldsPresets['text']['width'];
							$h = $h ? $h : Prop::$defaultFieldsPresets['text']['height'];
						}?>
						<div class="dop-info text">
							Размер: <input type="text" name="width" value="<?=$w?>" size="2"> x <input type="text" name="height" id="height" value="<?=$h?>" size="2">
						</div>
						
						<div class="dop-info select">
							<label>Мульти-выбор <input type="checkbox" name="select_multiple" id="select_multiple"  <?=($prop->multiple && $prop->type == 'select'?' checked="checked" ':'')?>></label><br>
							
							<?php 
							if($prop && $prop->type=='select')
							{?>
								<div style="margin: 10px 0;">
								<?php 
								if(count($prop->options) )
								{
									$i=0;
									?>
									<style>
										.opts td{font-size: 11px;}
										.opts input[type="button"]{font-size: 11px;}
									</style>
									
									<table border="1" class="t opts">
									<?php 
									foreach($prop->options as $key=>$opt)
									{?>
										<tr id="opt-row-<?=$opt->id?>" class="<?=$opt->active ? '' : 'inactive'?>" ondblclick="$('#opt-<?=$opt->id?>-value').slideUp(); $('#opt-<?=$opt->id?>-input').slideDown(); ">
											<td width="1" style="font-weight: normal; font-size: 10px; "><?=(++$i)?>. </td>
											<td width="1" style="font-weight: bold; "><?=($opt->id)?> </td>
											<td style="width: 240px; position: relative; ">
												<div id="opt-<?=$opt->id?>-value"><?=$opt->value?></div>
												<div id="opt-<?=$opt->id?>-input" style="display: none; ">
													<input  type="text"  value="<?=addslashes($opt->value)?>"> 
													<input type="button" value="OK" onclick="Slonne.Catalog.Props.propsOptionEdit(<?=$opt->id?>)" > 
													<input type="button" value="Отмена" onclick="$('#opt-<?=$opt->id?>-value').slideDown(); $('#opt-<?=$opt->id?>-input').slideUp(); " >
												</div>
											</td>
											<td ><a href="#propDelete" onclick="if(confirm('Вы уверены, что хотите удалить опцию БЕЗВОЗВРАТНО? ')){Slonne.Catalog.Props.propsOptionDelete(<?=$opt->id?>);} return false; " >&times;</a></td>
										</tr>
									<?php 	
									}
									?>
									</table>
								<?php 
								}
								else 
								{?>
									<b>Опций нет.</b>
								<?php 
								}
								?>
								</div>
							<?php 
							}?>
							
							Введите опции через ENTER:<br>
							<textarea name="options" ></textarea>
							<br>
							
							
						</div>
						
						<div class="dop-info pic">
							<label>Несколько картинок <input type="checkbox" name="pic_multiple" <?=($prop->multiple && $prop->type =='pic' ? ' checked="checked" ':'')?>></label>
						</div>
						
						<div class="dop-info date">
							<label>Отображать время <input type="checkbox" name="withTime" <?=($prop->withTime && $prop->type =='date' ? ' checked="checked" ':'')?>></label>
						</div>
						
						<script>Slonne.Fields.changeFieldType('<?=$prop->type?>')</script>
						
						<!--доп параметры-->
					</span>
					<div class="clear"></div>
				</div>
				
				
				
				<div class="field-wrapper">
					<span class="label">Обязательное: </span>
					<span class="value">
						<input type="checkbox" name="required" <?=($prop->required ? ' checked="checked" ' : '')?>>
					</span>
					<div class="clear"></div>
				</div>
				
				
				

			
			<input type="submit" value="Сохранить">
				
			<div class="loading" style="display: none;">Секунду...</div>
			<div class="info"></div>
		</form>
	</div>
	<iframe name="frame_type_edit" style="display: ; width: 98%; border: 1px dashed #0e0e0e; height: 400px;">1</iframe>
	
<?php 	
}
else 
{
	echo 'Модуль не найден! ['.$_REQUEST['id'].']';
}
?>

