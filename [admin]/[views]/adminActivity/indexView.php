<script>
    var opts = {}
function drawList()
{
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/adminActivity/list/',
		data: opts,
		beforeSend: function(){$.fancybox.showLoading(); $('.admins .inner').css('opacity', .3); },
		success: function(data){
			$('.admins .inner').html(data)
		},
		error: function(){alert('Возникла ошибка...Попробуйте позже!')},
		complete: function(){$.fancybox.hideLoading(); $('.admins .inner').css('opacity', 1);}
	});
}


</script>



<h1><?=$_GLOBALS['CURRENT_MODULE']->icon?> Активность админов</h1>



<div id="admins-list" class="admins"> 
	
	<div class="inner"></div>
	<div class="loading" style="visibility: hidden; "> <img src="/<?=ADMIN_DIR?>/img/tree-loading.gif" > </div>
</div>

<!--форма редактирования-->
<div id="float"  style="display: none; min-width: 700px; max-width: 700px;">!!</div>

<script>
$(document).ready(function(){
    drawList()
	
})
</script>