<?php
$catType = $MODEL['catType'];
$cat = $MODEL['cat'];
$item = $MODEL['item'];
$error = $MODEL['error'];
$id = $MODEL['id'];
$pid = $MODEL['pid'];
$lang = $MODEL['lang'];

$new = $item ? false : true;

//vd($item);

//vd($cat);
$titlePrefix= '';
$titlePostfix = '';
if($new)
{
	$titlePrefix = 'Добавление позиции';
	$titlePostfix = ' : '.$cat->class->name.'';
}
else
{ 
	$titlePrefix = ''.$item->name;
	$titlePostfix = ' : редактирование';
}
	

?>



<?php
if(!$error)
{?>
	<script>
		/*$(document).ready(function(){
			$('#edit-form [name=lang]').val(Slonne.Entities.LANG)
		});*/
	</script>
	<div class="view" >
		<form id="edit-form" enctype="multipart/form-data" method="post" action="/<?=ADMIN_URL_SIGN?>/catalog/interface/itemEditSubmit" target="frame2" onsubmit="Slonne.Catalog.Interface.itemEditSubmitStart()" >	
			
			<input type="hidden" name="id" value="<?=$item->id?>">
			<input type="hidden" name="pid" value="<?=$cat->id?>">
			<input type="hidden" name="lang" value=""><!--<<будет подцеплен яваскриптом-->
			
			
				<h1><?=$titlePrefix?><span class="title-gray"><?=$titlePostfix?></span></h1>
				
				
				<div class="field-wrapper">
					<span class="label" >Активен: </span>
					<span class="value" >
						<input type="checkbox" name="active" <?=($item->active || $new ? ' checked="checked" ' : '')?>>
					</span>
					<div class="clear"></div>
				</div>
			
			
				<div class="field-wrapper">
					<span class="label">Название<span class="required">*</span>: </span>
					<span class="value ">
						<input type="text" name="name" id="name" value="<?=htmlspecialchars($item->name)?>">
					</span>
					<div class="clear"></div>
				</div>
				
				
				<div class="field-wrapper">
					<span class="label">Текст: </span>
					<span class="value ">
						<div><input type="hidden" id="descr" name="descr" value="<?=htmlspecialchars(stripslashes($item->descr))?>"><input type="hidden" id="FCKeditor1___Config" value=""><iframe id="FCKeditor1___Frame" src="/<?=INCLUDE_DIR?>/FCKeditor/editor/fckeditor.html?InstanceName=descr&Toolbar=Slonne" style="min-width: 640px;" width="100%" height="400px" frameborder="no" scrolling="no"></iframe></div>
					</span>
					<div class="clear"></div>
				</div>
					
				
				<?php
				foreach($cat->class->props as $key=>$prop)
				{?>
				<div class="field-wrapper">
					<span class="label"><?=$prop->name?><?=($prop->required ? '<span class="required">*</span>' : '')?>: </span>
					<span class="value <?=$prop->type?>">
						<?=$prop->backendInput($item->propValues[$prop->code], $lang)?>
					</span>
					<div class="clear"></div>
				</div>
				<?php 		
				} 
				?>
				
				
				<div class="field-wrapper" style="margin-top: 55px;">
					<span class="label">Расположение: </span>
					<span class="value pid">
						<select name="pid">
							<option value="0">-КОРЕНЬ-</option>
							<?=Category::drawTreeSelect($catType->code, 0, $self_id = 0,  $idToBeSelected=$cat->id, $level=0 )?>
						</select>
					</span>
					<div class="clear"></div>
				</div>
				
			
			<input type="submit" value="Сохранить">
				
			<span class="loading" style="visibility: hidden">Секунду...</span>
			<div class="info"></div>
		</form>
	</div>
	
	<iframe name="frame2" style="display: ; width: 99%; border: 1px dashed #0e0e0e; height: 400px;">1</iframe>
	
<?php 	
}
else 
{
	echo $error;
}
?>

