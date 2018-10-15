<?php
$list = $MODEL['list']; 
?>



<?php Core::renderPartial('catalog/menu.php', $model);?>

<h1><?=$_GLOBALS['CURRENT_MODULE']->icon?> Типы каталогов</h1>
<?php 
if(count($list) )
{?>
<form id="list-form" method="post" action="/<?=ADMIN_URL_SIGN?>/module/listSubmit" target="frame1" onsubmit="Slonne.Catalog.listSubmitStart();" >
	<table class="t">
		<tr>
			<th>id</th>
			
			<th></th>
			<th>Тип</th>
			<th>Код</th>
			<th>Удалить</th>
		</tr>
		<?php 
		foreach($list as $key=>$type)
		{?>
			<tr  id="row-<?=$type->id?>" ondblclick="Slonne.Catalog.Types.typeEdit(<?=$type->id?>)">
				<td><?=$type->id?></td>
				
				<td><a href="#edit" onclick="Slonne.Catalog.Types.typeEdit(<?=$type->id?>); return false;">ред.</a></td>
				<td style="font-weight: bold; "><?=$type->name?></td>
				
				<td><?=$type->code?></td>
				
				<td><a href="#delete" class="" onclick="Slonne.Catalog.Types.typeDelete(<?=$type->id?>); return false;">удалить</a></td>
			</tr>
		<?php 
		}?>
	</table>
	
</form>
	
	
<?php
}
else
{?>
	Ничего нет.
<?php 	
} 
?>
<p>
<input id="add-btn" type="button" onclick="Slonne.Catalog.Types.typeEdit(); " value="+ тип каталога">