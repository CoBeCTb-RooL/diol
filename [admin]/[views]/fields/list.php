<?php
$list = $MODEL['list']; 
$field = $MODEL['essence']; 
$type=$MODEL['type'];
$error = $MODEL['error'];

$i=0;
?>


<?php
if($error)
{
	echo '<script>error("'.$error.'")</script>';
	return;
} 
?>




<h1><?=$field->name?><span class="title-gray"> : <?=($type==Entity2::TYPE_BLOCKS ? 'Поля блоков' : 'Поля элементов')?></span></h1>

<?php 
if(count($list) )
{?>
<form id="fields-list-form" method="post" action="/<?=ADMIN_URL_SIGN?>/field/listSubmit" target="frame1" onsubmit="Slonne.Fields.listSubmitStart();" >
	<table class="t">
		<tr>
			<th>#</th>
			<th>id</th>
			<th></th>
			<th>Поле</th>
			<th>Код</th>
			<th></th>
			<th></th>
			<th>Тип</th>
			<th></th>
			<th>Удалить</th>
		</tr>
		<?php 
		foreach($list as $key=>$field)
		{?>
			<tr id="row-<?=$field->id?>"  ondblclick="Slonne.Fields.edit(<?=$field->id?>); return false; ">
				<td class="num "><?=(++$i)?>. </td>
				<td class="bold"><?=$field->id?></td>
				<td><a href="#edit" onclick="Slonne.Fields.edit(<?=$field->id?>); return false; ">ред.</a></td>

				<td style="font-weight: bold; " ><?=$field->name?></td>
				<td ><?=$field->code?></td>
				
				<td class="center"><?=($field->displayed ? '<span style="color: #000;" class="fa fa-eye" title="отображается в списке"></span>' : '<span style="color: #ccc" class="fa fa-eye" title="не отображается в списке"></span>')?></td>
				<td class="center"><?=($field->required ? '<span style="color: #FA3E3E;" class="fa fa-asterisk" title="обязательное"></span>' : '<span style="color: #ccc" class="fa fa-asterisk" title="необязательное"></span>')?></td>
				
				<td><?=Field2::$types[$field->type]?></td>
				
				<td><input size="2" style="width: 25px; font-size: 9px;" id="idx-<?=$field->id?>" name="idx[<?=$field->id?>]" value="<?=$field->idx?>" type="text"></td>
				<td class="center"><a href="#delete" class="" onclick="Slonne.Fields.delete(<?=$field->id?>); return false;">удалить</a></td>
			</tr>
		<?php 
		}?>
	</table>
	<input type="submit" id="list-submit-btn" value="Сохранить изменения">
</form>	

	
	
<?php
}
else
{?>
	Ничего нет.
<?php 	
} 
?>

<p><input id="add-btn" type="button" onclick="Slonne.Fields.edit(); " value="+ поле">