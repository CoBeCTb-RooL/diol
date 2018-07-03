<?php
$list = $MODEL['list'];
$params = $MODEL['params'];
$i=0;


$listByTimes = $MODEL['listByTimes'];
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




<h1>Журнал</h1>

<button style="display: block; margin: 30px 0 0 0 ;" onclick="edit()" >Добавить запись</button>

<div class="filters">
    <div class="section user-id">
        <h1>Телефон:</h1>
        <input type="text" name="phone" value="<?=$params['phone']?>" style="width: 100px;" />
        <input type="button" value="найти" onclick="opts.phone=$('.filters input[name=phone]').val(); opts.p=1;  window.parent.list(); return false; " />&nbsp;<input type="button" value="&times;" onclick="opts.phone=''; opts.p=1; window.parent.list(); return false; " />
    </div>
<!--    <div class="section user-id">-->
<!--        <h1>Email:</h1>-->
<!--        <input type="text" name="email" value="--><?//=$params['email']?><!--" style="width: 100px;" />-->
<!--        <input type="button" value="найти" onclick="opts.email=$('.filters input[name=email]').val(); opts.p=1;  window.parent.list(); return false; " />&nbsp;<input type="button" value="&times;" onclick="opts.email=''; opts.p=1; window.parent.list(); return false; " />-->
<!--    </div>-->
</div>



<table border="1" class="t">
    <?
    foreach ($listByTimes as $time=>$entries)
    {?>
        <tr>
            <td style="width: 1px; "><?=$time?></td>
            <td>
                <?
                foreach ($entries as $entry)
                {?>
                    <div>
                        <table class="entry-info-tbl" cellpadding="0" cellspacing="0" style="border-collapse: collapse; ">
                            <tr>
                                <td>Клиент: </td>
                                <td><?=$entry->client->fio()?><br><?=$entry->client->phone?></td>
                                <td rowspan="3"><a href="#" onclick="edit(<?=$entry->id?>)">ред.</a></td>
                            </tr>
                            <tr>
                                <td>Услуга: </td>
                                <td><?=$entry->service->name?></td>
                            </tr>
                            <tr>
                                <td>Врач: </td>
                                <td><?=$entry->doctor->name?></td>
                            </tr>
                        </table>
                    </div>
                    <hr>
                <?
                }?>
            </td>
        </tr>
    <?
    }?>
</table>


