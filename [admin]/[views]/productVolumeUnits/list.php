<?php
$list = $MODEL['list']; 

?>



<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>



<style>
	.status-<?=Status::ACTIVE?>{}
	.status-<?=Status::INACTIVE?>{opacity: .4;  }
	.delete-btn{display: none; }
	.status-<?=Status::INACTIVE?> .delete-btn{display: block; }
	
</style>






<?php 
if($list)
{?>
	<table class="t">
		<tr>
			<th>id</th>
			<th></th>
			<th>Мера</th>
			<th>Создано</th>
		</tr>
		<?php 
		foreach($list as $key=>$item)
		{?>
			<tr id="row-<?=$item->id?>" class="status-<?=$item->status->code?>">
				<td><?=$item->id?></td>
				<td width="1"  class="status-switcher">
					<a href="#" id="status-switcher-<?=$item->id?>" onclick="switchStatus(<?=$item->id?>); return false; " ><?=$item->status->icon?></a>
				</td>
				<td><a href="#" onclick="edit(<?=$item->id?>); return false;" style="font-weight: bold; font-size: 1.3em;  "><?=$item->name?></a></td>
				<td><?=Funx::mkDate($item->dateCreated, 'with_time')?></td>
			</tr>
		<?php 	
		}?>
	</table>
	<p>
	<input type="button" value="+ добавить меру" onclick="edit()" />
<?php
}
else
{?>
	Ничего нет.
<?php 	
} 
?>
