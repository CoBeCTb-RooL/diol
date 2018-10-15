<?php
$list = $MODEL['list']; 

?>

<style>
	.status-<?=Status::ACTIVE?>{}
	.status-<?=Status::INACTIVE?>{opacity: .4;  }
	.delete-btn{display: none; }
	.status-<?=Status::INACTIVE?> .delete-btn{display: block; }
	
	.name{font-weight: bold; font-size: 1.0em;  }
	.is-large .name{font-size: 1.3em; }
	
	.is-large-lbl{text-align: center; }
	.is-large .is-large-lbl{font-weight: bold; }	
</style>




<?php 
if($list)
{?>
	<table class="t">
		<tr>
			<th>#</th>
			<th>id</th>
			<th></th>
			<th>Город</th>
			<th>Крупный?</th>
			<th>Создано</th>
		</tr>
		<?php 
		$i=0;
		foreach($list as $key=>$item)
		{?>
			<tr id="row-<?=$item->id?>" class="status-<?=$item->status->code?> <?=$item->isLarge ? 'is-large':''?>">
				<td style="font-size: 8px; "><?=(++$i)?>. </td>
				<td><?=$item->id?></td>
				<td width="1"  class="status-switcher">
					<a href="#" id="status-switcher-<?=$item->id?>" onclick="switchStatus(<?=$item->id?>); return false; " ><?=$item->status->icon?></a>
				</td>
				<td><a href="#" onclick="edit(<?=$item->id?>); return false;" class="name" ><?=$item->name?></a></td>
				<td class="is-large-lbl"><?=$item->isLarge ? 'ДА':'нет'?></td>
				<td><?=Funx::mkDate($item->dateCreated, 'with_time')?></td>
			</tr>
		<?php 	
		}?>
	</table>
	<p>
	<input type="button" value="+ добавить город" onclick="edit()" />
<?php
}
else
{?>
	Ничего нет.
<?php 	
} 
?>
