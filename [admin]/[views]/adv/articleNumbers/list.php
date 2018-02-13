<?php
$list = $MODEL['list']; 
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
if(count($list) )
{?>
Всего: <b><?=count($list)?></b>
<form id="list-form" method="post" action="/<?=ADMIN_URL_SIGN?>/adv/article_numbers/listSubmit" target="frame7" onsubmit="listSubmitStart();" >
	<table class="t">
		<tr>
			<th>id</th>
			<th>Акт.</th>
			<th></th>
			<th>Название</th>
			<th>Картинка</th>
			<th>Сорт.</th>
			<th>Удалить</th>
		</tr>
		<?php 
		foreach($list as $key=>$artNum)
		{?>
			<tr id="row-<?=$artNum->id?>" class="status-<?=$artNum->status ? $artNum->status->code : ''?> "  ondblclick="edit(<?=$artNum->id?>)">
				<td><?=$artNum->id?></td>
				<td width="1"  class="status-switcher" style="text-align: center; ">
					<a href="#" id="status-switcher-<?=$artNum->id?>" onclick="switchBrandStatus(<?=$artNum->id?>); return false; " ><?=$artNum->status->icon?></a>
				</td>
				<td><a href="#edit" onclick="edit(<?=$artNum->id?>); return false;">ред.</a></td>
				<td style="font-weight: bold; "><?=$artNum->icon?> <?=$artNum->name?></td>
				
				<td><a class="highslide" href="/<?=UPLOAD_IMAGES_REL_DIR.$artNum->pic?>" onclick="return hs.expand(this)" title="Нажмите, чтобы увеличить"><img src="<?=Media::img($artNum->pic.'&height=30')?>" alt="" /></a></td>
				
				<td><input size="2" style="width: 25px; font-size: 9px;" id="idx-<?=$artNum->id?>" name="idx[<?=$artNum->id?>]" value="<?=$artNum->idx?>" type="text"></td>
				<td>
					<a href="#delete" class="delete-btn status-<?=$item->status->code?>" onclick="if(confirm('Удалить?')){delete1(<?=$artNum->id?>);} return false;" style="font-size: 10px; color: red; ">&times; удалить</a>
				</td>
			</tr>
		<?php 
		}?>
	</table>
	<input type="submit" id="list-submit-btn" value="Сохранить изменения">
</form>
	
	
<?php
}
else
{?>
	Ничего нет.
<?php 	
} 
?>

<p><input id="add-btn" type="button" onclick="/*Slonne.Adv.ArtNums.edit();*/ edit();  " value="+ артикульный номер">