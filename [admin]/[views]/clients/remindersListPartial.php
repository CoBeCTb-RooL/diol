<?
$list = $MODEL['list'];
?>

<?if(count($list)):?>
    <table class="t">
        <?foreach ($list as $rem):?>
            <tr class="item-<?=$rem->id?>" ondblclick="Reminder.edit(<?=$rem->id?>)">
                <td width="1"><?=$rem->id?></td>
                <td width="1"></td>
                <td><?=htmlspecialchars($rem->comment)?></td>
                <td><?=$rem->dt?></td>
                <td width="1"><a href="#" onclick="Reminder.edit(<?=$rem->id?>)" style="font-size: 11px; white-space: nowrap;">ред.</a></td>
                <td width="1"><a href="#" onclick="Reminder.delete(<?=$rem->id?>)" style="color: red; font-size: 11px; white-space: nowrap;">&times; удалить</a></td>
            </tr>
        <?endforeach;?>
    </table>
<?else:?>
    -нет-
<?endif?>
