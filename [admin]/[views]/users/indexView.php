<script>
$(document).ready(function(){
	var userId = <?=$_REQUEST['userId'] ? $_REQUEST['userId'] : 0?>;
	if(userId > 0)
	{
		wList.css('display', 'none')
		wView.css('display', 'block')
		userView(userId)
	}
	else
	{
		wList.css('display', 'block')
		wView.css('display', 'none')
		usersList()
	}
});


var OPTS = {}

function usersList()
{
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/user/list/',
		data: OPTS,
		beforeSend: function(){$.fancybox.showLoading(); wList.css('opacity', '.3');},
		success: function(data){
			wList.html(data)
		},
		error: function(){alert('Возникла ошибка...Попробуйте позже!')},
		complete: function(){ $.fancybox.hideLoading();  wList.css('opacity', '1') }
	});
}



function userView(id)
{
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/user/view/',
		data: 'id='+id,
		beforeSend: function(){$.fancybox.showLoading(); wView.css('opacity', '.3');},
		success: function(data){
			wView.html(data)
			wList.slideUp('fast')
			wView.slideDown('fast')
		},
		error: function(){alert('Возникла ошибка...Попробуйте позже!')},
		complete: function(){ $.fancybox.hideLoading();  wView.css('opacity', '1') }
	});
}

</script>


<?php Core::renderPartial('adv/menu.php', $model);?>


<h1><span class="fa fa-users "></span> Пользователи</h1>

<div id="users-list" class="users"></div>
<div id="users-view" class="users" ></div>

<iframe name="frame1" style="display: none; width: 98%; border: 1px dashed #0e0e0e; height: 400px;">1</iframe>


<!--форма редактирования-->
<div id="float"  style="display: ; min-width: 700px; max-width: 700px;">!!</div>


<script>
var wList = $('#users-list')
var wView = $('#users-view')
</script>
