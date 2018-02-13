<?php 
$e = $MODEL['entity'];
$essence = $MODEL['essence'];
$type = $MODEL['type'];
//vd($MODEL);
//vd($e);
//vd($type);
//vd($essence);

#	меню в шапке может относиться только к блоку (или элементу только в случае jointFields)
if(!$essence->jointFields)
	$type=Entity2::TYPE_BLOCKS;
?>


<style>
.entity-menu.linear .edit{display: none !important; }
.entity-menu.linear .add-block{display: none !important;}
.entity-menu.linear .blocks-list{display: none !important;}
</style>

<h1>
	<?php 
	if(!$essence->linear)
	{?>
		<span class="gray" ><?= $e->id?>| </span><?=$e->attrs['name']?>
	<?php 
	}
	else
	{?>
		<?=$_GLOBALS['CURRENT_MODULE']->icon?> <?=$essence->name?>
	<?php 	
	}?>
</h1>

<div class="entity-menu  <?=($essence->linear ? 'linear' : '')?>">		
	<a class="edit" href="#edit" onclick="Slonne.Entities.edit('<?=$essence->code ?>', '<?=$e->id?>', '<?=$type?>', Slonne.Entities.LANG); return false;">редактировать</a>
	<?php
	if(!$essence->jointFields && !$essence->linear)
	{?>
	<a class="add-block" href="#new_sub_block" onclick="Slonne.Entities.edit('<?=$essence->code ?>', 0, '<?=Entity2::TYPE_BLOCKS?>', Slonne.Entities.LANG, '<?=$e->id ?>'); return false;"> + блок</a>
	<?php 
	}?>
	<a class="add-element" href="#new_sub_element" onclick="Slonne.Entities.edit('<?=$essence->code ?>', 0, '<?=Entity2::TYPE_ELEMENTS?>', Slonne.Entities.LANG, '<?=$e->id?>'); return false;"> + элемент</a>
	<?php
	if(!$essence->jointFields && !$essence->linear)
	{?>
	<a class="blocks-list" href="#sub_blocks_list" onclick="Slonne.Entities.entitiesList('<?=$e->id ?>', '<?=Entity2::TYPE_BLOCKS?>'); return false;" >список блоков</a>	
	<?php 
	}?>
	<a class="elements-list" href="#sub_elements_list" onclick="Slonne.Entities.entitiesList('<?=(!$essence->linear ? $e->id : 0) ?>', '<?=Entity2::TYPE_ELEMENTS?>'); return false;" >список элементов</a>
	
	<a class="delete" href="#delete" onclick="Slonne.Entities.delete('<?=$e->id ?>', '<?=$type?>'); return false;" >удалить</a>
	
</div>