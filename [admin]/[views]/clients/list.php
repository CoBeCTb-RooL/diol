<?php
$list = $MODEL['list'];
$i=0;
//vd($MODEL);
//vd($client);





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




<h1>Клиенты</h1>



<?php 
if(count($list))
{?>
<form id="list-form" action="/<?=ADMIN_URL_SIGN?>/clients/listSubmit" target="frame7" onsubmit="listSubmitStart()" >
	<table class="t">
		<tr>
			<!--<th>#</th>-->
			<th></th>
			<th>id</th>
			<th>ФИО</th>
			<th>Телефон</th>
			<th>Адрес</th>

			<th></th>

		</tr>
	<?php 
	foreach($list as $client)
	{
		$advCount=0;
		//vd($client);
		?>
		<tr id="cat-<?=$client->id?>" class="status-<?=$client->status ? $client->status->code : ''?> " ondblclick="catEdit(<?=$client->id?>)">
			<!--<td width="1" class="num"><?=++$i?>.</td>-->
			<td width="1"  class="status-switcher">
				<a href="#" id="status-switcher-<?=$client->id?>" onclick="switchStatus(<?=$client->id?>); return false; " ><?=$client->status->icon?></a>
			</td>
			<td width="1" class="id"><?=$client->id?></td>
			<td class="name"><b><?=$client->fio()?></b></td>
			<td class="name"><?=$client->phone?></td>
			<td class="name"><?=$client->address?></td>

			<td><a href="#" onclick="edit(<?=$client->id?>)">ред.</a></td>
		</tr>
	<?php 
	}?>
	</table>

    <div style="margin: 7px 0 30px 0; font-size: 10px; "><?=drawPages($MODEL['totalCount'], $MODEL['p']-1, $MODEL['elPP'], $onclick="opts.p=###; list();", $class="pages");?></div>
	
</form>
<?php 
}
else
{?>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Клиентов пока нет.
<?php 	
}?>

<button style="display: block; margin: 30px 0 0 0 ;" onclick="edit()" >Добавить клиента</button>


