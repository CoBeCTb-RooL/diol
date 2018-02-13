<?php
$list = $MODEL; 
//vd($list);
?>


<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>



<style>
	.status-active{}
	.status-inactive{opacity: .4;  }
	.delete-btn{display: none; }
	.status-inactive .delete-btn{display: block; }
	
</style>




<?php 
if($list)
{?>


<h4>Групп: <?=count($list)?></h4>
<form id="list-form" method="post" action="" target="frame1"  >
	<table class="t">
		<tr>
			<th>#</th>
			<th></th>
			
			<th>Группа</th>
			<th>Роль</th>
			<th>Дата рег.</th>
			
			
			
			<th>Удалить</th>
		</tr>
		<?php 
		
		foreach($list as $key=>$item)
		{
			$roles = Role::getListByRole($item->role);
		//vd($item)?>
			<tr id="row-<?=$item->id?>" class="status-<?=$item->status ? $item->status->code : ''?> "  ondblclick="groupsEdit(<?=$item->id?>); return false; " >
				<td style="width:1px; font-size: 8px; text-align: center; "><?=(++$i)?>. </td>

				<td width="1"  class="status-switcher">
					<a href="#" id="status-switcher-<?=$item->id?>" onclick="groupsSwitchStatus(<?=$item->id?>); return false; " ><?=$item->status->icon?></a>
				</td> 
				<td>
					<a href="#" onclick="groupsEdit(<?=$item->id?>)" style="font-size: 14px; font-weight: bold;  "><?=$item->name?></a>
					<div style="font-size: 9px; font-weight: bold; ">id: <?=$item->id?></div> 
				</td>
				
				
				<td>
					<div >

						<?php
						if(count($roles))
						{
							foreach($roles as $r)
							{?>
							<div style="margin: 0 0 3px 0; ">
								<?php 
								if($item->role & $r->num )
								{?>
									<i class="fa fa-check" aria-hidden="true" style="font-size: 9px; "></i> <?=$r->name?>
								<?php 	
								}?>
							</div>
							<?php 	
							}
						}
						else
						{?>
							<span>нет ни одной роли.</span>
						<?php 	
						}
						?>

					</div>
				</td>
				<td style="font-size: 10px; "><?=$item->dateCreated?></td>
				<td><a href="#" onclick="if(confirm('Удалить группу?')){groupsDelete(<?=$item->id?>)}" style="color: red; font-size: 10px; ">&times; удалить</a></td>
				
				
			</tr>
		<?php 
		}?>
	</table>
</form>

<p>
	<?=drawPages($totalCount, $p-1, $elPP, $onclick="OPTS.p=###; usersList()", $class="pages");?>
	
	
<?php
}
else
{?>
	Ничего нет.
<?php 	
} 
?>

<input type="button" onclick="groupsEdit()" value="+ добавить группу">
