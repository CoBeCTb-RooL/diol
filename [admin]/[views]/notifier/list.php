<?php
$list = $MODEL['list'];

?>


<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>

<?
?>



<?foreach ($list as $item):?>
<div class="item item-<?=$item->id?> " id="notifier-item-<?=$item->id?>">
    <span class="id">#<?=$item->id?></span>
    <span class="date"><?=Funx::mkDate($item->dt)?></span>
    <div class="client"><a href="#"><?=$item->client->fio()?></a></div>
    <div class="comment"><?=$item->comment?></div>
    <div class="btns-wrapper">
        <a class="btn" href="#" onclick="Notifier.setDone(<?=$item->id?>); return false; ">готово</a>
        <a class="btn" href="#" onclick="$(this).parent().find('.postponeBtns').slideToggle('fast'); return false; ">отложить</a>

        <div class="postponeBtns" style="display: none; ">
            <div style="margin-top: 5px; ">
                <a href="#" onclick="Notifier.postpone(<?=$item->id?>, 'через 2 часа'); return false; ">на 2 часа</a>
                <a href="#" onclick="Notifier.postpone(<?=$item->id?>, 'через 3 месяца'); return false; ">на 3 месяца</a>
                <a href="#" onclick="Notifier.postpone(<?=$item->id?>, 'через полгода'); return false; ">на полгода</a>
                <a href="#" onclick="Notifier.postpone(<?=$item->id?>, 'через год'); return false; ">на год</a>
            </div>
        </div>
    </div>
</div>
<?endforeach;?>
