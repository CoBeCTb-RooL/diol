<?php
$item = $MODEL['item'];
$crumbs = $MODEL['crumbs'];


?>
	<!--крамбсы-->
	<? Core::renderPartial(SHARED_VIEWS_DIR.'/crumbs.php', $crumbs);?>
	<!--//крамбсы-->

<div class="news-el" >
	<h1 style="font-size: 29px;"><?=$item->attrs['name']?></h1>
	<span class="date"><?=Funx::mkDate($item->attrs['dt'])?></span>
	<span class="text">
		<img width="250" src="/upload/images/<?=$item->attrs['pic']?>">
		<p><?=$item->attrs['text']?></p>
		<div class="clear"></div>
	</span>
</div>