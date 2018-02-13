<?
/*
IMAGE RESIZE by Mans
*/
ini_set('error_reporting', E_ERROR);
ini_set('display_errors', 1);
//$files_folder = $_SERVER['DOCUMENT_ROOT'].'/upload/images/';
$files_folder = $_SERVER['DOCUMENT_ROOT'].'';
$width = 100;
$height = 100;
$src_x = 0;
$src_y = 0;
$ratio = true;
$crop = false;

function ratio_image($properties, $method = 'width'){
	$ret_val = array();
	$ret_val['width'] = $properties['width'];
	$ret_val['height'] = $properties['height'];
	if ($properties['src_width'] > $properties['src_height']) $ratio = $properties['src_width'] / $properties['src_height'];
	else $ratio = $properties['src_height'] / $properties['src_width'];
	switch ($method){
		case 'width':
			if ($properties['src_width'] < $properties['src_height']) $ret_val['height'] = $ret_val['width'] * $ratio;
			else $ret_val['height'] = $ret_val['width'] / $ratio;
		break;
		case 'height':
			if ($properties['src_width'] > $properties['src_height']) $ret_val['width'] = $ret_val['height'] * $ratio;
			else $ret_val['width'] = $ret_val['height'] / $ratio;
		break;
	}
	
	return $ret_val;
}

if (empty($_GET['file'])) exit('file not found!');

if (isset($_GET['width']) && !empty($_GET['width'])) $width = intval($_GET['width']);
if (isset($_GET['height']) && !empty($_GET['height'])) $height = intval($_GET['height']);
if (isset($_GET['noratio'])) $ratio = false;
if (isset($_GET['crop'])) $crop = true;
if (isset($_GET['php'])) $php = true;
$file = $_GET['file'];

if (!file_exists($files_folder.$file)) exit('file not found');
list($src_width, $src_height) = getimagesize($files_folder.$file);
if ($ratio){
	if ($_GET['width'] && !$_GET['height']) $img_size = ratio_image(array('width'=>$width, 'height'=>$height, 'src_width'=>$src_width, 'src_height'=>$src_height), 'width');
	if (!$_GET['width'] && $_GET['height']) $img_size = ratio_image(array('width'=>$width, 'height'=>$height, 'src_width'=>$src_width, 'src_height'=>$src_height), 'height');
	if ($_GET['width'] && $_GET['height']) $img_size = ratio_image(array('width'=>$width, 'height'=>$height, 'src_width'=>$src_width, 'src_height'=>$src_height));
}
if($crop == 'resize'){
	if ($width > $height){
		$img_size = ratio_image(array('width'=>$width, 'height'=>$height, 'src_width'=>$src_width, 'src_height'=>$src_height));
		if($img_size['height'] < $height) $img_size = ratio_image(array('width'=>$width, 'height'=>$height, 'src_width'=>$src_width, 'src_height'=>$src_height), 'height');
	}else{
		$img_size = ratio_image(array('width'=>$width, 'height'=>$height, 'src_width'=>$src_width, 'src_height'=>$src_height), 'height');
		if($img_size['width'] < $width) $img_size = ratio_image(array('width'=>$width, 'height'=>$height, 'src_width'=>$src_width, 'src_height'=>$src_height), 'width');
	}
}
if (!empty($img_size)){
	//vd($img_size);
	$width = intval($img_size['width']);
	$height = intval($img_size['height']);
}
if(isset($_GET['debug'])){
	echo 'BEFORE<br>';
	vd($img_size);
	echo 'width: '.$width.'<br>';
	echo 'height: '.$height.'<br>';
	echo 'src_width: '.$src_width.'<br>';
	echo 'src_height: '.$src_height;
}
if ($crop){
	$crop = $_GET['crop'];
	switch($crop){
		case 'tl':
			$src_x = 0;
			$src_y = 0;
		break;
		case 'tc':
			$src_x = $src_width / 2 - $width / 2;
			$src_y = 0;
		break;
		case 'tr':
			$src_x = $src_width - $width;
			$src_y = 0;
		break;
		case 'ml':
			$src_x = 0;
			$src_y = $src_height / 2 - $height / 2;
		break;
		case 'mc':
			$src_x = $src_width / 2 - $width / 2;
			$src_y = $src_height / 2 - $height / 2;
		break;
		case 'mr':
			$src_x = $src_width - $width;
			$src_y = $src_height / 2 - $height / 2;
		break;
		case 'bl':
			$src_x = 0;
			$src_y = $src_height - $height;
		break;
		case 'bc':
			$src_x = $src_width / 2 - $width / 2;
			$src_y = $src_height - $height;
		break;
		case 'br':
			$src_x = $src_width - $width;
			$src_y = $src_height - $height;
		break;
		case 'resize':
		
		break;
		default:
			$src_x = $src_width / 2 - $width / 2;
			$src_y = $src_height / 2 - $height / 2;
		break;
	}
	if($crop != 'resize'){
		$src_width = $width;
		$src_height = $height;
	}
}

if (eregi('\.(jpeg|jpg)$', $file)) $src_img = imagecreatefromjpeg($files_folder.$file);
if (eregi('\.(gif)$', $file)) $src_img = imagecreatefromgif($files_folder.$file);
if (eregi('\.(png)', $file)) $src_img = imagecreatefrompng($files_folder.$file);
if (eregi('\.(bmp)', $file)) $src_img = imagecreatefromwbmp($files_folder.$file);

header ("content-type: image/jpeg");
if($crop == 'resize'){
	$tmp_image = imagecreatetruecolor($width, $height);
	imagecopyresampled($tmp_image, $src_img, 0, 0, $src_x, $src_y, $width, $height, $src_width, $src_height);
	
	$width = $_GET['width'];
	$height = $_GET['height'];
	
	if($src_width < $src_height){//vertical
		if($width < $_GET['width']){
		
		}else{
			$src_width = $_GET['width'];
			$src_height = $_GET['height'];
		}
	}else{//horisontal
		if($width < $_GET['width']){
		
		}else{
			//$height = $_GET['height'];
			//$src_width = $_GET['width'];
			$src_width = $_GET['width'];
			$src_height = $_GET['height'];
		}
	}
}
$image = imagecreatetruecolor($width, $height);
if(isset($_GET['debug'])){
	echo '<br><br>AFTER<br>';
	//vd($img_size);
	echo 'width: '.$width.'<br>';
	echo 'height: '.$height.'<br>';
	echo 'src_width: '.$src_width.'<br>';
	echo 'src_height: '.$src_height.'<br>';
	echo $src_x.' '.$src_y.' '.$width.' '.$height.' '.$src_width.' '.$src_height;
}
imagecopyresampled($image, ($tmp_image ? $tmp_image : $src_img), 0, 0, $src_x, $src_y, $width, $height, $src_width, $src_height);

if(isset($_REQUEST['watermark']) && $_REQUEST['watermark'] != ''){
	$file = $_SERVER['DOCUMENT_ROOT'].'/upload/images/'.$_REQUEST['watermark'];
	$tmp=getimagesize($file);
	$W = $tmp[0];
	$H = $tmp[1];
	$type = $tmp[2];
	
	switch($type){
		case 1: $tmp = imagecreatefromgif ($file); break;
		case 2: $tmp = imagecreatefromjpeg ($file); break;
		case 3: $tmp = imagecreatefrompng ($file); break;
	}
	$watermark = imagecreatetruecolor($W, $H);
	imagealphablending($watermark, false);
	imagecopyresampled($watermark, $tmp, 0, 0, 0, 0, $W, $H, $W, $H);
	imagesavealpha($watermark, true);
	imagecopyresampled($image, $watermark, 0, 0, 0, 0, $W, $H, $W, $H);
}


if (eregi('\.(jpeg|jpg)$', $file)) imagejpeg($image, null, 100);
if (eregi('\.(gif)$', $file)) imagegif($image, null, 100);
if (eregi('\.(png)$', $file)) imagepng($image, null, 100);
if (eregi('\.(bmp)$', $file)) imagewbmp($image, null, 100);

imagedestroy($image);
?>