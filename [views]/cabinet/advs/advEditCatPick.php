<?php
$cats = $MODEL['cats'];

$title = 'Создание объявления';
if($item)
	$title = 'Редактирование объявления';
?>



<!--крамбсы-->
<? Core::renderPartial(SHARED_VIEWS_DIR.'/crumbs.php', $MODEL['crumbs']);?>
<!--//крамбсы-->


<?php Core::renderPartial('cabinet/menu.php');?>




<h2>Создание объявления</h2>
<h3>Шаг 1. Выберите категорию</h3>



<div class="cats-list">
<?php
foreach($cats as $key=>$cat)
{?>
	
	<?
	if(count($cat->subCats))
	{?>
		<h2><?=$cat->name?></h2>
		<div class="subs" >
		<?php
		foreach($cat->subCats as $key=>$subcat)
		{?>
			<div>
				<a href="<?=Route::getByName(Route::CABINET_ADV_EDIT)->url()?>cat_<?=$subcat->urlPiece()?>"><?=$subcat->name?></a>
				
			</div>
		<?php 
		} 
		?>
		</div>
	<?php 	
	}
	else
	{?>
		<h2><a href="<?=Route::getByName(Route::CABINET_ADV_EDIT)->url()?>cat_<?=$cat->urlPiece()?>"><?=$cat->name?></a></h2>
	<?php 	
	}?>
<?php 	
} 
?>
</div>