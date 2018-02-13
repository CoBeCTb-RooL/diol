<?php
$pages = $MODEL['pages']; 
?>

<div class="pages-list">
<?php
foreach($pages as $key=>$val)
{?>
	<h2><a href="<?=$val->url()?>"><?=$val->attrs['name']?></a></h2>
<?php 	
} 
?>
</div>