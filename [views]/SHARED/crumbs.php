<?php
$crumbs = $MODEL; 
//vd($crumbs);
?>


<?php 
if(count($crumbs))
{?>
	<div class="crumbs">
		<span class="item"><img src="/img/crump-pic.gif" height="17" style="vertical-align: top; padding: 0 2px 0 0 ; " alt="" /><?=join('</span><span class="item">', $crumbs)?></span>
	</div>
<?php 
}?>