<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="/<?=ADMIN_DIR?>/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/<?=ADMIN_DIR?>/favicon.ico" type="image/x-icon">
	<meta charset="utf-8">
	
	<title><?=$CONTENT->title?></title>
	
	<meta name="description" content="" />
	<meta name="keywords" content="" />

	<script type="text/javascript" src="/js/libs/jquery-1.11.0.min.js"></script>
	<script src="/js/jquery-ui-1.9.2.custom.min.js" type="text/javascript"></script>
	
	<link href="/css/font-awesome-4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	
	<link rel="stylesheet/less" type="text/css" href="/<?=ADMIN_DIR?>/css/style.less" />
	
	<script src="/<?=ADMIN_DIR?>/js/slonne.js" type="text/javascript"></script>
	<script src="/<?=ADMIN_DIR?>/js/slonne.entities.js" type="text/javascript"></script>
	<script src="/<?=ADMIN_DIR?>/js/slonne.reminder.js" type="text/javascript"></script>
    <script src="/<?=ADMIN_DIR?>/js/slonne.fields.js" type="text/javascript"></script>

	<!--
	<script src="/<?=ADMIN_DIR?>/js/slonne.modules.js" type="text/javascript"></script>
	<script src="/<?=ADMIN_DIR?>/js/slonne.admins.js" type="text/javascript"></script>
	<script src="/<?=ADMIN_DIR?>/js/slonne.adminGroups.js" type="text/javascript"></script>
	<script src="/<?=ADMIN_DIR?>/js/slonne.essences.js" type="text/javascript"></script>
	<script src="/<?=ADMIN_DIR?>/js/slonne.fields.js" type="text/javascript"></script>
	<script src="/<?=ADMIN_DIR?>/js/slonne.constants.js" type="text/javascript"></script> 
	 -->
	<script src="/<?=ADMIN_DIR?>/js/slonne.catalog.js" type="text/javascript"></script>
	<script src="/<?=ADMIN_DIR?>/js/slonne.adv.js" type="text/javascript"></script>
	
	<!--CKEditor-->
	<script src="/<?=INCLUDE_DIR?>/ckeditor_4.5.3_full/ckeditor/ckeditor.js"></script>
	
	<!--LESS-->
	<script src="/js/libs/less/less-1.7.3.min.js" type="text/javascript"></script>
	
	<!--fancyBox-->
	<script type="text/javascript" src="/js/plugins/jquery.fancyBox-v2.1.5/jquery.fancybox.pack.js?v=2.1.5"></script>
	<link rel="stylesheet" type="text/css" href="/js/plugins/jquery.fancyBox-v2.1.5/jquery.fancybox.css?v=2.1.5" media="screen" />
	
	<!--Calendar-->
	<script type="text/javascript" language="javascript" src="/js/calendar/calendar.js"></script>
	<script type="text/javascript" language="javascript" src="/js/calendar/calendar-setup.js"></script>
	<script type="text/javascript" language="javascript" src="/js/calendar/lang/calendar-ru.js"></script>
	<link rel="StyleSheet" href="/js/calendar/calendar.css" type="text/css">
	
	<!--stickr-->
	<script src="/js/plugins/jquery.stickr.js" type="text/javascript"></script>
	
	
	<!--стандартные js Slonne-->
	<script type="text/javascript" src="/js/common.js"></script>
	
	
	
	<!--Модальное окно-->
	<script type='text/javascript' src='/js/plugins/jquery.simplemodal/jquery.simplemodal.js'></script>
	<link rel="stylesheet" type="text/css" href="/js/plugins/jquery.simplemodal/simplemodal.css" />
	
	
	
	
	<!--Шрифт OPEN SANS-->
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:600,300&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=PT+Sans:400,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Mystery+Quest' rel='stylesheet' type='text/css'>
	
	<script type="text/javascript" src="/js/libs/highslide-4.1.13/highslide-with-gallery.packed.js"></script>
	<link rel="stylesheet" type="text/css" href="/js/highslide/highslide.css" />
	
	
	
	<script>
	hs.graphicsDir = '/js/libs/highslide-4.1.13/graphics/';
	hs.align = 'center';
	hs.transitions = [];
	hs.outlineType = 'rounded-white';
	hs.fadeInOut = true;
	hs.headingEval = '';
	hs.captionEval = '';
	hs.lang.creditsText = '';
	//hs.dimmingOpacity = 0.75;
	//alert(hs.lang.creditsText)
	
	// Add the controlbar
	hs.addSlideshow({
		//slideshowGroup: 'group1',
		interval: 5000,
		repeat: false,
		useControls: false,
		fixedControls: true,
		overlayOptions: {
			opacity: .75,
			position: 'bottom center',
			hideOnMouseOut: true
		}
	});
	</script>
	
	


	
	<script>
	function logout()
	{
		$.ajax({
			url: '/<?=ADMIN_URL_SIGN?>/auth/logout/',
			data: '',
			dataType: 'html',
			success: function(data){
				location.href = '/<?=ADMIN_URL_SIGN?>/auth'
			},
			error: function(){alert('Возникла ошибка...Попробуйте позже!')},
			complete: function(){}
		});	
	}
	</script>

	

	

</head>



<body >
	<?php
if(Admin::isAdmin())
{
	Core::renderPartial(SHARED_VIEWS_DIR.'/adminTools/adminPanel.php', $model=null, $buffer=false, $ignoreIsAdminka=true);
}
?>
	<div class="container">
	
		<div class="table full-width full-height">
			
			<? //Core::renderPartial(SHARED_VIEWS_DIR.'/menu2.php', $tmp = array('menu'=>$GLOBALS['MENU2']));?>
			<? Core::renderPartial(SHARED_VIEWS_DIR.'/menu3.php', $tmp = array('menu'=>$GLOBALS['MENU2']));?>
			
			<!--<div class="row">
				<div class="cell"><? Core::renderPartial(SHARED_VIEWS_DIR.'/menu.php', $tmp = array('menu'=>$GLOBALS['MENU'], 'class'=>"menu"));?></div>	
			</div>-->
			
			<div class="row">
				<div class="cell full-height">
					<? //Core::renderPartial(SHARED_VIEWS_DIR.'/menu.php', $tmp = array('menu'=>$GLOBALS['MENU'], 'class'=>"leftMenu"));?>

					<div class="content" style=" border: 0px solid red; padding: 0; margin: 10px;  " >
						<?=$CONTENT->content?>
					</div>
				</div>	
			</div>
			
			<iframe name="frame1" style="display: none; width: 98%; border: 1px dashed #0e0e0e; height: 400px;">1</iframe>
			<div class="row">
				<div class="cell">
					<!--Футер-->
					<div id="footer">
						<div class="inner">
							<div id="caution"> 
								<b class="mystery engine-name">SLoNNe CMS</b> v5.3 
								<br><b>"Hanna Zuckerbrod"</b> project.
							</div>
							<div id="razrab">Разработка &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="mailto:tsop.tya@gmail.com">CoBeCTb'soft</a> &copy;2014</div>
						</div> 
					</div>
					<!--//Футер-->
				</div>	
			</div>
			
			
		</div>
		
		
	</div>
</body>

</html> 