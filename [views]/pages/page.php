<?php
$p = $MODEL['p'];
$crumbs = $MODEL['crumbs'];


?>

<!--крамбсы-->
<? Core::renderPartial(SHARED_VIEWS_DIR.'/crumbs.php', $crumbs);?>
<!--//крамбсы-->



<?php
if($p->attrs)
{?>
	<div class="page">
		<h1><?=$p->attrs['name']?></h1>
		<span class="text"><?=$p->attrs['descr']?></span>
	</div>
<?php 	
} 
else
{?>
	Раздел не найден.
<?php 	
}
?>