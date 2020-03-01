<?
$list = $MODEL['list'];
?>

<style>
    /*.isFuture{opacity: .6; }*/
</style>

<?if(count($list)):?>
    <table class="t">
        <?foreach ($list as $rem):?>
        <?
        $isFuture = mb_substr($rem->dt, 0, 10) > date('Y-m-d');
        ?>
            <tr class="item-<?=$rem->id?> <?=$isFuture? 'isFuture' : ''?>" ondblclick="Reminder.edit(<?=$rem->id?>)">
                <td width="1"><?=$rem->id?></td>
                <td width="1"></td>
                <td style="vertical-align: middle; ">
                    <?if($isFuture):?>
                    <span style="display: inline-block; font-size: 8px; background: #4b9eb8; padding: 2px 2px; color: #fff; border-radius: 3px;  ">будущее</span>
                    <?endif;?>
                    <?=htmlspecialchars($rem->comment)?>
                </td>
                <td style="font-size: .8em; "><?=mb_strtolower(Funx::mkDate($rem->dt))?></td>
                <td width="1"><a href="#" onclick="Reminder.edit(<?=$rem->id?>)" style="font-size: 11px; white-space: nowrap;">ред.</a></td>
                <td width="1"><a href="#" onclick="Reminder.delete(<?=$rem->id?>)" style="color: red; font-size: 11px; white-space: nowrap;">&times; удалить</a></td>
            </tr>
        <?endforeach;?>
    </table>
<?else:?>
    -нет-
<?endif?>
