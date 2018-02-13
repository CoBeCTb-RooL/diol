<?php
//vd($MODEL);
$list = $MODEL['list'];
?>


<style>
.t2 { border-collapse: collapse; }
.t2 td{vertical-align: top; padding: 9px 17px; }
.name{font-weight: bold; font-size: 15px; }
.text{font-size: 11px; }

.num{font-size: 10px; padding: 9px 3px !important; text-align: center; }
.date{font-weight: normal; font-size: 10px; }
.result{border: 1px solid #000; font-weight: normal; font-size: 19px; text-align: center;  }
.result-<?=ToolResult::OK?> .result{color: <?=ToolResult::code(ToolResult::OK)->color?>; }
.result-<?=ToolResult::FAILED?> .result{color: <?=ToolResult::code(ToolResult::FAILED)->color?>; }
.result-<?=ToolResult::ERRORS?> .result{color: <?=ToolResult::code(ToolResult::ERRORS)->color?>; }
.execType{font-weight: bold; }

tr.gray{font-size: 10px; background: #ececec;  }
tr.gray td{padding: 2px 4px ;}
tr.transparent, tr.transparent td{ padding: 0; border: 0px solid red; height: 3px; background: #bbb;  }
</style>



<h1><i class="fa fa-bar-chart"></i> Отчёты по тулзам</h1>


<h3>Отчёт за <?=$MODEL['dateStart']?> <?=($MODEL['dateStart'] == date('Y-m-d') ? '<span style="color: #bbb; ">(сегодня)</span>' : '')?></h3>

<form action="" style="margin: 0 0 17px 0; ">
	<input type="text" name="dateStart" id="dateStart" value="<?=$MODEL['dateStart']?>"  style="width:70px"> <img id="dateStart-calendar-btn" src="/js/calendar/calendar.jpg" style="border:0px; vertical-align: middle; "> 
	<script>
						Calendar.setup({
						    inputField     :    "dateStart",      // id of the input field
						    ifFormat       :    "%Y-%m-%d",       // format of the input field
						    showsTime      :    false,            // will display a time selector
						    button         :    "dateStart-calendar-btn",   // trigger for the calendar (button ID)
						    singleClick    :    true,           // double-click mode
						    step           :    1                // show all years in drop-down boxes (instead of every other year as default)
						});
				</script>
	<input type="submit" value="смотреть" />
	
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="button" value="<< предыдущий" onclick="location.href='?dateStart=<?=$MODEL['prevDate']?>'" />
	<input type="button" value="Сегодня" onclick="location.href='?'" />
	<input type="button" value="следующий >>" onclick="location.href='?dateStart=<?=$MODEL['nextDate']?>'" />
	&nbsp;&nbsp;&nbsp;&nbsp;
</form>



<hr />
Тул: 
<select onchange="location.href='?dateStart=<?=$MODEL['dateStart']?>&toolType='+(this.value)" >
	<option value="">-все-</option>
	<?php 
	foreach(ToolType::$items as $item)
	{?>
	<option value="<?=$item->code?>" <?=$item->code==$MODEL['toolType']->code? ' selected="selected" ':''?> ><?=$item->name?> (<span style="font-weight: bold; "><?=$item->code?></span>)</option>
	<?php 	
	}?>
</select>

<?php 
//vd(ToolType::$items);?>
<hr />



<?php 
if(date('Y-m-d') < $MODEL['dateStart'])
{?>
	<h1>это будущее! </h1>
<?php 	
}?>



<?php 
if($list)
{?>
	<table border="1" class="t2">
		<tr>
			<th>#</th>
			<th>Дата</th>
			<th>Скрипт</th>
			<th>Результат</th>
			<th>Время</th>
			<th>Тип запуска</th>
			
			<th>Комментарий</th>
		</tr>
		<?php 
		foreach($list as $key=>$item)
		{?>
			<tr class="transparent"><td colspan="7"></td></tr>
			<tr class="result-<?=$item->result->code?>">
				<td class="num"><?=$key+1?>. </td>
				<td class="date"><?=$item->dateStart?></td>
				<td class="name">
					<?=$item->toolType->name?>
					<div style="font-weight: normal; font-size: 11px; padding: 0 0 0 15px;  "><?=$item->toolType->code?></div>
				</td>
				<td class="result"><?=$item->result->code?></td>
				<td style="text-align: center; ">
					<?php 
					$dtStart = new Datetime($item->dateStart);
					$dtEnd = new Datetime($item->dateEnd);
					//vd($dtEnd->getTimestamp());
					$timeStr = $dtEnd->getTimestamp() ? '&asymp;'.($dtEnd->getTimestamp() - $dtStart->getTimestamp()).'с.' : ''; 
					echo $timeStr ? $timeStr : '--'; ?>
				</td>
				<td class="execType" style="text-align: center; "><?=$item->execType->code?></td>
				
				<td class="text"><?=$item->text?></td>
				
			</tr>
			<tr class="gray">
				<td colspan="7">
					<?=$item->url?>
				</td>
			</tr>
			
		<?php 	
		}?>
	</table>
<?php 	
}
else 
{?>
	Отчётов нет.
<?php 	
}?>