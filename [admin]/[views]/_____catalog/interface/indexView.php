<?php
$type=$MODEL['type'];
$catType=$MODEL['catType'];


//vd($catType);
?>




<?php Core::renderPartial('catalog/menu.php', $model);?>



<?php
if($catType )
{?>
	<h1>Каталог: <?=$catType->name?></h1>
	
	<?php //Core::renderPartial('catalog/interface/menu.php', $catType);?>
<div class="catalog">	
	
	<!--дерево обьектов-->
	<div class="tree">
		<img src="/<?=ADMIN_DIR?>/img/tree-loading.gif" id="tree-loading-global">
		<form id="tree-form" method="post" action="/<?=ADMIN_URL_SIGN?>/catalog/treeSaveChanges" target="frame1" onsubmit="Slonne.Catalog.treeSaveChanges(); return false;  ">
			<input type="hidden" name="catType" value="<?=$catType->code?>" />
			<input type="hidden" name="lang" value="">
			
			<div class="tree-inner" id="subs-0" style="display: none;"></div>
			
			<input id="save-idx-tree-btn" type="submit" value="Сохранить изменения в дереве" />
			<br/>
			<label><input type="checkbox" onclick="if(   $(this).is(':checked')    ){$('#tree-form input[name=lang]').val(Slonne.Entities.LANG)} else{$('#tree-form input[name=lang]').val('')}" />только для этой яз. версии</label>
		</form>
		<a id="add-element-to-root" href="#new_sub_element" onclick="Slonne.Catalog.Interface.catEdit('<?=$catType->code?>', 0, Slonne.Entities.lang, 0); ; return false;"> + элемент в корень</a>
	</div>
	<!--//дерево обьектов-->
	
	
	
	<!--список объектов-->
	<div class="list">
		<img src="/<?=ADMIN_DIR?>/img/tree-loading.gif" id="list-loading-global" style="visibility: hidden;">
		<div class="inner"></div>
	</div>
	<!--//список объектов-->
	
	
	<div class="clear"></div>
	
	
</div>

	
	
	
	
	
	
	<script>
	Slonne.Catalog.CatType = '<?=$catType->code?>'
	Slonne.Catalog.LANG = '<?=LANG?>'
	</script>
	
	
	<?=Core::renderPartial('catalog/interface/treeElementTemplate.php');?>
	
	<!--форма редактирования-->
	<div id="float" class="view " style="min-width: 1000px; max-width: 1200px;" >!!</div>
	
	
	<script>
	$(document).ready(function(){
		Slonne.Catalog.Interface.expandClick(0) ;
	});
	</script>
	
	
<?php 	
} 
else
{?>
	Тип <b><?=$type?></b> не найден.
<?php 	
}
?>


