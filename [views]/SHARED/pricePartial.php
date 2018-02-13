<?php
$item = $MODEL['item'];
?>

<div class="price-label">
	<fieldset >
		<legend>Цена:</legend>
		<?php 
		if($item->price > 0)
		{?>
			<div>
				<span class="value"><?=Funx::formatPrice($item->price)?> <?=$item->currency->sign?></span> 
				<span class="volume"><?=$item->productVolumeUnit->name?></span>
			</div>
		<?php 
		}
		else
		{?>
			<div class="value">-не указана-</div>
		<?php 	
		}?>	
	</fieldset>
</div>