<?php
$prop = $MODEL['prop'];
//vd($prop);
?>

<script>
var num<?=$prop->id?>=0;

function typeTableAppendRow<?=$prop->id?>(operation, object)
{
	//alert(123)
	var id=''
	var name = ''
	var idx = ++num<?=$prop->id?>*10
	var status=''
		
	var statusWrapperHTML = '';
	
	if(typeof object!='undefined')
	{
		id=object.id
		name=object.name
		//idx=object.idx
	}
	
	var str='<tr class="_OPERATION_  " id="table-column-_ID_" >'
		+'	<td class="num">_NUM_.</td>'
		<?php 
		foreach($prop->tableColumns as $col)
		{?>
		+'	<td><input type="text" name="<?=$prop->code?>[_NUM_][<?=$col->id?>]" value="'+(typeof object!='undefined' && typeof object[<?=$col->id?>]!='undefined'  ? object[<?=$col->id?>] : '')+'" style="min-width: auto; width: 80px !important; "></td>'
		<?php 	
		}?>
		+'</tr>'
		+''
		
	//alert(str)
	str=str.replace(/_NUM_/g, num<?=$prop->id?>)
	str=str.replace(/_OPERATION_/g, operation)
	
	str=str.replace(/_ID_/g, id)
	str=str.replace(/_NAME_/g, name)
	str=str.replace(/_IDX_/g, idx)
	str=str.replace(/_STATUS_/g, status)
	str=str.replace(/_STATUSHTML_/g, statusWrapperHTML)
	
	//alert(str)

	$('#adv-columns-table-input-<?=$prop->id?>').append(str)
}
</script>

		
		
		
<style>
	.adv-columns-table{border-collapse: collapse; }
	.adv-columns-table td, .adv-columns-table th{padding: 3px 6px; text-align: center; }
	.adv-columns-table .num{font-size: 10px; }
</style>


<table class="adv-columns-table" id="adv-columns-table-input-<?=$prop->id?>" border="1">
	<tr>
		<th class="num">#</th>
	<?php 
	foreach($prop->tableColumns as $col)
	{?>
		<th><?=$col->name?></th>
	<?php 
	}?>
	</tr>
</table>
<input type="button" class="btn-mini" onclick="typeTableAppendRow<?=$prop->id?>('insert')" value="+ ряд" style="font-size: .8em; " />
<span class="hint">(Пустые строки сохранены не будут)</span>


<?php 
foreach($prop->value as $rowId=>$cols)
{?>
	<script>typeTableAppendRow<?=$prop->id?>('update', <?=json_encode($cols)?>)</script>
	<?php 
}?>

<script>typeTableAppendRow<?=$prop->id?>('update')</script>