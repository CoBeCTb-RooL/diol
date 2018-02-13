<?php
$brands = $MODEL['brands'];

//vd($catsTree);
?>

<style>
	.section{border-right: 1px solid #ccc; display: table-cell; padding: 0 40px ; vertical-align: top;  }
	.brand{margin: 0 0 4px 0; }
	.brand a{ text-decoration: none; }
	.brand a.active{font-weight: bold; text-decoration: underline;  }
	.subs{margin: 0 0 11px 25px; }
	.subs a{display: block; margin: 0 0 3px 0;  }
	h3{margin: 10px 0 10px 0; padding: 0; font-size: 18px; }
</style>



<script>
var CHOSEN_BRAND = '';

function changeBrand(id, process)
{
	CHOSEN_BRAND = id 
	//alert(id)
	$('.brand a').removeClass('active')
	$('#brand-'+id).addClass('active')
	
	var fromExcel = typeof $('#from-excel').val() == 'undefined' ? '' : $('#from-excel').val()
	
	$.ajax({
		url: '/'+Slonne.ADMIN_URL_SIGN+'/'+Slonne.Adv.URL_SECTION+'/brand_artnum_combine/artnums_list_ajax/',
		data: "brand="+id+"&fromExcel="+encodeURIComponent(fromExcel)+"&process="+encodeURIComponent(process)+"",
		beforeSend: function(){$('.artnums .inner').css('opacity', '.3'); $('.artnums .loading').css('display', 'block')},
		success: function(data){$('.artnums .inner').html(data)},
		error: function(err){error('Возникла ошибка на сервере...')},
		complete: function(){$('.artnums .inner').css('opacity', '1'); $('.artnums .loading').css('display', 'none')} 
	});
}


function switchArtnumCheckbox(id)
{
	var checked = $("#artnum-cb-"+id+"").is(':checked') ? 1 : 0 

	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/adv/brand_artnum_combine/check_artnum/',
		data: "brand="+CHOSEN_BRAND+"&artnum="+id+"&checked="+checked,
		dataType: "json",
		beforeSend: function(){$.fancybox.showLoading(); $('#artnum-wrapper-'+id).css('opacity', .3); },
		success: function(data){
			if(!data.errors)
			{
				if(data.checked > 0) 
					$("#artnum-cb-"+id+"").attr('checked', 'checked');
				else
					$("#artnum-cb-"+id+"").removeAttr('checked')
			}
			else
				showErrors(data.errors)
		},
		error: function(err){error('Возникла ошибка на сервере...')},
		complete: function(){$.fancybox.hideLoading(); $('#artnum-wrapper-'+id).css('opacity', 1); } 
	});
	
}
</script>




<?php Core::renderPartial('adv/menu.php', $model);?>



<h1>Бренд + Арт.номер</h1>



<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>




<div class="section brands">
	<h3>Бренды</h3>
	<?php 
	foreach($brands as $key=>$item)
	{?>
		<div class="brand">
			<a href="#" id="brand-<?=$item->id?>" onclick="$('#from-excel').val(''); changeBrand(<?=$item->id?>); return false; "> <span style="font-size: 12px;">(<?=$item->id ?>)</span> <?=$item->name?> <span style="font-size: 10px; font-weight: normal; ">| арт.номеров: <b><?=count($item->brandArtnumCombines)?></b></span> </a>
		</div>
	<?php 	
	}?>
</div>


<div class="section artnums">
	<h3>Арт. номера</h3>
	<div class="inner">&larr; Выберите бренд сперва</div>
	<div class="loading" style="display: none;">заргузка...</div>
</div>


