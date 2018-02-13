<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	<meta charset="utf-8">
	
	<title><?=$CONTENT->title?></title>
	
	<meta name="keywords" content="<?=$CONTENT->metaKeywords?>" />
	<meta name="description" content="<?=$CONTENT->metaDescription?>" />

	<script type="text/javascript" src="/js/libs/jquery-1.11.0.min.js"></script>
	<? //require_once(INCLUDE_DIR.'/constants_js.php');?>
	
	<!--LESS-->
	<link rel="stylesheet/less" type="text/css" href="/css/style2.less" />
	<link rel="stylesheet/less" type="text/css" href="/css/slonne.less" />
	<script src="/js/libs/less/less-1.7.3.min.js" type="text/javascript"></script>
	
	<link href="/css/font-awesome-4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	
	<script type="text/javascript" src="/js/libs/highslide-4.1.13/highslide-full.packed.js"></script>
	<link rel="stylesheet" type="text/css" href="/js/libs/highslide-4.1.13/highslide.css" />
	
	<!--стандартные js Slonne-->
	<script type="text/javascript" src="/js/common.js"></script>
	<!--кабинет-->
	<script src="/js/slonne.cabinet.js" type="text/javascript"></script>
	<!--формы-->
	<!-- <script src="/js/slonne.forms.js" type="text/javascript"></script> -->
	
	<!--stickr-->
	<script src="/js/plugins/jquery.stickr.js" type="text/javascript"></script>
	<!--fancyBox-->
	<script type="text/javascript" src="/js/plugins/jquery.fancyBox-v2.1.5/jquery.fancybox.pack.js?v=2.1.5"></script>
	<link rel="stylesheet" type="text/css" href="/js/plugins/jquery.fancyBox-v2.1.5/jquery.fancybox.css?v=2.1.5" media="screen" />
	<!--Модальное окно-->
	<script type='text/javascript' src='/js/plugins/jquery.simplemodal/jquery.simplemodal.js'></script>
	<link rel="stylesheet" type="text/css" href="/js/plugins/jquery.simplemodal/simplemodal.css" />
	
	<!--Карусель (для клиентов)-->
	<!-- <script type='text/javascript' src='/js/plugins/jquery.jcarousellite.min.js'></script> -->
	
	<!--Слайдер (для индекса)-->
	<!-- <script type='text/javascript' src='/js/plugins/jquery.superslides/jquery.superslides.min.js'></script>
	<link rel="stylesheet" type="text/css" href="/js/plugins/jquery.superslides/superslides.css" /> -->
	
	
	
	<script type="text/javascript">
		hs.graphicsDir = '/js/libs/highslide-4.1.13/graphics/';
		hs.align = 'center';
		hs.transitions = ['expand', 'crossfade'];
		hs.wrapperClassName = 'dark borderless floating-caption';
		hs.fadeInOut = true;
		hs.dimmingOpacity = .65;
		hs.showCredits = false;
	
		// Add the controlbar
		if (hs.addSlideshow) hs.addSlideshow({
			//slideshowGroup: 'group1',
			interval: 5000,
			repeat: false,
			useControls: true,
			fixedControls: 'fit',
			overlayOptions: {
				opacity: .6,
				position: 'bottom center',
				hideOnMouseOut: true
			}
		});
	</script>
	
	
	
	<!-- sectionHeader -->
	<?=$CONTENT->sectionHeader?>
	<!-- //sectionHeader -->
	
</head>




<body >

<?php
//vd($_SESSION['admin']);
//vd($ADMIN);
if($ADMIN && $ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR))
{
	Core::renderPartial(SHARED_VIEWS_DIR.'/adminTools/adminPanel.php');
}
?>

	<table class="global-table" border="0">
		<tr>
			<td>
	
	<div class="container top">
		<div class="limited">
		
			<a href="<?=Route::getByName(Route::MAIN)->url()?>" class="logo" title="OSTATKI.KZ"><img src="/img/logo.png" alt="" width="233" /></a>
			
			
			<a class="add-adv-btn " href="<?=Route::getByName(Route::CABINET_ADV_EDIT)->url()?>">+ Подать объявление</a>
		
			<!--города-->
			<div class="current-city">
				Город: <a href="#" onclick="$('#city-pick').slideDown(); return false; "><?=$_GLOBALS['city']->name?></a>
			</div>
			<div class="cities" id="city-pick">
				<a href="#" class="close" onclick="$('#city-pick').slideUp(); return false;" title="закрыть">&times;</a>
				<h4>Выберите город:</h4>
				<?php 
				foreach($_GLOBALS['cities'] as $city)
					if($city->isLarge)
						$largeCities[] = $city;
					else 
						$smallCities[] = $city;
				?>
				
				<ul class="large">
				<?php
				foreach($largeCities as $city) 
				{?>
					<li><a href="?globalCityId=<?=$city->id?>" class="<?=$city->id==$_GLOBALS['city']->id? 'active' : ''?>" ><?=$city->name?></a></li>
				<?php 
				}?>
				</ul>
				
				<ul class="small">
				<?php
				foreach($smallCities as $city) 
				{?>
					<li><a href="?globalCityId=<?=$city->id?>" class="<?=$city->id==$_GLOBALS['city']->id? 'active' : ''?>" ><?=$city->name?></a></li>
				<?php 
				}?>
				</ul>
				
				
			</div>
			<!--//города-->
		
			
			<!-- поиск вверху -->
			<div class="top-search">
				<form  class="search" method="get" action="<?=Route::getByName(Route::SPISOK_OBYAVLENIY_KATEGORII)->url()?>">
					<input type="text" name="s" placeholder="Поиск..." />
					<button type="submit" class="bottom-search-btn" title="Искать"><i class="fa fa-search" ></i></button>
				</form>
			</div>
			<!-- /поиск вверху -->
			
			
				<!--авторизация-->
				<div class="auth" >
					<?php
					if($USER)
						Core::renderPartial('cabinet/topAuthPartials/topGreetingPartial.php', $USER);
					else
						Core::renderPartial('cabinet/topAuthPartials/topAuthFormPartial.php');
					?>
				</div>
				<!--//авторизация-->
		</div>
	</div>

		</td>		
	</tr>
	
	
	
	<tr>
		<td style="height: 100%; ">
	<div class="container content">
		<div class="limited">
			<div class="inner">
			
			
				<div class="subtop">
				
					<?Core::renderPartial(SHARED_VIEWS_DIR.'/topMenu.php', $arr = array('list'=>$_GLOBALS['MENU']))?>
					
					
					<div class="clear"></div>
				</div>
				
				
				<!--КОНТЕНТ-->
				<div class="real-content">
					<?=$CONTENT->content?>
				</div>
				<!--//КОНТЕНТ-->
			
			</div>
		</div>
	</div>
	
		</td>
	</tr>
	
	
	
	<tr>
		<td>
	<div class="container footer">
		<div class="limited">
		
			<div class="info">
			
				<div class="column left">
					<a href="/" class="logo" title="OSTATKI.KZ - перейти на главную"><img src="/img/logo-bottom.png" alt="OSTATKI.KZ" /></a>
					<div class="copyright">
						2015 &copy; Все права защищены. <br/>
						<div class="slonne">Powered by <b style="font-size: 1.1em; ">SLONNE</b><img src="/img/slonne.png" alt="" width="18" style="vertical-align:middle; "/></div>
					</div>
				</div>
				
				<div class="column center">
					<div class="phone">8 (727) 995-65-54</div>
					<a href="/<?=LANG?>/forms/feedback" class="request-call">заказать звонок</a>
					<h4>Наш офис:</h4>
					<div class="address">г. Алматы, Панфилова 110 , офис 209, <br/>уг. Богенбай батыра.</div>
					<a href="/<?=LANG?>/forms/feedback" class="send-msg">отправить сообщение</a>
				</div>
				
				<div class="column right">
					<h4>МЫ В СОЦИАЛЬНЫХ СЕТЯХ</h4>
					<div class="socials">
						<a href="/" class="facebook" title="FaceBook"><i class="fa fa-facebook"></i></a>
						<a href="/" class="vk" title="ВКонтакте"><i class="fa fa-vk"></i></a>
						<a href="/" class="insta" title="Instagram"><i class="fa fa-instagram"></i></a>
					</div>
					
					<form  class="search" method="get" action="<?=Route::getByName(Route::SPISOK_OBYAVLENIY_KATEGORII)->url()?>">
						<span>Вы ищете что-то конкретное?</span>
						<input type="text" name="s" placeholder="Поиск..." />
						<button type="submit" class="bottom-search-btn" title="Искать"><i class="fa fa-search" ></i></button>
					</form>
					
				</div>
				<div class="clear"></div>
				
			</div>
			
			
			<img id="cement" src="/img/cement-bottom.png" alt=""  />
			<img id="plitka" src="/img/plitka-bottom.png" alt=""  />
			<div id="banner-wrapper">
				<img id="banner" src="/img/banners/appolonius.jpg" alt=""  />
			</div>
			
			<img class="screw screw-1" src="/img/screw.png" alt=""  />
			<img class="screw screw-2" src="/img/screw.png" alt=""  />
			<img class="screw screw-3" src="/img/screw.png" alt=""  />
			<img class="screw screw-4" src="/img/screw.png" alt=""  />
			
		</div>
	</div>
		
		</td>
	</tr>
	
	</table>

	<iframe name="iframe1" style="width: 700px; height: 400px;  background: #fff; display: none;  ">asdasd</iframe>
</body>
</html> 




<script>
jQuery(function ($) {
	// Load dialog on page load
	//$('#basic-modal-content').modal();

	// Load dialog on click
	$('.modal-opener').click(function (e) {
		$('#float-form-wrapper').modal();
		return false;
	});
});
</script>
