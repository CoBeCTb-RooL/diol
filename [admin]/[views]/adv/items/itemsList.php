<?php
$chosenCat = $MODEL['cat'];
$status = $MODEL['status']; 
$items = $MODEL['items'];
$dealType = $MODEL['dealType'];
$orderBy = $MODEL['orderBy'];
$desc = $MODEL['desc'];

$cats = $MODEL['cats'];
//vd($cats);

$cities = $MODEL['cities'];
$chosenCity = $MODEL['chosenCity'];

$dateFrom = $MODEL['dateFrom'];
$dateTo = $MODEL['dateTo'];

$chosenId = $MODEL['chosenId'];
$chosenUserId = $MODEL['chosenUserId'];
//vd($cities);
$totalCount = $MODEL['totalCount'];
$p = $MODEL['p'];
$elPP = $MODEL['elPP'];
//vd($orderBy);
//vd($desc);
$i=0;
//vd($cat);
//vd($list);
$upArrowSign = '▲';
$downArrowSign = '▼';

if($chosenCat)
{
	$crumbs[] = '<a href="#cat:0" onclick="cat(0)">КОРЕНЬ</a>';
	foreach($chosenCat->elderCats as $c)
		$crumbs[] = '<a href="#cat:'.$c->id.'" onclick="cat('.$c->id.')">'.$c->name.'</a>';
}
?>



<style>
	.status-<?=Status::ACTIVE?>{}
	.status-<?=Status::INACTIVE?>{opacity: .4;  }
	.status-<?=Status::MODERATION?>{}
	.status-<?=Status::DELETED?>{}
	
	
	
	.id{font-weight: bold !important; }
	.num{font-size: 8px !important; }
	.adv-title{font-weight: bold !important; font-size: .9em !important; width: 500px;}
	.icon-wrapper{white-space: nowrap; }
	.icon-wrapper .label {font-size: 9px; }
	
	.t th{white-space: nowrap; }
	
	/*.filters{margin: 0 0 15px 10px;}
	.filters .section{border: 0px solid black; margin: 0 0 3px 0;}
	.filters .section h1{font-size: 13px; padding: 0; margin: 0;  text-shadow: none; display: inline; }
	.filters .section .item{text-decoration: none; font-size: 9px; padding: 1px 3px; display: inline;   }
	.filters .section .item.active{font-weight: bold;  background: #eee; border-radius: 4px; box-shadow: 1px 1px 0px #aaa;  }
	.filter-vals{margin: 0 0 15px 20px; font-size: 10px; }*/
	
	.statuses{}
</style>




<script>
/*function switchCatStatus(id)
{
	alert(123)
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
}*/
</script>


<?php 
if(!$MODEL['noTop'])
{?>
	<h1>
		<a href="#cat:<?=$chosenCat->pid?>" onclick="$('#cats').slideDown('fast'); $('#items').slideUp('fast')" style="opacity: .4;  font-size: .8em; text-decoration: none;  "><i class="fa fa-backward"></i> назад</a> | 
	<?php 
	if($chosenCat)
	{?>
		<span onclick="Slonne.Adv.AdvItems.itemsList(<?=$chosenCat->id?>);" style="cursor: pointer; "><?=$chosenCat ? $chosenCat->name : 'КОРЕНЬ'?></span>
		<!--<button onclick="Slonne.Adv.AdvItems.itemsList(<?=$chosenCat->id?>);" style="font-size: 10px; margin: 0 0 0 40px;"><i class="fa fa-refresh"></i> обновить</button>-->
	<?php 
	}?>
	</h1>
<?php 
}?>


<div class="filters">
	
	<div class="section statuses">
		<h1>Статус:</h1>
		<a class="item <?=!$status ? 'active' : ''?>" href="#" onclick="Slonne.Adv.AdvItems.LIST_OPTIONS.status=''; Slonne.Adv.AdvItems.itemsList(<?=$chosenCat->id?>); return false; ">ВСЕ</a>
	<?php 
	foreach(AdvItem::$statusesToShow as $statusCode)
	{
		$s = Status::code($statusCode);
		?>
		<a class="item <?=$status->num == $s->num ? 'active' : ''?>" href="#" onclick="Slonne.Adv.AdvItems.LIST_OPTIONS.status='<?=$s->num?>'; Slonne.Adv.AdvItems.itemsList(<?=$chosenCat->id?>); return false; "><?=$s->icon?> <?=$s->name?></a>
	<?php 
	}?>
	</div>
	
	<div class="section deal-types">
		<h1>Тип сделки:</h1>
		<a class="item <?=!$dealType ? 'active' : ''?>" href="#" onclick="Slonne.Adv.AdvItems.LIST_OPTIONS.dealType=''; Slonne.Adv.AdvItems.itemsList(<?=$chosenCat->id?>); return false; ">ВСЕ</a>
	<?php 
	foreach(DealType::$items as $dt)
	{?>
		<a class="item <?=$dealType->num == $dt->num ? 'active' : ''?>" href="#" onclick="Slonne.Adv.AdvItems.LIST_OPTIONS.dealType='<?=$dt->num?>'; Slonne.Adv.AdvItems.itemsList(<?=$chosenCat->id?>); return false; "><?=$dt->icon?> <?=$dt->title?></a>
	<?php 
	}?>
	</div>
	
	
	
	
	
	<div class="section cats">
		<h1>Категория:</h1>
		<select name="" id="" style="font-size: 11px; " onchange="Slonne.Adv.AdvItems.itemsList($(this).val()); return false;">
			<option value="">-все-</option>
		<?php 
		foreach($cats as $cat)
		{?>
			<option style="font-weight: bold; " value="<?=$cat->id?>" <?=$chosenCat->id==$cat->id ? ' selected="selected" ' : ''?>><?=$cat->name?></option>
			<?php 
			foreach($cat->subCats as $subcat)
			{?>
			<option value="<?=$subcat->id?>" <?=$chosenCat->id==$subcat->id ? ' selected="selected" ' : ''?>>&nbsp;&nbsp;&nbsp;&nbsp;<?=$subcat->name?></option>
			<?php 
			}?>
		<?php 
		}?>
		</select>
	</div>
	
	
	<div class="section cities">
		<h1>Город:</h1>
		
		<select name="" id="" style="font-size: 11px; " onchange="Slonne.Adv.AdvItems.LIST_OPTIONS.city=$(this).val(); Slonne.Adv.AdvItems.itemsList(<?=$chosenCat->id?>); return false; ">
			<option value="">-все-</option>
		<?php 
		foreach($cities as $city)
		{?>
			<option style="<?=($city->isLarge ? 'font-weight: bold; ' : '')?>" value="<?=$city->id?>" <?=$chosenCity->id == $city->id ? ' selected="selected" ' : ''?>><?=$city->name?></option>
			
		<?php 
		}?>
		</select>
		
		<!-- <a class="item <?=!$chosenCity ? 'active' : ''?>" href="#" onclick="Slonne.Adv.AdvItems.LIST_OPTIONS.city=''; Slonne.Adv.AdvItems.itemsList(<?=$chosenCat->id?>); return false; ">ВСЕ</a>
	<?php 
	foreach($cities as $city)
	{?>
		<a class="item <?=$chosenCity->id == $city->id ? 'active' : ''?>" href="#" onclick="Slonne.Adv.AdvItems.LIST_OPTIONS.city='<?=$city->id?>'; Slonne.Adv.AdvItems.itemsList(<?=$chosenCat->id?>); return false; "> <?=$city->name?></a>
	<?php 
	}?> -->
	</div>
	
	
	
	<div class="section date">
		<h1>Дата:</h1>
		от <input type="text" name="dateFrom" id="dateFrom" value="<?=$dateFrom?>"  style="width:70px"> <img id="dateFrom-calendar-btn" src="/js/calendar/calendar.jpg" style="border:0px; vertical-align: middle; ">
					
					<script>
						Calendar.setup({
						    inputField     :    "dateFrom",      // id of the input field
						    ifFormat       :    "%Y-%m-%d",       // format of the input field
						    showsTime      :    false,            // will display a time selector
						    button         :    "dateFrom-calendar-btn",   // trigger for the calendar (button ID)
						    singleClick    :    true,           // double-click mode
						    step           :    1                // show all years in drop-down boxes (instead of every other year as default)
						});
				</script>
		&nbsp; до <input type="text" name="dateTo" id="dateTo" value="<?=$dateTo?>"  style="width:70px"> <img id="dateTo-calendar-btn" src="/js/calendar/calendar.jpg" style="border:0px; vertical-align: middle; ">
					
					<script>
						Calendar.setup({
						    inputField     :    "dateTo",      // id of the input field
						    ifFormat       :    "%Y-%m-%d",       // format of the input field
						    showsTime      :    false,            // will display a time selector
						    button         :    "dateTo-calendar-btn",   // trigger for the calendar (button ID)
						    singleClick    :    true,           // double-click mode
						    step           :    1                // show all years in drop-down boxes (instead of every other year as default)
						});
				</script>
			&nbsp;&nbsp;<input type="button" value="взять дату" onclick="Slonne.Adv.AdvItems.LIST_OPTIONS.dateFrom=$('#dateFrom').val(); Slonne.Adv.AdvItems.LIST_OPTIONS.dateTo=$('#dateTo').val(); Slonne.Adv.AdvItems.itemsList(<?=$chosenCat->id?>); return false; " />&nbsp;<input type="button" value="&times;" onclick="Slonne.Adv.AdvItems.LIST_OPTIONS.dateFrom=''; Slonne.Adv.AdvItems.LIST_OPTIONS.dateTo=''; Slonne.Adv.AdvItems.itemsList(<?=$chosenCat->id?>); return false; " />
	</div>
	
	<div class="section id">
		<h1>ID объявления:</h1>
		<input type="text" name="chosenId" id="id" value="<?=$chosenId?>" style="width: 40px;" />
		&nbsp;&nbsp;<input type="button" value="взять id" onclick="Slonne.Adv.AdvItems.LIST_OPTIONS.chosenId=$('#id').val();Slonne.Adv.AdvItems.itemsList(<?=$chosenCat->id?>); return false; " />&nbsp;<input type="button" value="&times;" onclick="Slonne.Adv.AdvItems.LIST_OPTIONS.chosenId='';Slonne.Adv.AdvItems.itemsList(<?=$chosenCat->id?>); return false; " />
	</div>
	
	<div class="section user-id">
		<h1>ID пользователя:</h1>
		<input type="text" name="chosenUserId" id="user-id" value="<?=$chosenUserId?>" style="width: 40px;" />
		&nbsp;&nbsp;<input type="button" value="взять id" onclick="Slonne.Adv.AdvItems.LIST_OPTIONS.chosenUserId=$('#user-id').val();Slonne.Adv.AdvItems.itemsList(<?=$chosenCat->id?>); return false; " />&nbsp;<input type="button" value="&times;" onclick="Slonne.Adv.AdvItems.LIST_OPTIONS.chosenUserId='';Slonne.Adv.AdvItems.itemsList(<?=$chosenCat->id?>); return false; " />
	</div>
	
	
	<div class="clear"></div>
</div>


<?php 
//vd($_REQUEST);
//vd($status);
?>
<div class="filter-vals" >
	
	<div>Статус: <b><?=$status ? $status->name : 'ВСЕ'?></b>, 
	Сделка: <b><?=$dealType ? $dealType->title : 'ВСЕ'?></b>, 
	Категория: <b><?=$chosenCat ? $chosenCat->name : 'ВСЕ'?></b>, 
	Город: <b><?=$chosenCity ? $chosenCity->name : 'ВСЕ'?></b>,
	<?php 
	$dateStr = ($dateFrom ? 'от '.$dateFrom : '').' '.($dateTo ? 'до '.$dateTo : '')?>
	Дата: <b><?=trim($dateStr) ? $dateStr : 'не указана'?></b></div>
	ID: <b><?=$chosenId ? $chosenId : 'не указан'?></b>,
	ID юзера: <b><?=$chosenUserId ? $chosenUserId : 'не указан'?></b>,
</div>


<?php
if(count($items))
{?>
	<div style="padding: 0 0 0 0px; font-size: 16px; ">Объявлений: <b><?=$MODEL['totalCount']?></b></div>
	<div style="margin: 0px 0 3px 0; font-size: 10px;  "><?=drawPages($totalCount, $MODEL['p'], $MODEL['elPP'], $onclick="Slonne.Adv.AdvItems.LIST_OPTIONS.p=###; Slonne.Adv.AdvItems.itemsList();", $class="pages");?></div>
	<table class="t">
		<tr>
			<th>#</th>
			<th><a href="#" onclick="Slonne.Adv.AdvItems.LIST_OPTIONS.orderBy='id'; Slonne.Adv.AdvItems.LIST_OPTIONS.desc='<?=$orderBy=='id' ? ($desc ? 0 : 1) : 0?>'; Slonne.Adv.AdvItems.itemsList(<?=$chosenCat->id?>); return false; ">id <?=$orderBy=='id' ? ($desc ? $downArrowSign : $upArrowSign) : '' ?></a> </th>
			<th><a href="#" onclick="Slonne.Adv.AdvItems.LIST_OPTIONS.orderBy='status'; Slonne.Adv.AdvItems.LIST_OPTIONS.desc='<?=$orderBy=='status' ? ($desc ? 0 : 1) : 0?>'; Slonne.Adv.AdvItems.itemsList(<?=$chosenCat->id?>); return false; ">Статус <?=$orderBy=='status' ? ($desc ? $downArrowSign : $upArrowSign) : '' ?></a> </th>
			<th><a href="#" onclick="Slonne.Adv.AdvItems.LIST_OPTIONS.orderBy='dealTypeId'; Slonne.Adv.AdvItems.LIST_OPTIONS.desc='<?=$orderBy=='dealTypeId' ? ($desc ? 0 : 1) : 0?>'; Slonne.Adv.AdvItems.itemsList(<?=$chosenCat->id?>); return false; ">Тип <?=$orderBy=='dealTypeId' ? ($desc ? $downArrowSign : $upArrowSign) : '' ?></a> </th>
			<th><a href="#" onclick="Slonne.Adv.AdvItems.LIST_OPTIONS.orderBy='name'; Slonne.Adv.AdvItems.LIST_OPTIONS.desc='<?=$orderBy=='name' ? ($desc ? 0 : 1) : 0?>'; Slonne.Adv.AdvItems.itemsList(<?=$chosenCat->id?>); return false; ">Заголовок <?=$orderBy=='name' ? ($desc ? $downArrowSign : $upArrowSign) : '' ?></a> </th>
			<th><a href="#" onclick="Slonne.Adv.AdvItems.LIST_OPTIONS.orderBy='dateCreated'; Slonne.Adv.AdvItems.LIST_OPTIONS.desc='<?=$orderBy=='dateCreated' ? ($desc ? 0 : 1) : 0?>'; Slonne.Adv.AdvItems.itemsList(<?=$chosenCat->id?>); return false; ">Дата создания <?=$orderBy=='dateCreated' ? ($desc ? $downArrowSign : $upArrowSign) : '' ?></a> </th>
			<th><a href="#" onclick="Slonne.Adv.AdvItems.LIST_OPTIONS.orderBy='catId'; Slonne.Adv.AdvItems.LIST_OPTIONS.desc='<?=$orderBy=='catId' ? ($desc ? 0 : 1) : 0?>'; Slonne.Adv.AdvItems.itemsList(<?=$chosenCat->id?>); return false; ">Категория <?=$orderBy=='catId' ? ($desc ? $downArrowSign : $upArrowSign) : '' ?></a> </th>
			
			<th><a href="#" onclick="Slonne.Adv.AdvItems.LIST_OPTIONS.orderBy='userId'; Slonne.Adv.AdvItems.LIST_OPTIONS.desc='<?=$orderBy=='userId' ? ($desc ? 0 : 1) : 0?>'; Slonne.Adv.AdvItems.itemsList(<?=$chosenCat->id?>); return false; ">Пользователь <?=$orderBy=='userId' ? ($desc ? $downArrowSign : $upArrowSign) : '' ?></a></th>
			<th><a href="#" onclick="Slonne.Adv.AdvItems.LIST_OPTIONS.orderBy='cityId'; Slonne.Adv.AdvItems.LIST_OPTIONS.desc='<?=$orderBy=='cityId' ? ($desc ? 0 : 1) : 0?>'; Slonne.Adv.AdvItems.itemsList(<?=$chosenCat->id?>); return false; ">Город <?=$orderBy=='cityId' ? ($desc ? $downArrowSign : $upArrowSign) : '' ?></a> </th>
			<th>Контакты</th>
		</tr>
	<?php 
	foreach($items as $item)
	{
	//vd($item->url())?>
		<tr id="row-<?=$item->id?>" class="status-<?=$item->status->code?>">
			<td width="1" class="num"><?=$p*$elPP+(++$i)?>.</td>
			<td width="1"><?=$item->id?></td>
			<td width="1" class="icon-wrapper" ><?=$item->status->icon?> <span class="label" ><?=$item->status->name?></span></td>
			<td width="1" class="icon-wrapper" ><?=$item->dealType->icon?> <span class="label" ><?=$item->dealType->title?></span></td>
			<td class="adv-title"><a href="<?=$item->url()?>" target="_blank"><?=$item->name?></a></td>
			<td><?=$item->dateCreated?></td>
			<td><a href="<?=$item->cat->urlAdmin()?>" target="_blank"><?=$item->cat->name?></a></td>
			<td><a href="/<?=ADMIN_URL_SIGN?>/user/?userId=<?=$item->user->id?>" target="_blank"><?=$item->user->fullName?></a></td>
			<td><?=$item->city->name?></td>
			<td>
				<i class="fa fa-user" style="width: 12px;"></i> <?=$item->contactName ? $item->contactName: '--'?><br/>
				<i class="fa fa-phone" style="width: 12px;"></i> <?=$item->phone ? $item->phone : '--'?><br/>
				<i class="fa fa-envelope-o" style="width: 12px;"></i> <?=$item->email ? $item->email: '--'?><br/>
			</td>
		</tr>
	<?php 
	}?>
	</table>
	
	<div style="margin: 7px 0 30px 0; font-size: 10px; "><?=drawPages($totalCount, $MODEL['p'], $MODEL['elPP'], $onclick="Slonne.Adv.AdvItems.LIST_OPTIONS.p=###; Slonne.Adv.AdvItems.itemsList();", $class="pages");?></div>
<?php 
}
else
{?>
	&nbsp;&nbsp;&nbsp;&nbsp;Объявления не найдены. 
<?php 
}
?>















<iframe name="frame7" style="display: none; "></iframe>