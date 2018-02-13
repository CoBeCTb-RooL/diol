<?php
$essence = $MODEL['essence'];
?>









<?php
if($essence)
{?>
	
	
	<div class="entities">
		
		<!--языки-->
		<?php
		foreach($_CONFIG['langs'] as $lang)
			$langLinks[] = '<a href="#change_lang" id="lang-'.$lang->code.'" class="'.($lang->code == $_CONFIG['default_lang']->code ? 'active' : '').'" onclick="Slonne.Entities.changeLang(\''.$lang->code.'\'); return false;">'.$lang->name.'</a>';
		?>
		<div class="langs">
		Версия: <?=join(' | ', $langLinks) ?>
		</div>
		<!--//языки-->
	
		
		<?
		if(!$essence->linear)
		{?>
		<!--дерево обьектов-->
		<div class="tree">
			<h1><?=$_GLOBALS['CURRENT_MODULE']->icon?> <?=$essence->name?></h1>
			<img src="/<?=ADMIN_DIR?>/img/tree-loading.gif" id="tree-loading-global">
			<form id="tree-form" method="post" action="/<?=ADMIN_URL_SIGN?>/entity/treeSaveChanges" target="frame1" onsubmit="Slonne.Entities.treeSaveChanges(); return false;  ">
				<input type="hidden" name="essenceCode" value="<?=$essence->code?>" />
				<input type="hidden" name="type" value="<?=($essence->jointFields ? Entity2::TYPE_ELEMENTS : Entity2::TYPE_BLOCKS)?>" />
				<input type="hidden" name="lang" value="">
				<div class="tree-inner" id="subs-0" style="display: none;"></div>
				<input id="save-idx-tree-btn" type="submit" value="Сохранить изменения в дереве" />
				<br/>
				<label><input type="checkbox" onclick="if(   $(this).is(':checked')    ){$('#tree-form input[name=lang]').val(Slonne.Entities.LANG)} else{$('#tree-form input[name=lang]').val('')}" />только для этой яз. версии</label>
			</form>
			<a id="add-element-to-root" href="#new_sub_element" onclick="Slonne.Entities.edit('<?=$essence->code?>', 0, '<?=($essence->jointFields ? Entity2::TYPE_ELEMENTS : Entity2::TYPE_BLOCKS)?>', Slonne.Entities.lang, '0'); ; return false;"> + элемент в корень</a>
		</div>
		<!--//дерево обьектов-->
		<?php 
		}?>
		
		
		<!--список объектов-->
		<div class="list">
			<img src="/<?=ADMIN_DIR?>/img/tree-loading.gif" id="list-loading-global" style="visibility: hidden;">
			<div class="inner"></div>
		</div>
		<!--//список объектов-->
		
		
		<div class="clear"></div>
	</div>
	
	<?php
	if($essence->linear || $essence->jointFields)
		$type = Entity2::TYPE_ELEMENTS;
	else 
		$type = Entity2::TYPE_BLOCKS;
	?>
	
	<script>
	Slonne.Entities.ESSENCE = '<?=$essence->code?>';
	Slonne.Entities.TYPE = '<?=$type?>'
	Slonne.Entities.LANG = '<?=$_CONFIG['default_lang']->code?>'
	Slonne.Entities.LINEAR = <?=($essence->linear ? 'true' : 'false')?>
	
	Slonne.Entities.LOADED_TREE_ITEMS = []

	Slonne.Entities.TYPE_ELEMENTS = '<?=Entity2::TYPE_ELEMENTS?>'
	Slonne.Entities.TYPE_BLOCKS = '<?=Entity2::TYPE_BLOCKS?>'

	//Slonne.Entities.LIST_P = 1
	</script>
	
	
	<?=Core::renderPartial('entities/treeElementTemplate.php');?>
	
	
	
	
	
	<!--форма редактирования-->
	<div id="float" class="view " style="min-width: 1000px; max-width: 1200px;" >!!</div>
	
	
	
	
	
	
	<script>
		$(document).ready(function(){
			<?php
			if(!$essence->linear) 
			{?>
			Slonne.Entities.expandClick(0) ;
			<?php
			}
			else 
			{?>
			Slonne.Entities.entitiesList(0, '<?=Entity2::TYPE_ELEMENTS?>');
			<?php 
			}	 
			?>
		});
	</script>
	
	
<?php 
}
else
{?>
	uNKNoWN eSSeNCe <b>"<?=$_PARAMS[1]?>"</b>!  
<?php 
} 
?>
