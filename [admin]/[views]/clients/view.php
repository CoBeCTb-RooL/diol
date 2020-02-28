<?php
$item = $MODEL['item'];
?>


<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>

<h1><?=$item->fio() ?> <sup style="font-size: 10px; ">id: <?=$item->id?></sup> <span style="font-size: 12px; text-shadow: none; ">(создан: <?=Funx::mkDate($item->createdAt, 'with_time')?>)</span></h1>

<?
//vd($item);
?>



    <div class="field-wrapper">
        <span class="label" >Телефон: </span>
        <span class="value" ><?=$item->phone?> </span>
        <div class="clear"></div>
    </div>


    <div class="field-wrapper">
        <span class="label" >Адрес: </span>
        <span class="value" ><?=$item->address ? $item->address : '-не указан-'?> </span>
        <div class="clear"></div>
    </div>




    <div class="clear"></div>

    <hr>
    <h3>Материалы: </h3>
    <?
    if(count($item->media))
    {?>

        <?
        foreach ($item->media as $m)
        {?>
            <div class="pic-wrap" id="media-<?=$m->id?>" >
                <a href="<?=$m->src()?>" onclick="return hs.expand(this)" class="highslide ">
                    <img src="<?=Media::img($m->path.'&height=100')?>" >
                </a>
                <div><?=$m->title?></div>
            </div>
        <?
        }?>
        <div class="clear"></div>
    <?
    }
    else
    {?>
        &nbsp;&nbsp;&nbsp;&nbsp;материалов нет.
    <?
    }?>





<div class="clear"></div>

<div>
    <hr>
    <h3>Напоминания: </h3>
    <div class="reminder-list"></div>




    <button type="button" style="margin: 15px 0 ; " onclick="Reminder.initForm({});  ">+ Новое напоминание</button>




    <!--reminder form-->
    <div class="reminder-form" style="border: 0px solid black; display: none; ">
        <div class="form">
            <input type="hidden" name="id" value="">
            <input type="hidden" name="clientId" value="<?=$item->id?>">

            <div class="field-wrapper">
                <span class="label" >Дата<span class="required">*</span>: </span>
                <span class="value" >
                    <input type="text" id="date" name="date" value="" style="width: 100px; " />
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
                    <!--                Время<span class="required">*</span>:-->
                    <!---->
                    <!--                <select name="time" >-->
                    <!--                    <option value="">-выберите-</option>-->
                    <!--                    --><?//
                    //                    $timeArr = ScheduleEntry::timeArr();
                    //                    foreach ($timeArr as $time)
                    //                    {?>
    <!--                        <option value="--><?//=$time?><!--" --><?//= $time==$chosenTime ? 'selected' : ''?><!-- >--><?//=$time?><!--</option>-->
                    <!--                        --><?//
                    //                    }?>
    <!--                </select>-->
                </span>
                <div class="clear"></div>
            </div>

            <div class="field-wrapper">
                <span class="label" >Текст<span class="required">*</span>: </span>
                <span class="value" >
                    <textarea name="comment" style="width: 300px; "></textarea>
                </span>
                <div class="clear"></div>
            </div>

            <div class="field-wrapper" style="font-size: 12px; ">
                <span class="label" ></span>
                <span class="value" ><button type="button" onclick="Reminder.submit()" >Сохранить</button></span>
                <span class="loading" style="display: none; ">Секунду...</span>

                <div class="clear"></div>
            </div>
        </div>

        <div class="info" style="margin: 15px 0 0 0; "></div>

    </div>


</div>  



<div style="height: 30px; "></div>








<iframe name="frame8" style="display: none; "></iframe>


<script>
    $(document).ready(function(){
        Reminder.opts.clientId = '<?=$item->id?>'
        Reminder.list()
    })
</script>

<script>

</script>

