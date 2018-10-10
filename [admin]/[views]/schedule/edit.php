<?php
$item = $MODEL['item'];
$doctors = $MODEL['doctors'];
$editOpts = $MODEL['editOpts'];


if($item)
    $chosenDate = date('Y-m-d', strtotime($item->dt ));
elseif($editOpts['date'])
    $chosenDate = $editOpts['date'];
else $chosenDate = date('Y-m-d');


if($item)
	$chosenTime = $item->getTime();
elseif($editOpts['time'])
	$chosenTime = $editOpts['time'];



if($item)
	$chosenDoctorId = $item->doctorId;
elseif($editOpts['doctorId'])
    $chosenDoctorId = $editOpts['doctorId'];


?>


<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>


<h1><?=$item ? $item->fio() : 'Журнал'?><span class="title-gray"> : <?=$item ? 'Редактирование' : 'Добавление'?></span></h1>

<form id="schedule-edit-form" enctype="multipart/form-data" method="post" action="/<?=ADMIN_URL_SIGN?>/schedule/editSubmit" target="frame8" onsubmit="editSubmitStart(); " >
	<input type="hidden" name="clientId" value="" />
	<input type="hidden" name="doctorId" value="" />
	<input type="hidden" name="serviceId" value="" />
	<input type="hidden" name="scheduleEntryId" value="<?=$item->id?>" />



    <div class="field-wrapper">
        <span class="label" >Клиент<span class="required">*</span>: </span>
        <span class="value" >

            <div class="clients-search-form" style="border: 0px solid red; ">
                <table>
                    <tr>
                        <td>Поиск:</td>
                        <td><!--Фамилия: <br>--><input type="text" name="clientSurname" value="" onkeyup="Schedule.clientSearch()"  placeholder="фамилия"/></td>
                        <td><!--Имя: <br>--><input type="text" name="clientName" value="" onkeyup="Schedule.clientSearch()" placeholder="имя" /></td>
                        <td><!--Отчество: <br>--><input type="text" name="clientFathername" value="" onkeyup="Schedule.clientSearch()" placeholder="отчество" /></td>
                        <td><!--Телефон: <br>--><input type="text" name="clientPhone" value="" onkeyup="Schedule.clientSearch()" placeholder="телефон" /></td>
                    </tr>
                </table>

                <div class="clients-list">
                    <div class="loading" style="font-size: .8em; display: none; ">загрузка...</div>
                    <div class="inner" style="overflow: auto; max-height: 150px; "></div>
                </div>

            </div>

            <div class="chosenClientWrapper" style="display: none; ">
                <span class="inner"></span>
                <a href="#" class="changeClientBtn" style="font-size: .8em; display: ; " onclick="$('#schedule-edit-form input[name=clientId]').val(''); $('.clients-search-form').slideDown('fast'); $('.chosenClientWrapper').slideUp('fast');  return false; ">сменить</a>
            </div>


		</span>
        <div class="clear"></div>

    </div>


    <div class="field-wrapper">
        <span class="label" >Врач<span class="required">*</span>: </span>
        <span class="value" >
            <select name="doctorId">
                <option value="">-выберите специалиста-</option>
                <?
                foreach ($doctors as $d)
                {?>
                    <option value="<?=$d->id?>" <?=($d->id == $item->doctorId ? ' selected ' : '')?> ><?=$d->name?> <?=$d->speciality ? '('.$d->speciality.')' : '' ?></option>
                <?
                }?>
            </select>
		</span>
        <div class="clear"></div>
    </div>
	
    <div class="field-wrapper">
        <span class="label" >Услуга<span class="required">*</span>: </span>
        <span class="value" >
            <select name="serviceId">
                <option value="">-выберите-</option>
			    <?=Service::drawTreeSelect2($MODEL['services'], 0, $item->serviceId)?>
            </select>
            &nbsp;&nbsp;
            Цена<span class="required">*</span>: <input type="text" name="price" value="<?=$item->price?>"  />
		</span>
        <div class="clear"></div>
    </div>
    <div class="field-wrapper">
        <span class="label" >Дата<span class="required">*</span>: </span>
        <span class="value" >
			<input type="text" id="date" name="date" value="<?=$chosenDate?>" style="width: 60px; " />
            <img id="date-calendar-btn" src="/js/calendar/calendar.jpg" style="border:0px;">
            <script>
						Calendar.setup({
                            inputField     :    "date",      // id of the input field
                            ifFormat       :    "%Y-%m-%d",       // format of the input field
                            showsTime      :    false,            // will display a time selector
                            button         :    "date-calendar-btn",   // trigger for the calendar (button ID)
                            singleClick    :    true,           // double-click mode
                            step           :    1                // show all years in drop-down boxes (instead of every other year as default)
                        });
				</script>
            &nbsp;&nbsp;
            Время<span class="required">*</span>:

            <select name="time" >
                <option value="">-выберите-</option>
                <?
                $timeArr = ScheduleEntry::timeArr();
                foreach ($timeArr as $time)
                {?>
                    <option value="<?=$time?>" <?= $time==$chosenTime ? 'selected' : ''?> ><?=$time?></option>
                <?
                }?>
            </select>
		</span>
        <div class="clear"></div>
    </div>





    <div class="clear"></div>






    <p>
    <div class="clear"></div>
    <input type="submit" value="Сохранить" />
	<div class="loading" style="display: none; ">секунду...</div>
	
</form>



<iframe name="frame8" style="display: ; "></iframe>


<script>
    <?
    if($item)
    {?>
        Schedule.clientsSearchResult = <?=json_encode([$item->client])?>;
        Schedule.setClient(<?=$item->clientId?>)
    <?
    }?>
</script>





<!--found client tmpl-->
<div class="foundClientTmpl" style="display: none; ">
    <div>
        <a href="#" onclick="Schedule.setClient(_ID_)">_NAME_</a>
        <span style="font-size: .9em; ">(_PHONE_)</span>
    </div>
</div>
<!--/found client tmpl-->





