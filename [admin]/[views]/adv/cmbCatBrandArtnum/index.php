<?php
$catsTree = $MODEL['catsTree'];

//vd($catsTree);
?>

<style>
	.section{border-right: 1px solid #ccc; display: table-cell; padding: 0 40px ; vertical-align: top;  }
	.cat a{ text-decoration: none; }
	.cat a.active{font-weight: bold; text-decoration: underline;  }
	.primary-cat{font-size: 15px; }
	.subs{margin: 0 0 11px 25px; }
	.subs a{display: block; margin: 0 0 3px 0;  }
	h3{margin: 10px 0 10px 0; padding: 0; font-size: 18px; }
	
	.status-active{}
	.status-inactive{opacity: .5; }
</style>



<script>
var CHOSEN_CAT = '';
var CHOSEN_BRAND = '';

function changeCat(id)
{
	CHOSEN_CAT = id 
	//alert(id)
	$('.cat a').removeClass('active')
	$('#cat-'+id).addClass('active')
	$('.artnums .inner').html('&larr; Выберите бренд сперва')
	
	$.ajax({
		url: '/'+Slonne.ADMIN_URL_SIGN+'/'+Slonne.Adv.URL_SECTION+'/cat_brand_artnum_combine/brands_list_ajax/',
		data: "cat="+id+"",
		beforeSend: function(){$('.brands .inner').css('opacity', '.3'); $('.brands .loading').css('display', 'block')},
		success: function(data){$('.brands .inner').html(data)},
		error: function(err){error('Возникла ошибка на сервере...')},
		complete: function(){$('.brands .inner').css('opacity', '1'); $('.brands .loading').css('display', 'none')} 
	});
}



function changeBrand(id)
{
	CHOSEN_BRAND = id 
	//alert(id)
	$('.brand a').removeClass('active')
	$('#brand-'+id).addClass('active')
	
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/adv/cat_brand_artnum_combine/artnums_list_ajax/',
		data: "brand="+id+"&cat="+CHOSEN_CAT+"",
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
		url: '/<?=ADMIN_URL_SIGN?>/adv/cat_brand_artnum_combine/check_artnum/',
		data: "cat="+CHOSEN_CAT+"&brand="+CHOSEN_BRAND+"&artnum="+id+"&checked="+checked,
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



<h1>Категория + бренд + арт.номер</h1>


<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>


<div class="section cat">
	<h3>Категории</h3>
	<?php 
	foreach($catsTree as $key=>$cat)
	{?>
		<div class="cat">
			<a href="#" id="cat-<?=$cat->id?>" class="primary-cat status-<?=$cat->status->code?>" onclick="changeCat(<?=$cat->id?>); return false; "> <span style="font-size: 12px;">(<?=$cat->id ?>)</span> <?=$cat->name?> <span style="font-size: 10px; font-weight: normal; ">| брендов: <b><?=count($cat->catBrandCombines)?></b></span> </a>
			<?php
			if($cat->subs)
			{?>
			<div class="subs">
				<?php 
				foreach($cat->subs as $key=>$sub)
				{?>
					<a href="#" id="cat-<?=$sub->id?>" onclick="changeCat(<?=$sub->id?>); return false; " class="primary-cat status-<?=$sub->status->code?>"><span style="font-size: 9px;">(<?=$sub->id ?>)</span> <?=$sub->name?> <span style="font-size: 10px; font-weight: normal; ">| брендов: <b><?=count($sub->catBrandCombines)?></b></span> </a>
				<?php 
				}?>
			</div>
			<?php 
			}?>
		</div>
	<?php 	
	}?>
</div>


<div class="section brands">
	<h3>Бренды</h3>
	<div class="inner">&larr; Выберите категорию сперва</div>
	<div class="loading" style="display: none;">заргузка...</div>
</div>



<div class="section artnums">
	<h3>Арт. номера</h3>
	<div class="inner"></div>
	<div class="loading" style="display: none;">заргузка...</div>
</div>














