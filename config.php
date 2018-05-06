<?
if($_SERVER['SERVER_ADDR'] == '127.0.0.1')
{
	define('DB_NAME', 		'diol');
	define('DB_USER', 		'root');
	define('DB_PASSWORD', 	'');
	define('DB_HOST', 		'127.0.0.1');
}
else
{
	/*define('DB_NAME', 		'v_21838_ostatki_db');
	define('DB_USER', 		'v_21838_ost_db_u');
	define('DB_PASSWORD', 	'7pGeXgwnqHcXzGZNAYFrLQmK');
	define('DB_HOST', 		'localhost');*/
    define('DB_NAME', 		'p-735sd1_osdstat11ki');
    define('DB_USER', 		'ost_u111ssder');
    define('DB_PASSWORD', 	'Olxf596sd?Ftv111h68#5');
    define('DB_HOST', 		'localhost');

}



$_CONFIG['ZAGLUSHKA'] 		= false;
$_CONFIG['ZAGLUSHKA_INDEX'] = true;




#	лэйаут по умолчанию
$_CONFIG['DEFAULT_LAYOUT'] = 'mainLayout2.php';

#	лэйаут админки по умолчанию
$_CONFIG['DEFAULT_ADMIN_LAYOUT'] = 'adminLayout.php';

#	дефолтовые ящики, на которые будут уходить все фидбеки, формы и тд
$_CONFIG['DEFAULT_DELIVERY_EMAILS'] = array(
										'A@mail.ru', 
										'tsop.tya@gmail.com'
									);

/*
$_CONFIG['LANGS']=array(
	'ru'=>array('title'=>'Русская', 'postfix'=>'', 'siteTitle'=>'Rus',  ),
//	'en'=>array('title'=>'Engish',  'postfix'=>'_en', 'siteTitle'=>'Eng', ),
//	'kz'=>array('title'=>'Қазақ',  'postfix'=>'_kz', 'siteTitle'=>'Каз', ),
//	'tur'=>array('title'=>'Türk',  'postfix'=>'_tur', 'siteTitle'=>'Tur', ),

);
*/






define('CONTROLLERS_DIR', 	'[controllers]');
define('VIEWS_DIR', 		'[views]');
define('MODELS_DIR', 		'[models]');
define('CLASSES_DIR', 		'[classes]');


define('SHARED_VIEWS_DIR', 	'SHARED');
define('LAYOUTS_DIR', 		'[layouts]');



define('ABS_PATH_TO_RESIZER_SCRIPT', 		'/resize.php');
define('ABS_PATH_TO_RESIZER_SCRIPT_NEW', 	'/include/resize.slonne.php');

#	корень (ну там если придётся сделать псевдо-относительный путь)            
define('ROOT', $_SERVER['DOCUMENT_ROOT']);

#	относительный путь к папке со всеми инклудами
define('INCLUDE_DIR', 					'include');
define('GLOBAL_VARS_SCRIPT_FILE_PATH', 	'include/globalVars.php');
define('DEFAULT_STARTUP_FILE_PATH', 	'startup.php');

#	Папки с медиа
define('UPLOAD_IMAGES_REL_DIR', 'upload/images/');

#	Значение 1-го кусочка урла, говорящего что это админка
define('ADMIN_URL_SIGN', 'admin');

#	папка - админка
define('ADMIN_DIR', '[admin]');

#	разделитель для параметров и значений в гете (метод Slonne::getParams )
define('PARAMS_INNER_SEPARATOR', '_');

# 	название папки с кэшем картинок
define('PIC_CACHE_DIR_NAME', '_cache');


define('ROBOT_EMAIL', 		'robot@ostatki.kz');

define('DOMAIN', 			'ostatki.kz');
define('DOMAIN_FIRST_CAPITAL',	'Ostatki.kz');
define('DOMAIN_CAPITAL', 	'OSTATKI.KZ');





#######################################
###### 	Конфиги обьявлений	###########
#######################################

# 	картинка NO-PHOTO
define('NO_PHOTO_REL_PATH', '/img/no-photo.jpg');

# 	ЗАГРУЖАЕМЫЕ КАРТИНКИ
# 	макс. размеры картинки
define('MAX_PIC_WIDTH', '2300');
define('MAX_PIC_HEIGHT', '1500');





# 	ВАЛЮТЫ
require_once(MODELS_DIR.'/Common/Currency.php');

$_CONFIG['currencies'] = array(
	Currency::code(Currency::KZT), 
//	Currency::code(Currency::USD),
);



#	ЯЗЫК ПО УМОЛЧАНИЮ
require_once(CLASSES_DIR.'/Lang.php');
$_CONFIG['default_lang'] = Lang::code(Lang::RU);
$_CONFIG['langs'] = array(
		Lang::RU => Lang::code(Lang::RU),
//		Lang::EN=> Lang::code(Lang::EN),
//		Lang::KZ => Lang::code(Lang::KZ),
);


#############################
####   кэши информации   ####
#############################
define('INFO_CACHE_DIR_PATH', 'include/content/infoCache');
define('INFO_CACHE_CITIES_FILE', 'cities.cache');





?>