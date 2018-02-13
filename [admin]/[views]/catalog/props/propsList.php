<?php
$list = $MODEL['list']; 
//vd($list);
?>



<?php Core::renderPartial('catalog/menu.php', $model);?>

<h1><?=$_GLOBALS['CURRENT_MODULE']->icon?> Поля </h1>
<?php 
if(count($list) )
{?>

<table class="t">
	<tr>
		<th>id</th>
		<th></th>
		<th></th>
		<th>Поле</th>
		<th>Название на сайте</th>
		<th>Код</th>
		<th>Тип</th>
		<th></th>
		<th>Удалить</th>
	</tr>
	<?php 
	foreach($list as $key=>$prop)
	{?>
		<tr  id="row-<?=$prop->id?>" class="<?=$prop->active ? "" : "inactive"?> " ondblclick="Slonne.Catalog.Props.propsEdit(<?=$prop->id?>)">
			<td><?=$prop->id?></td>
			<td style="text-align: center; "><?=$prop->active ? '<span style="color: green; ">АКТ. </span>' : '<span style="color: red;  ">НЕАКТ. </span>'?></td>
			<td><a href="#edit" onclick="Slonne.Catalog.Props.propsEdit(<?=$prop->id?>); return false;">ред.</a></td>
			<td style="font-weight: bold; "><?=$prop->name?></td>
			<td ><?=$prop->nameOnSite?></td>
			<td style="font-weight: bold; ">[ <span style="font-weight: bold;"><?=$prop->code?></span> ]</td>
			<td><?=$prop->type?></td>
			<td style="font-size: 10px;">
				<?php 
				foreach($prop->options as $key=>$opt)
				{?>
					<div style="<?=$opt->active ? "" : "color: #ccc;"?>" >- <?=$opt->value?></div>
				<?php 	
				}?>
			</td>
			
			<td><a href="#delete" class="" onclick="Slonne.Catalog.Props.propsDelete(<?=$prop->id?>); return false;">удалить</a></td>
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
<input id="add-btn" type="button" onclick="Slonne.Catalog.Props.propsEdit(); " value="+ новое поле">