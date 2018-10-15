<?php
$list = $MODEL['list']; 
//vd($list);
?>



<?php Core::renderPartial('catalog/menu.php', $model);?>

<h1>Классы</h1>
<?php 
if(count($list) )
{?>

<table class="t">
	<tr>
		<th>id</th>
		
		<th></th>
		<th>Название</th>
		<th>Свойства</th>
		
		<th>Удалить</th>
	</tr>
	<?php 
	foreach($list as $key=>$class)
	{
		$i=0;
		?>
		<tr  id="row-<?=$class->id?>" class="<?=($class->active ? '' : 'inactive') ?>" ondblclick="Slonne.Catalog.Classes.classesEdit(<?=$class->id?>)">
			<td><?=$class->id?></td>
			
			<td><a href="#edit" onclick="Slonne.Catalog.Classes.classesEdit(<?=$class->id?>); return false;">ред.</a></td>
			<td style="font-weight: bold; "><?=$class->name?></td>
			<td align="center">
				<b><?=count($class->props)?></b><br/>
				<div class="props">
					<table style="width: 100%;">
					<?
					foreach($class->props as $key=>$prop)
					{?>
						<tr style="background: none; <?=$prop->active ? '' : 'color: #ccc;' ?>" >
							<td width="1"><?=++$i; ?>. </td>
							<td ><a href="/admin/catalog/props/?propId=<?=$prop->id?>" target="_blank"><?=$prop->id?>: <?=$prop->name?></a></td>
						</tr>
						
					<?php 	
					}?>						
					</table>
				
				</div>
			</td>
			
			<td align="center"><a href="#delete" class="" onclick="Slonne.Catalog.Classes.classesDelete(<?=$class->id?>); return false;">удалить</a></td>
		</tr>
	<?php 
	}?>
</table>

<?php
}
else
{?>
	Ничего нет.
<?php 	
} 
?>
<p>
<input id="add-btn" type="button" onclick="Slonne.Catalog.Classes.classesEdit(); " value="+ новый класс">