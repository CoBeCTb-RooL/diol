<?php
$item = $MODEL['item']; 


?>



<?php 
if($item)
{?>
	<i class="fa fa-phone" style="width: 16px; text-align: center; "></i> <?=$item->phone ? $item->phone : '--'?>
	<br/><b><i class="fa fa-envelope-o" style="width: 16px;"></i> </b><?=$item->email ? '<a href="mailto:'.$item->email.'">'.$item->email.'</a>': '-не указан-'?>
<?php 	
}
else
{?>
	Ошибка, объявление не найдено. 
<?php 	
}?>