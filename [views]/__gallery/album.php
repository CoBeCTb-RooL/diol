<?php
$album = $MODEL['album'];
$photos = $MODEL['photos']; 

$crumbs = $MODEL['crumbs'];
?>


<!--крамбсы-->
<? Core::renderPartial(SHARED_VIEWS_DIR.'/crumbs.php', $crumbs);?>
<!--//крамбсы-->

<div class="gallery-photos-wrapper">
	<h1><?=$album->attrs['name']?></h1>
	<?
	foreach($photos as $key=>$m)
	{?>
		<div class="item">
			<a href="/upload/images/<?=$m->path?>" onclick="return hs.expand(this)" class="highslide ">
				<img src="<?=Media::img($m->path.'&width=200')?>"  />
				<span><?=$m->title[LANG]?></span>				
			</a>
			
		</div>
	<?php 
	}
	?>
</div>