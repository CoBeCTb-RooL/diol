<?php
$cat = $MODEL['cat'];
$list = $MODEL['list']; 
$i=0;
//vd($cat);




if($cat)
{
	$crumbs[] = '<a href="#cat:0" onclick="cat(0)">КОРЕНЬ</a>';
	foreach($cat->elderCats as $c)
		$crumbs[] = '<a href="#cat:'.$c->id.'" onclick="cat('.$c->id.')">'.$c->name.'</a>';
}
?>



<style>
	.status-active{}
	.status-inactive{opacity: .4;  }
	
	
	.id{font-weight: bold !important; }
	.num{}
	.name a{font-weight: bold; font-size: 1.3em; }
	
	.statuses{}
	.statuses .item{ margin: 0 0 3px 0; }
</style>




<script>
function switchCatStatus(id)
{
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/adv/cats/catsSwitchStatus',
		data: 'catId='+id,
		dataType: 'json',
		beforeSend: function(){},
		success: function(data){
			if(data.error=='')
			{
				$('#status-switcher-'+id).html(data.status.icon)
				$('#cat-'+id).removeAttr('class').addClass('status-'+data.status.code)
			}
			else
				this.error(data.error)
		},
		error: function(e){alert(e=='' ? e : 'Возникла ошибка на сервере... Попробуйте позже.')},
		complete: function(){}
	});
}
</script>


<div class="crumbs" style="margin: 0 0 15px 0; "> 
<?=join('&nbsp;/&nbsp;', $crumbs)?>
</div>

<h1>
<?php 
if($cat)
{?>
	<a href="#cat:<?=$cat->pid?>" onclick="cat(<?=$cat->pid?>)" style="opacity: .4;  font-size: .8em; text-decoration: none;  "><i class="fa fa-backward"></i> назад</a> | 
<?php 
}?>

	<span onclick="cat(CHOSEN_CAT)" style="cursor: pointer; "><?=$cat ? $cat->name : 'КОРЕНЬ'?></span>
</h1>



<?php 
if(count($list))
{?>
<form id="list-form" action="/<?=ADMIN_URL_SIGN?>/adv/cats/catsListSubmit" target="frame7" onsubmit="$('#cats').css('opacity', '.4')" >
	<table class="t">
		<tr>
			<th>#</th>
			<th></th>
			<th>id</th>
			<th>Категория</th>
			
			<th></th>
			<th>Класс</th>
			<th>idx</th>
			<th>Объявления</th>
		</tr>
	<?php 
	foreach($list as $cat)
	{
		$advCount=0;
		//vd($cat);
		?>
		<tr id="cat-<?=$cat->id?>" class="status-<?=$cat->status ? $cat->status->code : ''?> " ondblclick="catEdit(<?=$cat->id?>)">
			<td width="1" class="num"><?=++$i?>.</td>
			<td width="1"  class="status-switcher">
				<a href="#" id="status-switcher-<?=$cat->id?>" onclick="switchCatStatus(<?=$cat->id?>); return false; " ><?=$cat->status->icon?></a>
			</td>
			<td width="1" class="id"><?=$cat->id?></td>
			<td class="name">
				<a href="#cat:<?=$cat->id?>" onclick="cat(<?=$cat->id?>);  ">
					<?=$cat->name?> 
				</a>
				<div style="font-size: .8em; margin: 0 0 0 7px; ">подкат.: <b><?=$cat->subCatsCount?></b></div>
			</td>
			
			<td><a href="#cat_edit:<?=$cat->id?>" onclick="catEdit(<?=$cat->id?>)">ред.</a></td>
			
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
			
			<td><input type="text" size="3" name="idx[<?=$cat->id?>]" value="<?=$cat->idx?>" style="font-size: 11px; " /></td>
			
			<td>
				
				
				<div class="statuses" >
				
					<div class="item">
					<?php 
					if($cat->advsCount)
					{?>
						<a href="#" onclick="Slonne.Adv.AdvItems.itemsList(<?=$cat->id?>, {}); return false; " style="text-decoration: none; ">Все: <b><?=$cat->advsCount?></b></a>
					<?php 
					}
					else
					{?>
						<span style="opacity: .4; ">Все: <b>0</b></span>
					<?php 
					}?>
					</div>
						
					
						
				<?php 
				foreach(AdvItem::$statusesToShow as $statusCode)
				{
					$status=Status::code($statusCode);
					$count = $cat->advsCountByStatus[$status->num] ? $cat->advsCountByStatus[$status->num] : 0 ; 
					?>
					<div class="item" >
					<?php 
					if($count > 0)
					{?>
						<a href="#" onclick="Slonne.Adv.AdvItems.itemsList(<?=$cat->id?>, {status: '<?=$status->num?>'}); return false; " style="text-decoration: none; "><?=$status->icon?> <?=$status->name?>: <b><?=$count?></b></a>
					<?php 
					}
					else
					{?>
						<span style="opacity: .4; "><?=$status->icon?> <?=$status->name?>: <b><?=$count?></b></span>						
					<?php 	
					}?>
					</div>
				<?php 
				}?>
				
				</div>
			</td>
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

<button style="display: block; margin: 30px 0 0 0 ;" onclick="catEdit('', CHOSEN_CAT)" >+ категория</button>


<iframe name="frame7" style="display: none; "></iframe>