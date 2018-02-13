<?php 
$cat = $MODEL['cat'];
$classes = $MODEL['classes'];
$currentCatId = $_REQUEST['currentCat'];
?>



<style>

</style>


<h1><?=$cat ? $cat->name : 'Категория'?><span class="title-gray"> : <?=$cat ? 'Редактирование' : 'Добавление'?></span></h1>

<form id="cat-edit-form" enctype="multipart/form-data" method="post" action="/<?=ADMIN_URL_SIGN?>/adv/cats/catEditSubmit" target="frame6" onsubmit="return catEditCheck(); " >
	<input type="hidden" name="catId" value="<?=$cat->id?>" />
	
	
	<div class="field-wrapper">
		<span class="label" >Активен: </span>
		<span class="value" >
			<input type="checkbox" name="active" <?=$cat->status->code == Status::code(Status::ACTIVE)->code || !$cat ? ' checked="checked" ' : ''?>>
		</span>
		<div class="clear"></div>
	</div>
	
	<div class="field-wrapper">
		<span class="label" >Название<span class="required">*</span>: </span>
		<span class="value" >
			<input type="text" name="name" value="<?=$cat->name?>" />
		</span>
		<div class="clear"></div>
	</div>
	
	<div class="field-wrapper">
		<span class="label">Расположение: </span>
		<span class="value">
			<select name="pid">
				<option value="0">-КОРЕНЬ-</option>
				<?=AdvCat::drawTreeSelect(0, $self_id=$cat->id,  $idToBeSelected=$cat->pid ? $cat->pid : $currentCatId, $level=0)?>
			</select>
		</span>
		<div class="clear"></div>
	</div>
	
	
	<div class="field-wrapper">
		<span class="label">Класс: </span>
		<span class="value">
			<div style="margin:0 0 10px 0 ;">
				<label>
					<input type="radio" name="classId" value="" checked="checked"> 
					<b style="font-size: 15px;">-без класса-</b>
				</label>
			</div>
			<?php
			//vd($classes);
			foreach($classes as $key=>$class)
			{?>
				<div style="margin:0 0 10px 0 ;">
					<label>
						<input type="radio" name="classId" value="<?=$class->id?>"  <?=($cat->classId == $class->id ? ' checked="checked" ' : '')?> /> 
						<b style="font-size: 12px;"><?=$class->name?> : </b>
						<?php
						$tmp=null;
						
						foreach($class->props as $key=>$prop)
							$tmp[] = $prop->name;
						echo '<span style="font-size: 11px;">'.join(', ', $tmp).'</span>'; 
						?>
					</label>
				</div>
			<?php 	
			} 
			?>
		</span>
		<div class="clear"></div>
	</div>
	
	
	<input type="submit" value="Сохранить" />
	<div class="loading" style="display: none; ">секунду...</div>
	
</form>



<iframe name="frame6" style="display: ; "></iframe>





