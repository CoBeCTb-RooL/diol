<?php
$e = $MODEL['entity'];
$essence = $MODEL['essence'];
$error = $MODEL['error'];
$type = $MODEL['type'];
$pid = $MODEL['pid'];
$lang = $MODEL['lang'];

$new = $e ? false : true;


$titlePostfix = '';
if($new)
	if($type == Entity2::TYPE_BLOCKS)
		$titlePostfix = 'Добавление блока';
	else 	
		$titlePostfix = 'Добавление элемента';
else 
	$titlePostfix = ' : редактирование';

?>



<?php
if(!$error)
{?>
	<script>
		$(document).ready(function(){
			$('#edit-form [name=lang]').val(Slonne.Entities.LANG)
		});
	</script>
	<div class="view" >
		<form id="edit-form" enctype="multipart/form-data" method="post" action="/<?=ADMIN_URL_SIGN?>/entity/editSubmit2" target="frame2" onsubmit="Slonne.Entities.editStart()" >	
			<input type="hidden" name="essenceCode" value="<?=$essence->code?>">
			<input type="hidden" name="type" value="<?=$type?>">
			<input type="hidden" name="id" value="<?=$e->id?>">
			<input type="hidden" name="pid" value="<?=$pid?>">
			<input type="hidden" name="lang" value=""><!--<<будет подцеплен яваскриптом-->
			
			
				<h1><?=$e->attrs['name']?><span class="title-gray"><?=$titlePostfix?></span></h1>
				
				<!--<a href="#edit" onclick="Slonne.Entities.treeNameClick('<?=$e->id ? $e->id : $_REQUEST['LAST_VIEWED']?>'); ; return false;">&larr; назад</a>-->
				
				
					<?php	
					//vd($e);
					if($ADMIN->hasPrivilege($_GLOBALS['CURRENT_MODULE']->id, 'set_untouchability'))
					{?>
					<div class="field-wrapper">
						<span class="label" style="font-size: 1.3em;">Неприкосновенен: </span>
						<span class="value" >
							<input type="checkbox" name="untouchable" <?=($e->untouchable ? ' checked="checked" ' : '')?>>
						</span>
						<div class="clear"></div>
					</div>
					<?php 	
					} 
					?>
					
				
					<div class="field-wrapper">
						<span class="label" >Активен: </span>
						<span class="value" >
							<input type="checkbox" name="active" <?=($e->active || $new ? ' checked="checked" ' : '')?>>
						</span>
						<div class="clear"></div>
					</div>
				
				<?php
				foreach($essence->fields[$type] as $key=>$field)
				{?>
					<div class="field-wrapper">
						<span class="label"><?=$field->name?><?=($field->required ? '<span class="required">*</span>' : '')?>: </span>
						<span class="value <?=$field->type?>">
							<?=$field->drawInput($e->attrs[$field->code], $lang)?>
						</span>
						<div class="clear"></div>
					</div>
					
				<?php 
				} 
				?>
				
				
				<?php
				//vd($lang);
				if(!$essence->attrs['linear'])
				{?>
				<div class="field-wrapper">
					<span class="label">Расположение: </span>
					<span class="value pid">
						<select name="pid">
							<option value="0">-КОРЕНЬ-</option>
							<?=Field2::drawTreeSelect($essence, 0, $self_id = ($type == Entity2::TYPE_ELEMENTS && !$essence->jointFields ? null : $e->id ),  $idToBeSelected=$pid, $level=0, $lang )?>
						</select>
					</span>
					<div class="clear"></div>
				</div>
				<?php 
				}?>
				
			
			<input type="submit" value="Сохранить">
				
			<span class="loading" style="visibility: hidden">Секунду...</span>
			<div class="info"></div>
		</form>
	</div>
	
	<iframe name="frame2" style="display: none; width: 99%; border: 1px dashed #0e0e0e; height: 400px;">1</iframe>
	
<?php 	
}
else 
{
	echo $error;
}
?>

