<?php
$list = $MODEL['list'];
$params = $MODEL['params'];
$i=0;


$listByTimes = $MODEL['listByTimes'];
//vd($MODEL);
//vd($client);



$doctors = $MODEL['doctors'];



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

    .past{text-decoration: line-through; opacity: .5;  }
	.unit-status-<?=Status::ACTIVE?>{}
	.unit-status-<?=Status::INACTIVE?>{opacity: .3; }
	.unit-item{padding: 0 0 1px 0;}

    .client{ width: 220px; }
    .phone{font-size: .9em; color: #777; font-style: italic; }
    .service{font-weight: bold !important; width: 150px; font-size: 1.1em;   }

    .entry{border: 1px solid #c9c900; background: #eaff8b !important; font-size: 1.4em !important; margin: 0 0 3px 0; }
    .entry:hover{background: #eaff8b !important; }
    .entry-info-tbl{}
    .entry-info-tbl td{border: none; }
    .entry-info-tbl .btns{width: 60px; text-align: center; }
</style>




<h1>Журнал</h1>

<!--<button style="display: block; margin: 30px 0 0 0 ;" onclick="edit()" >Добавить запись</button>-->

<div class="filters">
    <div class="section user-id">
        <h1>Дата:</h1>
        <a href="#" onclick="setDate('<?=date('Y-m-d', strtotime($params['date'])-3600*24)?>'); list1();  return false; ">&larr;пред.</a>
        &nbsp;<input type="text" id="filterDate" value="<?=$params['date']?>" onkeyup="setDate($(this).val());  list1();"  onchange=" opts.date=$(this).val(); editOpts.date=$(this).val(); list1();  " style="width: 67px;" />
        <img id="filterDate-calendar-btn" src="/js/calendar/calendar.jpg" style="border:0px;">
        <script>
            Calendar.setup({
                inputField     :    "filterDate",      // id of the input field
                ifFormat       :    "%Y-%m-%d",       // format of the input field
                showsTime      :    false,            // will display a time selector
                button         :    "filterDate-calendar-btn",   // trigger for the calendar (button ID)
                singleClick    :    true,           // double-click mode
                step           :    1                // show all years in drop-down boxes (instead of every other year as default)
            });
        </script>
        &nbsp;<a href="#" onclick="setDate('<?=date('Y-m-d', strtotime($params['date'])+3600*24)?>'); list1();  return false; ">след.&rarr;</a>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <a href="#" onclick="setDate('<?=date('Y-m-d')?>'); list1();  return false; ">СЕГОДНЯ</a>
    </div>


    <?
    if(!$ADMIN->isDoctor())
    {?>
    <div class="section user-id">
        <h1>Врач:</h1>
        <select onchange="opts.doctorId=$(this).val(); list1(); ">
            <option value="">-все-</option>
            <?
            foreach ($doctors as $doctor) {
                ?>
                <option value="<?= $doctor->id ?>" <?= ($params['doctorId'] == $doctor->id) ? ' selected ' : '' ?>><?= $doctor->name ?></option>
                <?
            } ?>
        </select>
    </div>
    <?
    }?>

</div>


<table border="1" class="t" style="width: 700px; font-family: 'Open Sans1', Tahoma; ">
    <?
    foreach ($listByTimes as $time=>$entries)
    {
//        vd($params['date'].' '.$time);
//        vd(date('Y-m-d H:i') );

        $zeroZero = false;
        if(strpos($time, ':00')!==false)
            $zeroZero = true;
        ?>
        <tr style="height: 50px; <?=($zeroZero ? 'border-top: 2px solid #000 !important; ' : '')?>" >
            <td style="width: 1px;  font-size: 15px; font-weight: normal;  " class="<?= ($params['date'].' '.$time) < date('Y-m-d H:i') ?'past':''?>"><?=$time?></td>
            <td style="width: 1px; "><button onclick="editOpts.time = '<?=$time?>'; edit();  return false; ">+</button></td>
            <td>
                <?
                if(count($entries))
                {
                    foreach ($entries as $entry)
                    {?>
                        <div class="entry">
                            <table class="entry-info-tbl" cellpadding="0" cellspacing="0" style="border-collapse: collapse; width: 100%;  ">
                                <tr>
                                    <td class="client" >
                                        <div class="service"><?=$entry->service->name?></div>
                                        <?=$entry->client->fio()?>
                                        <div class="phone"><?=$entry->client->phone?></div>
                                    </td>
    <!--                                <td class="service">-->
    <!--                                    --><?//=$entry->service->name?>
    <!--                                </td>-->
                                    <td>Врач: <?=$entry->doctor->name?></td>
                                    <td class="btns">
                                        <?
                                        if(!$ADMIN->isDoctor())
                                        {?>
                                        <nobr>
                                            <a href="#" onclick="edit(<?=$entry->id?>)">ред.</a>&nbsp;&nbsp;&nbsp;
                                            <a style="color: red; " href="#" onclick="del(<?=$entry->id?>)">удалить</a>
                                        </nobr>
                                        <?
                                        }?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    <?
                    }
				}
				else
                {?>
                    <span style="font-size: 1em; font-style: italic; color: #777; ">-записей нет-</span>
                <?
                }?>
            </td>
        </tr>
    <?
    }?>
</table>


