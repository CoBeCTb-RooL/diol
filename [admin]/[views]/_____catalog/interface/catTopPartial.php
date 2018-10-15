<?php 
$cat = $MODEL['cat'];
$catType = $MODEL['catType'];
$lang = $MODEL['lang'];
$headingPostfix = $MODEL['headingPostfix'];
//vd($MODEL);
//vd($e);
//vd($type);
//vd($essence);


?>


<style>
.entity-menu.linear .edit{display: none !important; }
.entity-menu.linear .add-block{display: none !important;}
.entity-menu.linear .blocks-list{display: none !important;}
</style>

<h1><span class="gray" ><?= $cat->id?>| </span><?=$cat->name?> <span class="gray" style="font-size: .9em; "><?=$headingPostfix?></span></h1>

<div class="entity-menu">		
	<a class="edit" href="#edit" onclick="Slonne.Catalog.Interface.catEdit('<?=$catType->code ?>', '<?=$cat->id?>', Slonne.Catalog.LANG); return false;"><i class="fa fa-pencil-square-o"></i> Редактировать</a>
	
	<a class="add-block" href="#new_sub_block" onclick="Slonne.Catalog.Interface.catEdit('<?=$catType->code ?>', 0, Slonne.Catalog.LANG, '<?=$cat->id ?>'); return false;"><i class="fa fa-cube"></i> + блок</a>
	<a class="add-block" href="#new_sub_block" onclick="Slonne.Catalog.Interface.itemEdit('0', '<?=$cat->id?>', Slonne.Catalog.LANG); return false;"><i class="fa fa-cubes"></i> + элемент</a>
	
	<a class="add-block" href="#elements_list" onclick="Slonne.Catalog.Interface.itemsList('<?=$cat->id ?>'); return false;"><span class="fa fa-th"></span> Список элементов</a>
	
	<a class="add-block" href="#class_edit" onclick="Slonne.Catalog.Interface.catClassEdit('<?=$cat->id ?>'); return false;"><i class="fa fa-cogs"></i> Настроить класс</a>
	
	
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="delete" href="#delete" onclick="Slonne.Entities.delete('<?=$e->id ?>', '<?=$type?>'); return false;" ><i class="fa fa-times"></i> Удалить</a>
	
</div>