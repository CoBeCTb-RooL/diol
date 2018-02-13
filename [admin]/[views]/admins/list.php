<?php
$list = $MODEL['list']; 
//vd($MODEL['error']);
?>


<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>



<style>
	.status-active{}
	.status-inactive{opacity: .4;  }
	.delete-btn{display: none; }
	.status-inactive .delete-btn{display: block; }
	
	
	.group{font-weight: bold !important; }
	.hint{font-weight: normal !important; }
	.group-status-<?=Status::ACTIVE?> .hint{display: none; }
	.group-status-<?=Status::INACTIVE?> {/*opacity: .6; */ color: #888; }
	.group-status-<?=Status::INACTIVE?> .hint{color: #888; font-style: italic; }
	
</style>


<?php 
if(count($list) )
{?>

	<table class="t">
		<tr>
			<th>#</th>
			<th>Акт.</th>
			<th>Администратор</th>
			<th>E-mail</th>
			<th>Группа</th>
			
			<th>Удалить</th>
		</tr>
		<?php 
		foreach($list as $key=>$item)
		{?>
			<tr  id="row-<?=$item->id?>" ondblclick="adminsEdit(<?=$item->id?>)" class="status-<?=$item->status ? $item->status->code : ''?>">
				<td style="width:1px; font-size: 8px; text-align: center; "><?=(++$i)?>. </td>
				<td width="1"  class="status-switcher" style="text-align: center; ">
					<a href="#" id="status-switcher-<?=$item->id?>" onclick="adminsSwitchStatus(<?=$item->id?>); return false; " ><?=$item->status->icon?></a>
				</td> 
				<td>
					<a href="#edit" onclick="adminsEdit(<?=$item->id?>); return false;" style="font-weight: bold; font-size: 13px; "><?=$item->name?></a>
					<div style="margin: 2px 0 0 10px;"><b style="font-size: 10px; ">id: <?=$item->id?></b><br><?=$item->email?></div>
				</td>
				<td ><?=$item->email?></td>

				<td class="group group-status-<?=$item->group->status->code?>" >
				<?php 
				if($item->group)
				{?>
					<?=$item->group->name?> <span class="hint">(<?=$item->group->status->name?>)</span>
					<a href="<?=$item->group->adminUrl()?>" target="_blank" style="font-weight: normal; display: block; font-size: 9px;  ">смотреть</a>
				<?php 
				}
				else 
				{?>
					<span style="font-weight: normal !important; font-style: italic; ">-группа не выбрана-</span>
				<?php 	
				}?>
				</td>
				
				<td><a href="#" onclick="if(confirm('Удалить администратора?')){adminsDelete(<?=$item->id?>)}" style="color: red; font-size: 10px; ">&times; удалить</a></td>
			</tr>
		<?php 
		}?>
	</table>
	

	
	<input id="add-btn" type="button" onclick="adminsEdit(); " value="+ Новый администратор">
<?php
}
else
{?>
	Ничего нет.
<?php 	
} 
?>