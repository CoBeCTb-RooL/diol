<?php 
//$timers = $_GLOBALS['timers'];
//vd($_SESSION['timersDeserialized']);
$timers = $_SESSION['timers'];
//vd($timers);

?>


<style>
body{}
.admin-panel{ background: #ececec  ; width: 100%; border: 1px solid #ccc; padding: 3px 0px 4px 0px; border-left: none; border-right: none;  font-weight: normal; font-size: 11px; font-family: tahoma; text-shadow: 1px 1px 0px #eee; color: #555; min-height: 18px; }

.admin-panel .core-info{padding: 4px  10px ;margin: 0 0 4px 0;  background: #ddd;  }
.admin-panel .core-info .row{line-height: 160%; }
.admin-panel .core-info .label{ min-width: 70px; text-align: right;  display: inline-block; padding: 0 10px 0 0 ; }
.admin-panel .core-info .value{display: inline-block; font-weight: bold; }
.admin-panel .core-info hr{margin: 4px 0; }

.admin-panel .btns{margin: 0 10px 4px 6px; text-shadow: 1px 1px 0px #eee; }
.admin-panel .btns a{background: #ddd; padding: 0 3px ; margin: 0 2px; ; color: #555; border: 1px solid #aaa; border-radius: 4px; text-transform: none; height: auto; font-weight: normal; font-size: 11px; font-family: tahoma; text-shadow: 1px 1px 0px #eee;  text-decoration: none;   }
.admin-panel .btns a:hover{background: #ececec;}
.admin-panel .btns a:active{background: #ccc; }

.admin-panel #admin-panel-info{border: 1px solid #ccc; background: #000; color: LimeGreen  ;  padding: 4px; border-left: none; border-right: none;  font-size: 11px; line-height: 200%; max-height: 400px; overflow: auto ; text-shadow: none;}



</style>


<script>
function showTimers(){
	//alert($('#admin-panel-info').css('display'))
	if($('#admin-panel-info').css('display') == 'none' ){
		
		$.ajax({
			url: '/<?=ADMIN_URL_SIGN?>/tools/showTimers',
			data: '', 
			beforeSend: function(){$.fancybox.showLoading(); },
			success: function(data){
				//alert(data)
				$('#admin-panel-info').html(data).slideDown('fast');
			},	
			error: function(data){},
			complete: function(){$.fancybox.hideLoading(); },
		});
	}
	else{
		$('#admin-panel-info').slideUp('fast')
	}
}
</script>



<div class="admin-panel admin">
	
	
	
	<div class="btns">
		
		<a href="#" onclick="$('.admin').slideUp('fast');">&times;</a>
		
		<span style="padding: 0 7px 0 3px; "><b class="name"><?=$ADMIN->name?></b> (<?=$ADMIN->email?> | <b><?=$ADMIN->group->name?></b>)</span>
		
		<a href="/index" target="_blank">Сайт</a>
		<a href="/<?=ADMIN_URL_SIGN?>" target="_blank">Админка</a>
		<!-- <a href="#timers" onclick="$('#timers').slideToggle('fast'); return false; "><i class="fa fa-clock-o"></i> время загрузки</a> -->
		<a href="#" onclick="$('#core-info').slideToggle('fast'); return false; ">core info</a>
		<a href="#timers" onclick="showTimers(); return false; "><i class="fa fa-clock-o"></i> время загрузки</a>
	</div>
	
	
	
	<div id="admin-panel-info" style="display: none; ">
		<?//Core::renderPartial(SHARED_VIEWS_DIR.'/adminTools/timers.php', $arr = array('timers'=>$_SESSION['timers']))?>
	</div>
	
	
	
	<!-- общая инфа -->
	<div class="core-info" id="core-info" style="display: none; ">
		<div class="row"><span class="label">controller: </span><span class="value"><?=$CORE->controller?> <span style="font-weight: normal; ">&nbsp;<?=$CORE->controllerPath?></span></span></div>
		<div class="row"><span class="label">action: </span><span class="value"><?=$CORE->action ? $CORE->action  : '<span style="color: green; ">index </span>'?></span></div>
		<div class="row"><span class="label">view: </span><span class="value"><?=VIEWS_DIR?>/<?=$CORE->view?></span></div>
		<hr />
		<div class="row"><span class="label">язык: </span><span class="value">[<?=$CORE->lang->code?>] <?=$CORE->lang->name?></span></div>
		<div class="row"><span class="label">isAdminka: </span><span class="value"><?=$CORE->isAdminka ? 'true' : 'false'?></span></div>
		<div class="row"><span class="label">layout: </span><span class="value"><?=$CORE->layout?> <span style="font-weight: normal;">&nbsp;<?=$CORE->layoutPath?></span></span></div>
		<!-- <div class="row"><span class="label">startup: </span><span class="value"><?=$CORE->startupFile?></span></div> -->
		
		<hr />
		<div class="row"><span class="label">route: </span><span class="value"><?=$CORE->route?'<span style="color: green; ">да</span>':'нет'?> </span></div>
		<?php 
		if($CORE->route)
		{//vd($CORE->route)?>
			<div class="row"><span class="label">pattern: </span><span class="value">  '<?=$CORE->route->pattern?>' => '<?=$CORE->route->replaceWith?>' &nbsp;&nbsp;("<?=$CORE->route->name?>")</span></div>
			<div class="row"><span class="label">source url: </span><span class="value">'<?=$CORE->route->urlSource?>'</span></div>
			<div class="row"><span class="label">adapted url: </span><span class="value">'<?=$CORE->route->urlTransformed?>'</span></div>
		<?php 	
		}?>
		
		<hr />
		<div class="row"><span class="label">executed in: </span><span class="value"><?=$CORE->globalTimer->time?>s.</span></div>
	</div>
	<!-- общая инфа -->
	
	
	
</div>