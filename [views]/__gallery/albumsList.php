<?php
$albums = $MODEL['albums']; 
$crumbs = $MODEL['crumbs'];

?>

<!--крамбсы-->
<? Core::renderPartial(SHARED_VIEWS_DIR.'/crumbs.php', $crumbs);?>
<!--//крамбсы-->

<div class="gallery-albums-list">
	<h1><?=$_CONST['АЛЬБОМЫ']?></h1>
<?php
foreach($albums as $key=>$album)
{?>
	<div class="item">
		<a href="<?=$album->url()?>">
			<img src="<?=Media::img($album->attrs['pics'][0]->path.'&width=200')?>"  />
			<?=$album->attrs['name']?>
		</a>
	</div>

<?php 	
} 
?>
	<div class="clear"></div>
</div>
