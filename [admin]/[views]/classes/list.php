<?php
$list = $MODEL['list']; 
//vd($list);
?>



<style>
	.status-active{}
	.status-inactive{opacity: .3;  }
	.status-inactive .delete-btn{display: block; }
	.delete-btn{display: none; }
	
	.option-active{}
	.option-inactive{opacity: .4;  }
	
	.id{font-weight: bold !important; }
	.num{}
	.name a{font-weight: bold; font-size: 1.3em; }
</style>



<h1><i class="fa fa-cubes"></i> Классы</h1>


<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>


<?php 
if(count($list) )
{?>
<form action="/<?=ADMIN_URL_SIGN?>/classes/listSaveChanges" method="post" onsubmit="if(confirm('Сохранить изменения?')){listSaveChangesStart()} else return false;" target="frame7">
	<table class="t">
		<tr>
			<th>id</th>
			<th></th>
			<th>Название</th>
			<th>Свойства</th>
			
			<th>Удалить</th>
		</tr>
		<?php 
		foreach($list as $key=>$class)
		{
			$i=0;
			//vd($class);
			?>
			<tr id="row-<?=$class->id?>" class="status-<?=$class->status ? $class->status->code : ''?> " ondblclick="classesEdit(<?=$class->id?>);">
				<td><?=$class->id?></td>
				<td width="1"  class="status-switcher">
					<a href="#" id="status-switcher-<?=$class->id?>" onclick="switchStatus(<?=$class->id?>); return false; " ><?=$class->status->icon?></a>
				</td>
				
				<td><a href="#" onclick="classesEdit(<?=$class->id?>); return false;" style="font-size: 13px; font-weight: bold; "><?=$class->name?></a></td>
				<td align="center">
					<b><?=count($class->props)?></b><br/>
					<div class="props">
						<table style="width: 100%;">
						<?
						foreach($class->props as $key=>$prop)
						{?>
							<tr style="background: none;" class="option-<?=$prop->status->code?>" >
								<td width="1"><?=++$i; ?>. </td>
								<td ><a href="<?=$prop->adminUrl()?>" target="_blank"><?=$prop->id?>: <?=$prop->name?></a></td>
								<td><input style="font-size: 11px;" type="text" size="1" name="idx[<?=$class->id?>][<?=$prop->id?>]" value="<?=$i*10?>" /></td>
							</tr>
							
						<?php 	
						}?>						
						</table>
					
					</div>
				</td>
				
				<td align="center"><a href="#delete" class="delete-btn" onclick="{if(confirm('Удалить?'))deleteClass(<?=$class->id?>);} return false;" style="font-size: 10px; color: red; ">&times; удалить</a></td>
			</tr>
		<?php 
		}?>
	</table>
	
	<input  type="submit" value="Сохранить изменения в списке" style="display: block; margin: 15px 0 0 00px;">
	<iframe  name="frame7" style="display: none; "></iframe>
</form>

<?php
}
else
{?>
	Ничего нет.
<?php 	
} 
?>
<p>
<input id="add-btn" type="button" onclick="classesEdit(); " value="+ новый класс">
