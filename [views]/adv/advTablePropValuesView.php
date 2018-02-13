<?php
$prop = $MODEL['prop']; 

//vd($prop);
?>


<style>
.table-prop-tbl{border-collapse: collapse; }
.table-prop-tbl th, .table-prop-tbl td{padding: 5px 8px; text-align: center;  }
</style>

<table class="table-prop-tbl" border="1">
	<tr>
	<?php 
	foreach($prop->tableColumns as $col)
	{?>
		<th><?=$col->name?></th>
	<?php 	
	}?>
	</tr>
	
	
<?php 
foreach($prop->value as $row=>$val)
{?>
	<tr>
	<?php 
	foreach($prop->tableColumns as $col)
	{?>
		<td><?=$val[$col->id] ? $val[$col->id] : '-'?></td>		
	<?php 
	}?>
	</tr>
<?php 	
}?>
</table>


