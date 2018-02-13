<?php
$list = $MODEL; 
//vd($list);
?>




<?php 
if(count($list) )
{?>

	<table class="t">
		<tr>
			<th>id</th>
			<th></th>
			<th style="width: 200px;">Константа</th>
			
			
	<?php
	foreach($_CONFIG['LANGS'] as $lang=>$val)
	{?>
			<th style="width: 200px;"><?=$lang?></th>
	<?php 
	} 
	?>
			
			<th>Удалить</th>
		</tr>
		<?php 
		foreach($list as $key=>$const)
		{?>
			<tr class="" id="row-<?=$const->id?>" ondblclick="Slonne.Constants.edit(<?=$const->id?>)">
				<td><?=$const->id?></td>
				
				<td><a href="#edit" onclick="Slonne.Constants.edit(<?=$const->id?>); return false;">ред.</a></td>
				<td style="font-weight: bold; "><?=$const->name?></td>
				
		<?php
		foreach($_CONFIG['LANGS'] as $lang=>$val)
		{?>
			<td><?=$const->value[$lang]?></td>
		<?php 
		} 
		?>				
				
				<td><a href="#delete" class="" onclick="Slonne.Constants.delete(<?=$const->id?>); return false;">удалить</a></td>
			</tr>
		<?php 
		}?>
	</table>
	

	
	<input id="add-btn" type="button" onclick="Slonne.Constants.edit(); " value="+ константа">
<?php
}
else
{?>
	Ничего нет.
<?php 	
} 
?>