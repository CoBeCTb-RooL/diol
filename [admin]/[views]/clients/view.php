<?php
$item = $MODEL['item'];
?>


<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>

<h1><?=$item->fio() ?> <span style="font-size: 12px; text-shadow: none; ">(Создан: <?=Funx::mkDate($item->createdAt, 'with_time')?>)</span></h1>

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


    <hr>









<iframe name="frame8" style="display: none; "></iframe>





