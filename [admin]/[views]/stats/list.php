<?php
$list = $MODEL['list'];
$params = $MODEL['params'];
$i=0;



$doctors = $MODEL['doctors'];
$services= $MODEL['services'];



?>


<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>





<div class="filters">
    <div class="section user-id">
        <h1>Дата:</h1>
        &nbsp;от <input type="text" id="dateFrom" value="<?=$params['dateFrom']?>"  onchange="  list1();  " style="width: 87px;" />
        <img id="dateFrom-calendar-btn" src="/js/calendar/calendar.jpg" style="border:0px;">
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
        до
        &nbsp;<input type="text" id="dateTo" value="<?=$params['dateTo']?>"  onchange="  list1(); " style="width: 87px;" />
        <img id="dateTo-calendar-btn" src="/js/calendar/calendar.jpg" style="border:0px;">
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

    </div>


    <div class="section user-id">
        <h1>Врач:</h1>
        <select id="doctorId"  onchange=" list1(); ">
            <option value="">-все-</option>
            <?foreach ($doctors as $doctor) :?>
                <option value="<?= $doctor->id ?>" <?= ($params['doctorId'] == $doctor->id) ? ' selected ' : '' ?>><?= $doctor->name ?></option>
            <?endforeach;?>
        </select>
    </div>

    <div class="section user-id">
        <h1>Услуга:</h1>
        <select id="serviceId" onchange="list1(); ">
            <option value="">-все-</option>
            <?=Service::drawTreeSelect3($services, 0, $params['serviceId1'])?>
        </select>
    </div>


    <button type="button" onclick="list1()">Искать</button>

</div>






<?if(count($list)): ?>
<table class="t">
    <tr>
        <th>id</th>
        <th>Услуга</th>
        <th>Врач</th>
        <th>Клиент</th>
        <th>Стоимость</th>
    </tr>
    <?foreach ($list as $item):?>
    <?
    $total += $item->price;
    ?>
        <tr>
            <td><?=$item->id?></td>
            <td><?=$item->service->name?></td>
            <td><?=$item->doctor->name?></td>
            <td><?=$item->client->name?></td>
            <td><?=number_format($item->price, 0, '', ' ')?> тг</td>
        </tr>
    <?endforeach;?>
    <tr>
        <td colspan="4" style="text-align: right; ">Итого</td>
        <td style="font-weight: bold; font-size: 1.3em; "><?=number_format($total, 0, '', ' ')?> тг</td>
    </tr>
</table>
<?else:?>
    Ничего не найдено.
<?endif;?>
