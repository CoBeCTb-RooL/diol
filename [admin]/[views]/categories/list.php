<?php
$cat = $MODEL['cat'];
$list = $MODEL['list']; 
$i=0;
//vd($cat);




if($cat)
{
	$crumbs[] = '<a href="#" onclick="list(0)">КОРЕНЬ</a>';
	foreach($cat->elderCats as $c)
		$crumbs[] = '<a href="#" onclick="list('.$c->id.')">'.$c->name.'</a>';
}
?>


<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>


<style>
	.status-active{}
	.status-inactive{opacity: .4;  }
	
	
	.id{font-weight: bold !important; }
	.num{}
	.name a{font-weight: bold; font-size: 1.3em; }
	
	.statuses{}
	.statuses .item{ margin: 0 0 3px 0; }
	
	.unit-status-<?=Status::ACTIVE?>{}
	.unit-status-<?=Status::INACTIVE?>{opacity: .3; }
	.unit-item{padding: 0 0 1px 0;}
</style>





<div class="crumbs" style="margin: 0 0 15px 0; "> 
<?=join('&nbsp;/&nbsp;', $crumbs)?>
</div>

<h1>
<?php 
if($cat)
{?>
	<a href="#" onclick="list(<?=$cat->pid?>)" style="opacity: .4;  font-size: .8em; text-decoration: none;  "><i class="fa fa-backward"></i> назад</a> | 
<?php 
}?>

	<span onclick="cat(CHOSEN_CAT)" style="cursor: pointer; "><?=$cat ? $cat->name : 'КОРЕНЬ'?></span>
</h1>



<?php 
if(count($list))
{?>
<form id="list-form" action="/<?=ADMIN_URL_SIGN?>/categories/listSubmit" target="frame7" onsubmit="listSubmitStart()" >
	<table class="t">
		<tr>
			<!--<th>#</th>-->
			<th></th>
			<th>id</th>
			<th>Категория</th>
			
			<th></th>
			<th>Класс</th>
			<th>Мера</th>
			<th>idx</th>
			<th colspan="5">Объявления</th>
		</tr>
	<?php 
	foreach($list as $cat)
	{
		$advCount=0;
		//vd($cat);
		?>
		<tr id="cat-<?=$cat->id?>" class="status-<?=$cat->status ? $cat->status->code : ''?> " ondblclick="catEdit(<?=$cat->id?>)">
			<!--<td width="1" class="num"><?=++$i?>.</td>-->
			<td width="1"  class="status-switcher">
				<a href="#" id="status-switcher-<?=$cat->id?>" onclick="switchStatus(<?=$cat->id?>); return false; " ><?=$cat->status->icon?></a>
			</td>
			<td width="1" class="id"><?=$cat->id?></td>
			<td class="name">
				<a href="#" onclick="list(<?=$cat->id?>);  "><?=$cat->name?></a>
				<div style="font-size: .8em; margin: 0 0 0 7px; ">подкат.: <b><?=$cat->subCatsCount?></b></div>
			</td>
			
			<td><a href="#" onclick="edit(<?=$cat->id?>)">ред.</a></td>
			
			<td>
			<?php 
			if($cat->class)
			{?>
				<a href="/<?=ADMIN_URL_SIGN?>/adv/classes/?id=<?=$cat->class->id?>" target="_blank" style="font-weight: bold; "><?=$cat->class->name?></a>
			<?php 
			}
			else
			{?>
				<span style="color: red; ">-нет-</span>
			<?php 
			}?>
			</td>
			
			<td  >
				<div id="cats-list-units-wrapper-<?=$cat->id?>">
				<?php 
				if(count($cat->productVolumeUnits))
				{
					foreach($cat->productVolumeUnits as $key=>$item)
					{?>
						<div class="unit-item unit-status-<?=$item->status->code?>" > - <?=$item->name?></div>
					<?php 	
					}
				}
				else
				{?>
					<div>-не указано-</div> 
				<?php 	
				}?>
				</div>
				
				<a href="#" onclick="editUnits(<?=$cat->id?>); return false; " style="font-size: 10px; ">редактировать</a>
			</td>
			
			<td><input type="text" size="3" name="idx[<?=$cat->id?>]" value="<?=$cat->idx?>" style="font-size: 11px; " /></td>
			
			
			<td>
				<a href="#" onclick="Slonne.Adv.AdvItems.itemsList(<?=$cat->id?>, {}); return false; " style="text-decoration: none; <?=($cat->advsCount==0 ? 'opacity: .4;':'')?> ">Все: <b><?=$cat->advsCount?></b></a>
			</td>
			
			<?php 
			foreach(AdvItem::$statusesToShow as $statusCode)
			{
				$status=Status::code($statusCode);
				$count = $cat->advsCountByStatus[$status->num] ? $cat->advsCountByStatus[$status->num] : 0 ; 
				?>
				<td>
					<a href="#" onclick="Slonne.Adv.AdvItems.itemsList(<?=$cat->id?>, {status: '<?=$status->num?>'}); return false; " style="text-decoration: none; <?=($count==0 ? 'opacity: .4;' : '' ) ?> "><?=$status->icon?> <?=$status->name?>: <b><?=$count?></b></a>
				</td>
			<?php 
			}?>
			
		</tr>
	<?php 
	}?>
	</table>
	
	<input type="submit" value="сохранить изменения" style="display: block; margin: 12px 0 0 0 ;"  />
</form>
<?php 
}
else
{?>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Категорий нет. 
<?php 	
}?>

<button style="display: block; margin: 30px 0 0 0 ;" onclick="edit('', CHOSEN_CAT)" >+ категория</button>


<iframe name="frame7" style="display: none; "></iframe>