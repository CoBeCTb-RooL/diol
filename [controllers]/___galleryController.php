<?php





$id = intval($_PARAMS[0]);
if($id)
	$ACTION = 'album';
else
	$ACTION = 'albumsList';
	



	
	

class GalleryController extends MainController{

	
	function albumsList()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		
		$_GLOBALS['TITLE'] = Slonne::getTitle($_CONST['ГАЛЕРЕЯ']);
	
		$crumbs[] = '<a href="/'.LANG.'">'.$_CONST['ГЛАВНАЯ'].'</a>';
		$crumbs[] = $_CONST['ГАЛЕРЕЯ'];
		$MODEL['crumbs'] = $crumbs;
		
		$albums = Gallery::getChildren();
		$MODEL['albums'] = $albums;
		
		Core::renderView('gallery/albumsList.php', $MODEL);
	}
	
	
	
	
	function album($id)
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		
		$id = intval($_PARAMS[0]);
		$album = Gallery::get($id);
	
		$crumbs[] = '<a href="/'.LANG.'">'.$_CONST['ГЛАВНАЯ'].'</a>';
		$crumbs[] = '<a href="'.Entity2::moduleUrl(Gallery::ESSENCE).'">'.$_CONST['ГАЛЕРЕЯ'].'</a>';
		$crumbs[] = $album->attrs['name'];
		$MODEL['crumbs'] = $crumbs;	
		
		$_GLOBALS['TITLE'] = Slonne::getTitle($album->attrs['name'].' - '.$_CONST['ГАЛЕРЕЯ'].'');
		
		$MODEL['album'] = $album;
		$MODEL['photos'] = $album->attrs['pics'];
	
		
		$MODEL['crumbs'] = $crumbs;
		
		
		Core::renderView('gallery/album.php', $MODEL);
	}
	
	
	
}



?>