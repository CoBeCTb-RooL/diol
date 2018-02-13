<?php
$class = $MODEL['class'];
$props = $MODEL['props'];
$edit = $class ? true : false;

?>


<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>




<style>
	.prop-active{}
	.prop-inactive{opacity: .4;  }
</style>



<h1><?=$class ? $class->name : 'Класс'?><span class="title-gray"> : <?=$class ? 'Редактирование' : 'Добавление'?></span></h1>


	
<form id="class-edit-form" enctype="multipart/form-data" method="post" action="/<?=ADMIN_URL_SIGN?>/classes/editSubmit" target="frame7" onsubmit="classEditSubmitStart(); " >
	<input type="hidden" name="id" value="<?=$class->id?>">

	
	
	<div class="field-wrapper">
		<span class="label">АКТИВЕН: </span>
		<span class="value">
			<input type="checkbox" name="active" <?=$class->status->code == Status::ACTIVE || !$class ? ' checked="checked" ' : ''?> />
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
		<span class="label">Свойства<span class="required">*</span>: </span>
		<span class="value">
			<?php
			foreach($props as $key=>$prop)
			{?>
				<div class="prop-<?=$prop->status->code?>"><label><input type="checkbox" name="props[<?=$prop->id?>]"    <?=($class->props[$prop->id] ? ' checked="checked" ' : '')?>     <?=($prop->status->code!=Status::code(Status::ACTIVE)->code?' disabled="disabled" ' : '')?> > <?=$prop->name?></label></div>
			<?php 	
			} 
			?>
		</span>
		<div class="clear"></div>
	</div>
		
		
				
	<input type="submit" value="Сохранить">
		
	<div class="loading" style="display: none;">Секунду...</div>

</form>

<iframe name="frame7" style="display: none ; ">1</iframe>