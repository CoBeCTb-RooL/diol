<?php
$prop = $MODEL['prop'];
?>


<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>




<style>
	.table-column-status-2{opacity: .4; }
	.insert .delete-table-column{display: none; } 
	.insert .table-column-delete-btn{display: none; }
	.table-column-status-wrapper{text-decoration: none; }
</style>




<!--шаблон для ряда формы ввода столбца-->
<div id="tmpl-add-column" style="display: none; ">
	
	<div style="margin: 0 0 6px 0; " class="table-column-status-_STATUS_  _OPERATION_  " id="table-column-_ID_">
		<span style="font-size: 11px; ">_NUM_. </span> <input type="text" name="_OPERATION_[_ID_]" placeholder="Название столбца" value="_NAME_" /> <input type="text" name="_OPERATION__idx[_ID_]" placeholder="idx" size="1" value="_IDX_" />
		&nbsp;&nbsp;<a href="#" class="table-column-status-wrapper" id="column-status-btn-_ID_" onclick="switchColumnStatus(_ID_)">_STATUSHTML_</a>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" class="table-column-delete-btn" id="table-column-delete-btn-_ID_" onclick="if(confirm('Удалить колонку?')){deleteColumn(_ID_)}">удалить</a>
	</div>
	
</div>
<!--//шаблон для ряда формы ввода столбца-->



<h1><?=$prop ? $prop->name : 'Свойство'?><span class="title-gray"> : <?=$prop ? 'Редактирование' : 'Добавление'?></span></h1>

	
<form id="prop-edit-form" enctype="multipart/form-data" method="post" action="/<?=ADMIN_URL_SIGN?>/props/editSubmit" target="frame7" onsubmit="propEditSubmitStart(); " >
	<input type="hidden" name="id" value="<?=$prop->id?>">
		
		<div class="field-wrapper">
			<span class="label">АКТИВЕН: </span>
			<span class="value">
				<input type="checkbox" name="active" <?=$prop->status->code == Status::code(Status::ACTIVE)->code || !$prop ? ' checked="checked" ' : ''?> />
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
				<select name="type" onchange="changeFieldType(this.value)" <?=$prop ? ' disabled="disabled" ' : ''?>>
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
								<tr id="opt-row-<?=$opt->id?>" class="<?=$opt->status->code == Status::ACTIVE ? '' : 'inactive'?>" ondblclick="$('#opt-<?=$opt->id?>-value').slideUp(); $('#opt-<?=$opt->id?>-input').slideDown(); ">
									<td width="1" style="font-weight: normal; font-size: 10px; "><?=(++$i)?>. </td>
									<td width="1" style="font-weight: bold; "><?=($opt->id)?> </td>
									<td style="width: 240px; position: relative; ">
										<div id="opt-<?=$opt->id?>-value"><?=$opt->value?></div>
										<div id="opt-<?=$opt->id?>-input" style="display: none; ">
											<input  type="text"  value="<?=addslashes($opt->value)?>"> 
											<input type="button" value="OK" onclick="propsOptionEdit(<?=$opt->id?>)" > 
											<input type="button" value="Отмена" onclick="$('#opt-<?=$opt->id?>-value').slideDown(); $('#opt-<?=$opt->id?>-input').slideUp(); " >
										</div>
									</td>
									<td ><a href="#propDelete" onclick="if(confirm('Вы уверены, что хотите удалить опцию БЕЗВОЗВРАТНО? ')){propsOptionDelete(<?=$opt->id?>);} return false; " >&times;</a></td>
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
				
				<div class="dop-info table">
					<div id="columns-wrapper"></div>
					<?php 
					if($prop->type == 'table')
					{?>
						<?php 
						foreach($prop->tableColumns as $col)
						{?>
							<script>typeTableAppendRow('update', <?=json_encode($col)?>)</script>
						<?php 
						}?>	
					<?php 
					}?>
					<br/><input type="button" onclick="typeTableAppendRow('insert')" value="+ столбец" />
				</div>
				
				
				<script>changeFieldType('<?=$prop->type?>')</script>
				
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
	
</form>
	
<iframe name="frame7" style="display: none;">1</iframe>
