<?php
//header ("content-type: image/jpeg");
$startTimer = microtime(1);
error_reporting(0);
require_once('../config.php');
require_once('../'.CLASSES_DIR.'/ImageResize.php');
require_once('../'.CLASSES_DIR.'/Funx.php');

function vd($a)
{
	echo '<pre>';
	var_dump($a);
	echo '</pre>';
}

//vd($_GET);

$img = $_GET['img'];
$method = $_GET['method'];

$w = $_GET['width'];
$h= $_GET['height'];
$coef = $_GET['coef'];


# 	тянемся сперва в кэш
if($w && $h)
	$cacheInnerDirName = 'crop'.$w.'x'.$h;
elseif($w)
	$cacheInnerDirName = 'width'.$w;
elseif($h)
	$cacheInnerDirName = 'height'.$h;

# 	подпапка в папке cache - для разного (не типизированного как например как adv или news)
$sectionDir = '_common/';

# 	достаём имя файла
$tmp2 = explode('/', $img);

$fileName = $tmp2[count($tmp2)-1];
if(count($tmp2) >= 5)
{
	# 	подпапка с ид юзера(пока так)
	$subdir = $tmp2[4].'/';

	# 	подпапка сущности (типа adv или news в папке cache)
	$sectionDir = $tmp2[3].'/';
}
//vd($img);
//vd($tmp2);

$finalDirPath = ROOT.'/'.UPLOAD_IMAGES_REL_DIR.PIC_CACHE_DIR_NAME.'/'.$sectionDir.$cacheInnerDirName;
$finalDirPath .= '/'.Funx::getSubdirsByFile($fileName);
//vd($finalDirPath);
//vd($finalDirPath.'/'.$fileName);
//vd(file_exists($finalDirPath.'/'.$fileName));
//vd($fileName);

# 	момент истины
if(file_exists($finalDirPath.'/'.$fileName) && 1) 	# забираем из кэша
{
	//echo "!";
    //vd($finalDirPath.'/'.$fileName);
	$image = new ImageResize($finalDirPath.'/'.$fileName);
	$image->output();

}
else
{
	$image = new ImageResize($img);
	$image->quality_jpg = 100;
	$image->quality_png = 1;

	$cacheInnerDirName = '';

	if($w && $h)
		$image->crop($w, $h);
	elseif($w)
		$image->resizeToWidth($w);
	elseif($h)
		$image->resizeToHeight($h);

	# 	создаём подпапку кэша, если надо
	mkdir($finalDirPath, 0777, true );

	/*$image->scale(50);

	$image->crop(400, 400);*/
    $image->save($finalDirPath.'/'.$fileName);
	$image->output();

}

//$image->scale(70);
//echo microtime(1)-$startTimer;
?>