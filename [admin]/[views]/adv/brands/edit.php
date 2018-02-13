<?php
$item = $MODEL['item'];


	
$titlePrefix = 'Бренд';
$titlePostfix = ' : добавление';

if($item->id)
{
	$titlePrefix = $item->name;
	$titlePostfix = ' : редактирование';
}
?>





<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>



<?php
if($item || 1)
{?>
	<div class="view" >
		<form id="edit-form" method="post" enctype="multipart/form-data" action="/<?=ADMIN_URL_SIGN?>/adv/brands/editSubmit" target="frame6" onsubmit="editSubmitStart()" >	
			<input type="hidden" name="id" value="<?=$item->id?>">
				<h1><?=$titlePrefix?><span class="title-gray"><?=$titlePostfix?></span></h1>
					<div class="field-wrapper">
						<span class="label">Активен: </span>
						<span class="value" >
							<input type="checkbox" name="active" <?=($item->status==Status::$items[Status::ACTIVE] || !$item ? ' checked="checked" ' : '')?>>
						</span>
						<div class="clear"></div>
					</div>
				
					<div class="field-wrapper">
						<span class="label">Название<span class="required">*</span>: </span>
						<span class="value">
							<input type="text" name="name" value="<?=htmlspecialchars($item->name)?>">
						</span>
						<div class="clear"></div>
					</div>
					
					<div class="field-wrapper">
						<span class="label">Картинка: </span>
						<span class="value">
							<?php 
							if($item->pic)
							{?>
							<img src="<?=Media::img($item->pic.'&height=100')?>" alt="" /><br/>
							<label >Удалить картинку? <input type="checkbox" name="delete_pic" /></label>
							<p/>
							<?php 
							}?>
							
							<input type="file" name="pic" >
						</span>
						<div class="clear"></div>
					</div>
					
					
					
				
			
			<input type="submit" value="Сохранить">
				
			<div class="loading" style="display: none;">Секунду...</div>
			<div class="info"></div>
		</form>
	</div>
	
	<iframe name="frame6" style="display: none; "></iframe>
<?php 	
}
else 
{
	echo 'Бренд не найден! ['.$_REQUEST['id'].']';
}
?>

