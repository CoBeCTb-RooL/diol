
<script>



    var Schedule = {

        listOpts: {},
        editOpts: {},


        clientsWrapper: {},

        clientsSearchResult: [],

        clientSearch: function(){
            this.clientsWrapper = $('.clients-list')

            var surname = $('.clients-search-form input[name=clientSurname]').val()
            var name = $('.clients-search-form input[name=clientName]').val()
            var fatherName = $('.clients-search-form input[name=clientFathername]').val()
            var phone = $('.clients-search-form input[name=clientPhone]').val()


            if(surname.length >= 1 || name.length >= 1 || fatherName.length >= 1 || phone.length >= 1){
                var w = this.clientsWrapper.find('.inner')
                w.slideDown('fast')
                //this.clientsWrapper.find('.inner').html(txt)
                var currentRequest = $.ajax({
                    url: '/<?=ADMIN_URL_SIGN?>/schedule/clientsSearch',
                    data: {surname: surname, name:name, fatherName:fatherName, phone:phone },
                    dataType: 'json',
                    beforeSend: function(){
                        if(currentRequest != null)
                            currentRequest.abort();
                        w.find('.loading').slideDown('fast');
                    },
                    complete: function(){},
                    success: function(data){
                        if(!data.error){
                            Schedule.clientsSearchResult = data.list
                            var list = data.list
                            var str = ''
                            if(list.length > 0){
                                $.each(list, function(i, val){
                                    str += Schedule.getFoundClientHtml(val)
                                })
                            }
                            else
                                str = 'Ничего не найдено.'

                            w.html(str)
                        }
                        else{
                            w.html(data.error)
                        }
                    },
                    error: function(){w.html('Возникла ошибка на сервере..')},
                })


            }
            else/* if(txt.length == 0)*/{
                $('.clients-list>.inner').html('')
            }
        },



        getFoundClientHtml: function(val){
            var tmpl = $('.foundClientTmpl').html()
            tmpl = tmpl.replace(/_ID_/g, val.id);
            tmpl = tmpl.replace(/_NAME_/g, val.surname+' '+val.name+' '+val.fathername );
            tmpl = tmpl.replace(/_PHONE_/g, val.phone);

            return tmpl
        },


        setClient: function(id){
            var client = null
            $.each(this.clientsSearchResult, function(i, val){
                if(val.id == id)
                    client = val
            })

            if(client){
                $('.clients-search-form').slideUp('fast')
                $('#schedule-edit-form input[name=clientId]').val(client.id)
                $('.chosenClientWrapper>.inner').html(client.surname+' '+client.name+' '+client.fathername+' ('+client.phone+')')
                $('.chosenClientWrapper').slideDown('fast')
            }
        }


    }




    var opts = {
        date: '<?=date('Y-m-d')?>'
    }


    var editOpts = {
        date: '<?=date('Y-m-d')?>'
    }

	function list1()
	{
		$.ajax({
			url: '/<?=ADMIN_URL_SIGN?>/schedule/list',
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

        editOpts.id = id
		$.ajax({
			url: '/<?=ADMIN_URL_SIGN?>/schedule/edit',
			data: {editOpts: editOpts},
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
			list1()
			$.fancybox.close()
			notice('Сохранено')
		}
		else
			showErrors(data.errors)
	}



	function setDate(val){
        opts.date=val;
        editOpts.date=val;
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
            url: '/<?=ADMIN_URL_SIGN?>/schedule/switchStatus',
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



	


	$(document).ready(function(){
		list1()
       // edit(1)
	});
</script>





<div id="cats">Загрузка....</div>
<div id="items" style="display: none; "> Загрузка....</div>

<iframe name="frame7" style="display: ; "></iframe>



<!--форма редактирования-->
<div id="float" class="view " style="min-width: 1000px; max-width: 1200px;" >!!</div>
<div id="float2" class="view " style="" ></div>
	
	