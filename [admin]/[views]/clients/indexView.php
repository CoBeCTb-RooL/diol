
<script>

    var opts = {
        p: 1,
        elPP: 10,
    }

	function list()
	{
		$.ajax({
			url: '/<?=ADMIN_URL_SIGN?>/clients/list',
			data: opts,
			beforeSend: function(){$.fancybox.showLoading()},
            complete: function(){ $("#cats").css("opacity", "1"); $('#cats').slideDown('fast'); $('#items').slideUp('fast');  $.fancybox.hideLoading() },
			success: function(data){
				$('#cats').html(data)
			},
			error: function(e){}

		});
	} 



	function edit(id)
	{
		$.fancybox.showLoading()
		
		$.ajax({
			url: '/<?=ADMIN_URL_SIGN?>/clients/edit',
			data: 'id='+id+'',
			beforeSend: function(){$.fancybox.showLoading()},
			success: function(data){
				$('#float').html(data)
				$.fancybox('#float');
			},
			error: function(e){alert('Возникла ошибка на сервере... Попробуйте позже.')},
			complete: function(){$.fancybox.hideLoading()}
		});
	}


	function editSubmitStart()
	{ 
		$.fancybox.showLoading()
	}
	function editSubmitComplete(data)
	{
		$.fancybox.hideLoading()
		if(!data.errors)
		{
			list()
			//$.fancybox.close()
			notice('Сохранено')
		}
		else
			showErrors(data.errors)
	}


	/*function listSubmitStart()
	{ 
		$.fancybox.showLoading()
		$('#cats').css('opacity', .4)
	}
	function listSubmitComplete(data)
	{
		$.fancybox.hideLoading()
		$('#cats').css('opacity', 1)
		if(!data.errors)
		{
			list()
			//$.fancybox.close()
			notice('Сохранено')
		}
		else
			showErrors(data.errors)
	}*/



    function switchStatus(id)
    {
        $.ajax({
            url: '/<?=ADMIN_URL_SIGN?>/clients/switchStatus',
            data: 'id='+id,
            dataType: 'json',
            beforeSend: function(){$.fancybox.showLoading()},
            success: function(data){
                //alert(data.errors)
                if(!data.errors)
                {
                    $('#status-switcher-'+id).html(data.status.icon)
                    $('#cat-'+id).removeAttr('class').addClass('status-'+data.status.code)
                    notice('Сохранено')
                }
                else
                    showErrors(data.errors)
            },
            error: function(e){alert('Возникла ошибка на сервере... Попробуйте позже.')},
            complete: function(){$.fancybox.hideLoading()}
        });
    }




    function deleteMedia(id)
    {
        if(!confirm('Удалить?'))
            return

        $.ajax({
            url: '/<?=ADMIN_URL_SIGN?>/clients/deleteMedia',
            data: 'id='+id,
            dataType: 'json',
            beforeSend: function(){$.fancybox.showLoading()},
            success: function(data){
                //alert(data.errors)
                if(!data.errors)
                {
                    $('#media-'+id).fadeOut()
                    notice('Удалено')
                }
                else
                    alert(data.errors[0].msg)
            },
            error: function(e){alert('Возникла ошибка на сервере... Попробуйте позже.')},
            complete: function(){$.fancybox.hideLoading()}
        });
    }




	


	$(document).ready(function(){
		list()
	});
</script>





<div id="cats">Загрузка....</div>
<div id="items" style="display: none; "> Загрузка....</div>

<iframe name="frame7" style="display: none; "></iframe>



<!--форма редактирования-->
<div id="float" class="view " style="min-width: 1000px; max-width: 1200px;" >!!</div>
<div id="float2" class="view " style="" ></div>
	
	