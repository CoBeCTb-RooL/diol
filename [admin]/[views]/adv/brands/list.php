<?php
$list = $MODEL['list']; 
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
<form id="list-form" method="post" action="/<?=ADMIN_URL_SIGN?>/adv/brands/listSubmit" target="frame7" onsubmit="listSubmitStart();" >
	<table class="t">
		<tr>
			<th>id</th>
			<th></th>
			
			<th>Название</th>
			<th>Картинка</th>
			<th>Сорт.</th>
			<th>Удалить</th>
		</tr>
		<?php 
		foreach($list as $key=>$brand)
		{?>
			<tr id="row-<?=$brand->id?>" class="status-<?=$brand->status ? $brand->status->code : ''?> "  ondblclick="edit(<?=$brand->id?>)">
				<td><?=$brand->id?></td>
				<td width="1"  class="status-switcher">
					<a href="#" id="status-switcher-<?=$brand->id?>" onclick="switchBrandStatus(<?=$brand->id?>); return false; " ><?=$brand->status->icon?></a>
				</td>
				<td><a href="#edit" onclick="edit(<?=$brand->id?>); return false;" style="font-weight: bold; "><?=$brand->name?></a></td>
				
				<td><a class="highslide" href="/<?=UPLOAD_IMAGES_REL_DIR.$brand->pic?>" onclick="return hs.expand(this)" title="Нажмите, чтобы увеличить"><img src="<?=Media::img($brand->pic.'&height=30')?>" alt="" /></a></td>
				
				<td><input size="2" style="width: 25px; font-size: 9px;" id="idx-<?=$brand->id?>" name="idx[<?=$brand->id?>]" value="<?=$brand->idx?>" type="text"></td>
				<td><a href="#delete" class="delete-btn" onclick="if(confirm('Удалить?')){delete1(<?=$brand->id?>);} return false;">удалить</a></td>
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

<p><input id="add-btn" type="button" onclick="edit(); " value="+ бренд">