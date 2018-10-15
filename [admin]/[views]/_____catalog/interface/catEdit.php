<?php
$catType = $MODEL['catType'];
$cat = $MODEL['cat'];
$error = $MODEL['error'];
$id = $MODEL['id'];
$pid = $MODEL['pid'];


$new = $cat ? false : true;
//vd($pid);
$pid = $pid ? $pid : $cat->pid;
//vd($pid);
$titlePostfix = '';
if($new)
	$titlePostfix = 'Добавление категории';
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
		<form id="edit-form" enctype="multipart/form-data" method="post" action="/<?=ADMIN_URL_SIGN?>/catalog/interface/catEditSubmit" target="frame2" onsubmit="Slonne.Catalog.Interface.catEditStart()" >	
			<input type="hidden" name="catType" value="<?=$catType->code?>">
			
			<input type="hidden" name="id" value="<?=$cat->id?>">
			<input type="hidden" name="pid" value="<?=$pid?>">
			<input type="hidden" name="lang" value=""><!--<<будет подцеплен яваскриптом-->
			
			
				<h1><?=$cat->name?><span class="title-gray"><?=$titlePostfix?></span></h1>
				
				
				
					<?php	
					if($ADMIN->hasPrivilege($_GLOBALS['CURRENT_MODULE']->id, 'set_untouchability'))
					{?>
					<div class="field-wrapper">
						<span class="label" style="font-size: 1.3em;">Неприкосновенен: </span>
						<span class="value" >
							<input type="checkbox" name="untouchable" <?=($cat->untouchable ? ' checked="checked" ' : '')?>>
						</span>
						<div class="clear"></div>
					</div>
					<?php 	
					} 
					?>
					
				
					<div class="field-wrapper">
						<span class="label" >Активен: </span>
						<span class="value" >
							<input type="checkbox" name="active" <?=($cat->active || $new ? ' checked="checked" ' : '')?>>
						</span>
						<div class="clear"></div>
					</div>
				
				
					<div class="field-wrapper">
						<span class="label">Название<span class="required">*</span>: </span>
						<span class="value ">
							<input type="text" name="name" id="name" value="<?=addslashes($cat->name)?>">
						</span>
						<div class="clear"></div>
					</div>
					
				
				
				
				<?php
				//vd($pid); 
				?>
				<div class="field-wrapper">
					<span class="label">Расположение: </span>
					<span class="value pid">
						<select name="pid">
							<option value="0">-КОРЕНЬ-</option>
							<?=Category::drawTreeSelect($catType->code, 0, $self_id = $cat->id,  $idToBeSelected=$pid, $level=0 )?>
						</select>
					</span>
					<div class="clear"></div>
				</div>
				
			
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

