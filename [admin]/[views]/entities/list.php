<?php
$e = $MODEL['entity'];
$essence = $MODEL['essence'];
$error = $MODEL['error'];
$type = $MODEL['type'];
$list = $MODEL['list'];
$order = $MODEL['order'];
$desc = $MODEL['desc'];
$totalCount = $MODEL['totalCount'];
$elPP = $MODEL['elPP'];
$p = $MODEL['p'];
//vd($totalCount);
//vd($p);
//vd($order);
//vd($desc);
//vd($e);
//vd($essence);
//vd($essence);
foreach($essence->fields[$type] as $key=>$field)
	if($field->displayed)
		$displayedFields[] = $field;
		
$UP_SIGN = '&#9650;';
$DOWN_SIGN = '&#9660;';


$modelForTopPartial = array('essence'=>$essence, 'entity'=>$e, 'type'=>$type)
?>



<?php
if(!$error)
{?>
	<?php 
	if(!$essence->linear)
	{?>
	<a href="#view" onclick="Slonne.Entities.treeNameClick('<?=$e->id ?>');  return false;" style="position: absolute; margin-top: -21px;">&larr; назад</a>
	<?php
	}?>
	
	<?=Core::renderPartial('entities/entityTopPartial.php', $modelForTopPartial );?>
	
	<span class="fa fa-th"></span> <?=($type == Entity2::TYPE_BLOCKS ? 'Блоки' : 'Элементы')?>:
	
	<?php
	//vd($list);
	//vd($essence); 
	
	
	if(count($list))
	{?>
	<form id="list-form" method="post" action="/<?=ADMIN_URL_SIGN?>/entity/listSaveChanges" target="frame1" onsubmit="Slonne.Entities.listSaveChanges();  ;  ">
		<input type="hidden" name="essenceCode" value="<?=$essence->code ?>" />
		<input type="hidden" name="type" value="<?=$type?>" />
		<input type="hidden" name="lang" value="">
		
		<table class="t" width="100%" border="1">
			<tr>
				<th style="width: 1px;">#</th>
				<th width="1"><a href="#order" onclick="Slonne.Entities.entitiesList('<?=$e->id ?>', '<?=$type?>', 'active', '<?=($order == 'active' && !$desc ? 1 : 0)?>'); return false; ">Акт. <?=($order=='active' ? ($desc ? $DOWN_SIGN : $UP_SIGN) : '')?></a></th>
				<th width="1"></th>
				<th width="1"><a href="#order" onclick="Slonne.Entities.entitiesList('<?=$e->id ?>', '<?=$type?>', 'id', '<?=($order == 'id' && !$desc ? 1 : 0)?>'); return false; ">id <?=($order=='id' ? ($desc ? $DOWN_SIGN : $UP_SIGN) : '')?></a></th>
	<?php 
	foreach($displayedFields as $key=>$field)
	{
		$columnClass = $field->type . ($field->type == 'pic' && $field->multiple ? '-multiple' : ''  ); 
		?>
		
		<?php 
		if($field->type == 'pic' && $field->multiple)
		{
		?>
				<th class="<?=$columnClass?>"><?=$field->name?></th>
		<?php
		}
		else 
		{ 
		?>		
				<th class="<?=$columnClass?>">
					<a href="#order" onclick="Slonne.Entities.entitiesList('<?=$e->id ?>', '<?=$type?>', '<?=$field->code?>', '<?=($order == $field->code && !$desc ? 1 : 0)?>'); return false; "><?=$field->name?> <?=($order==$field->code ? ($desc ? $DOWN_SIGN : $UP_SIGN) : '')?></a>
				</th>
		<?
		}?>
	<?php 
	}	
	?>
		
				<th width="1"><a href="#order" onclick="Slonne.Entities.entitiesList('<?=$e->id ?>', '<?=$type?>', 'idx', '<?=($order == 'idx' && !$desc ? 1 : 0)?>'); return false; ">Сорт. <?=($order=='idx' ? ($desc ? $DOWN_SIGN : $UP_SIGN) : '')?></a></th>
				<th class="del" width="1">Удалить</th>
			</tr>
	<?php
	$i = 0;
	foreach($list as $key=>$e)
	{
		++$i;
		?>
			<tr id="row-<?=$e->id?>" class=" <?=!$e->active ? 'inactive' : ''?>"  ondblclick="Slonne.Entities.edit('<?=$essence->code?>', '<?=$e->id?>', '<?=$type?>', Slonne.Entities.LANG);" >
				<td class="middle center"><?=$i?></td>
				<td class="center active-cb-wrapper">
					<img src="/<?=ADMIN_DIR?>/img/tree-loading.gif" class="loading" style="display: none">
					<input type="checkbox" id="active-<?=$e->id?>" name="active[<?=$e->id?>]" <?=($e->active ? ' checked="checked" ' : '')?> onclick="Slonne.Entities.changeActive(<?=$e->id?>)">
				</td>
				<td class="center"><a href="#edit" onclick="Slonne.Entities.edit('<?=$essence->code?>', '<?=$e->id?>', '<?=$type?>', Slonne.Entities.LANG); return false;">ред.</a></td>
				<td class="center">
					<?=$e->id?>
					<? if($e->untouchable)
					{?>
						<br><i class="fa fa-lock"></i>
					<?php 	
					}?>
				</td>
		<?php 
		foreach($displayedFields as $key2=>$field)
		{
			$columnClass = $field->type . ($field->type == 'pic' && $field->multiple ? '-multiple' : ''  );
			?>
				<td class="<?=($field->marked ? 'marked' : '')?>  <?=$columnClass?>  ">
					<?=$field->displayValueForList($e->attrs[$field->code]) ?>
				</td>
		<?php 
		}	
		?>
				<td ><input type="text" name="idx[<?=$e->id?>]" size="2" value="<?=$e->idx?>" /></td>
				<td class="del center" ><input type="checkbox" name="del[<?=$e->id?>]" /></td>
			</tr>
	<?php 
	} 
	?>
		</table>
		
		
		<input id="save-idx-list-btn" type="submit" value="Сохранить изменения" />	
	</form>		
	
	<p>
	<?=drawPages($totalCount, $p, $elPP, $onclick="Slonne.Entities.entitiesList(Slonne.Entities.LIST_SETTINGS.pid, Slonne.Entities.LIST_SETTINGS.type, Slonne.Entities.LIST_SETTINGS.order, Slonne.Entities.LIST_SETTINGS.desc, ###)", $class="pages");?>
	
	
	<?php 
	}
	else
		echo 'Список пуст. ';
	?>
	
	
<?php 	
}
else 
{
	echo $error;
}
?>

