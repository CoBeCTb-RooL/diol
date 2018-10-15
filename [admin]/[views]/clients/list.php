<?php
$list = $MODEL['list'];
$params = $MODEL['params'];
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


<div class="filters">

    <form action="" onsubmit="opts.phone=$('.filters input[name=phone]').val(); opts.p=1;  window.parent.list(); return false; ">
        <div class="section user-id">
            <h1>Телефон:</h1>
            <input type="text" name="phone" value="<?=$params['phone']?>" style="width: 100px;" />

            <input type="submit" value="найти" onclick="" />&nbsp;
            <input type="button" value="&times;" onclick="opts.phone=''; opts.p=1; window.parent.list(); return false; " />
        </div>
    </form>



    <form action="" onsubmit="
                            opts.surname=$('.filters input[name=surname]').val();
                            opts.name=$('.filters input[name=name]').val();
                            opts.fathername=$('.filters input[name=name]').val();
                            opts.p=1;
                            window.parent.list();
                            return false;
    ">
        <div class="section user-id">
            <h1>ФИО:</h1>
            <input type="text" name="surname" value="<?=$params['surnameLike']?>" style="width: 100px;" placeholder="Фамилия" />
            <input type="text" name="name" value="<?=$params['nameLike']?>" style="width: 100px;"  placeholder="Имя" />
            <input type="text" name="fathername" value="<?=$params['fatherNameLike']?>" style="width: 100px;" placeholder="Отчество"  />


            <input type="submit" value="найти"  />&nbsp;
            <input type="button" value="&times;" onclick="opts.surname=''; opts.name=''; opts.fathername=''; opts.p=1; window.parent.list(); return false; " />
        </div>
    </form>



<!--    <div class="section user-id">-->
<!--        <h1>Email:</h1>-->
<!--        <input type="text" name="email" value="--><?//=$params['email']?><!--" style="width: 100px;" />-->
<!--        <input type="button" value="найти" onclick="opts.email=$('.filters input[name=email]').val(); opts.p=1;  window.parent.list(); return false; " />&nbsp;<input type="button" value="&times;" onclick="opts.email=''; opts.p=1; window.parent.list(); return false; " />-->
<!--    </div>-->
</div>



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


