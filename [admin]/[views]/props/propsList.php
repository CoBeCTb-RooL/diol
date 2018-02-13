<?php
$list = $MODEL['list']; 
?>



<style>
	.status-active{}
	.status-inactive{opacity: .5;  }
	.status-inactive .delete-btn{display: block; }
	.delete-btn{display: none; }
	
	.option-active{}
	.option-inactive{opacity: .4;  }
	
	.id{font-weight: bold !important; }
	.num{}
	.name a{font-weight: bold; font-size: 1.3em; }
</style>




<h1><i class="fa fa-cube"></i> Свойства </h1>


<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>


<?php 
if(count($list) )
{?>

<table class="t">
	<tr>
		<th>id</th>
		<th></th>
		<th></th>
		<!-- <th></th> -->
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
		<tr id="row-<?=$prop->id?>" class="status-<?=$prop->status ? $prop->status->code : ''?> " ondblclick="propsEdit(<?=$prop->id?>);">
			<td><?=$prop->id?></td>
			<td width="1"  class="status-switcher">
				<a href="#" id="status-switcher-<?=$prop->id?>" onclick="switchPropStatus(<?=$prop->id?>); return false; " ><?=$prop->status->icon?></a>
			</td>
			<td style="font-size: 1.3em; " align="center"><?=$prop->required ? '<span style="color: red; ">*</span>' : '<span style="color: #ccc; ">*</span>'?></td>
			<td><a href="#edit" onclick="propsEdit(<?=$prop->id?>); return false;" style="font-size: 13px; font-weight: bold; "><?=$prop->name?></a></td>
			
			<td ><?=$prop->nameOnSite?></td>
			
			<td style="font-weight: bold; ">[ <span style="font-weight: bold;"><?=$prop->code?></span> ]</td>
			<td><?=$prop->type?></td>
			<td style="font-size: 10px;">
			<?php 
			if($prop->type == 'select')
			{?>
				<?php 
				foreach($prop->options as $key=>$opt)
				{?>
					<div class="option-<?=$opt->status ? $opt->status->code : ''?> " >- <?=$opt->value?></div>
				<?php 	
				}?>
			<?php 
			}?>
			
			<?php 
			if($prop->type == 'table')
			{?>
				<?php 
				foreach($prop->tableColumns as $key=>$col)
				{?>
					<div class="option-<?=$col->status ? $col->status->code : ''?> " >- <?=$col->name?></div>
				<?php 	
				}?>
			<?php 
			}?>
			
			</td>
			
			<td><a href="#delete" class="delete-btn" onclick="if(confirm('Удалить?')){deleteProp(<?=$prop->id?>);} return false;">удалить</a></td>
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
<input id="add-btn" type="button" onclick="propsEdit(); " value="+ новое поле">