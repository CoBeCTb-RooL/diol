<?php
$cat = $MODEL['cat'];
$units = $MODEL['units'];
//vd($cat);
?>

<script>
function unitClick(id)
{
	//alert($('#unit-'+id).is(':checked'))
	return
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/product_volume_unit/edit',
		data: 'id='+encodeURIComponent(id)+'&checked='+encodeURIComponent(),
		beforeSend: function(){$.fancybox.showLoading()},
		success: function(data){
			$('#float').html(data)
			$.fancybox('#float');
		},
		error: function(e){alert(e=='' ? e : 'Возникла ошибка на сервере... Попробуйте позже.')},
		complete: function(){$.fancybox.hideLoading()}
	});
}
</script>




<h1><span style="font-weight: normal; ">Меры объёма для</span> <b>"<?=$cat->name?>"</b></h1>
<?php 
if(count($units))
{?>
	<?php 
	foreach($units as $u)
	{?>
		<div class="unit-status-<?=$u->status->code?>">
			<label ><input type="checkbox" name="u" id="unit-<?=$u->id?>" onclick="unitClick(<?=$u->id?>)" <?=$cat->productVolumeUnits[$u->id] ? ' checked="checked" ' : ''?> /> <?=$u->name?></label>
		</div>
	<?php 	
	}?>	
<?php 	
}
else
{?>
	В базе нет единиц.
<?php 	
}?>