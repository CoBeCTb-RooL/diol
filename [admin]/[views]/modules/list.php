<?php
$list = $MODEL; 
?>




<?php 
if(count($list) )
{?>
<form id="list-form" method="post" action="/<?=ADMIN_URL_SIGN?>/module/listSubmit" target="frame1" onsubmit="Slonne.Modules.listSubmitStart();" >
	<table class="t">
		<tr>
			<th>id</th>
			<th>Акт.</th>
			<th></th>
			<th>Модуль</th>
			<th>Path</th>
			<th>Права</th>
			<th>Сорт.</th>
			<th>Удалить</th>
		</tr>
		<?php 
		foreach($list as $key=>$module)
		{?>
			<tr class="<?=(!$module->active ? 'inactive' : '')?>" id="row-<?=$module->id?>" ondblclick="Slonne.Modules.edit(<?=$module->id?>)">
				<td><?=$module->id?></td>
				<td><?=($module->active ? '<span style="color: green; ">ДА</span>' : '<span style="color: red; ">нет</span>')?></td>
				<td><a href="#edit" onclick="Slonne.Modules.edit(<?=$module->id?>); return false;">ред.</a></td>
				<td style="font-weight: bold; "><?=$module->icon?> <?=$module->name?></td>
				
				<td><?=$module->path?></td>
				
				<td><?=($module->actions ? '<span style="color: green; ">ДА</span>' : '<span style="color: red; ">нет</span>')?></td>
				<td><input size="2" style="width: 25px; font-size: 9px;" id="idx-<?=$module->id?>" name="idx[<?=$module->id?>]" value="<?=$module->idx?>" type="text"></td>
				<td><a href="#delete" class="" onclick="Slonne.Modules.delete(<?=$module->id?>); return false;">удалить</a></td>
			</tr>
		<?php 
		}?>
	</table>
	<input type="submit" id="list-submit-btn" value="Сохранить изменения">
</form>
	
	<input id="add-btn" type="button" onclick="Slonne.Modules.edit(); " value="+ модуль">
<?php
}
else
{?>
	Ничего нет.
<?php 	
} 
?>