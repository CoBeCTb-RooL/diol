<?php

$timers = $MODEL['timers'];
/*$tmp = null;
foreach($timers as $key=>$t)
	$tmp[$key] = Slonne::cast('Timer', $t);
	$timers = $tmp;
	unset($tmp);*/
//vd($_SERVER);
?>


<style>
#timers .col{display: table-cell; text-align: left; vertical-align: top;}
#timers .col.num{min-width: 27px; padding: 0 7px 0 0 ;  }
#timers .col.time{font-weight: bold; padding: 0 0 0 1px; vertical-align: top; width: 80px !important;  }
#timers .col.memory{padding: 0 0 0 1px; vertical-align: top; width: 80px !important;  }
#timers .col.time.alert{font-weight: bold; color: red; }
#timers .col.type{ vertical-align: top; width: 70px !important;  }
#timers .col.title{ vertical-align: top; }

.timer-type-<?=Timer::TYPE_QUERY?> .title{}
.timer-type-<?=Timer::TYPE_CUSTOM?> .title{color: orange; font-weight: bold;  }
.timer-type-<?=Timer::TYPE_CUSTOM?> .type{color: orange; }

#timers .itogo{color: #fff; }
#timers .itogo .label{display: inline-block; width: 170px; text-align: right;padding: 0 6px 0 0 ;  }
#timers .itogo .value{display: inline-block; font-size: 13px; font-weight: bold; }
</style>




	<!-- таймеры -->
	<div class="admin-panel-info " id="timers" style="display: non1e; ">
		<?
		foreach($timers as $key=>$t)
		{
			if($t->type == Timer::TYPE_QUERY)
				$queriesTimeSum += $t->time;
			elseif($t->type == Timer::TYPE_GLOBAL_TIMER)
			{
				$globalTimer = $t;
				continue; 
			}
			
			?>
			<div class="item timer-type-<?=$t->type?>">
				<span class="col num"><?=$key+1?>) </span>
				<span class="col time <?=$t->time > 1 ? "alert" : ""?>"><?=$t->time?> с.</span>
				<span class="col memory"> &asymp;<?=Funx::getFileSizeOkon($t->memory)?> </span>
				<span class="col type">| <?=$t->type?> </span>
				<span class="col title">| &nbsp;&nbsp;&nbsp;<?=$t->title?></span> 
			</div>
		<?php 	
		}?>
		
			----------------------------------------------------------------------------------------------------------------------------------------------<br>
			
			<div class="item ">
				<span class="col num"> </span>
				<span class="col time <?=$t->time > 1 ? "alert" : ""?>"><?=$queriesTimeSum?> с.</span>
				<span class="col title">СУММАРНОЕ ВРЕМЯ ЗАПРОСОВ</span> 
			</div>
			----------------------------------------------------------------------------------------------------------------------------------------------<br>
			
		<div class="itogo">
			<div><span class="label">Время загрузки страницы: </span><span class="value" style=""><?=$globalTimer->time?> c.</span></div>
			<div><span class="label">Память: </span><span class="value" style="font-size: 13px; ">&asymp;<?=Funx::getFileSizeOkon($t->memory)?></span></div> 
		</div>
		
	</div>
	<!-- //таймеры -->
