<script>
var OPTIONS = {}
OPTIONS.statusNotIn = '<?=Status::ARCHIVED?>'

$(document).ready(function(){
	//groupsList()
	listAll()
});



function groupsList()
{
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/tasks/groupsList/',
		data: OPTIONS,
		beforeSend: function(){$.fancybox.showLoading(); },
		success: function(data){
			$('#tasks-list').html(data)
		},
		error: function(){alert('Возникла ошибка...Попробуйте позже!')},
		complete: function(){ $.fancybox.hideLoading(); }
	});
}
function listAll()
{
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/tasks/listAll/',
		data: OPTIONS,
		beforeSend: function(){$.fancybox.showLoading(); },
		success: function(data){
			$('#tasks-list').html(data)
		},
		error: function(){alert('Возникла ошибка...Попробуйте позже!')},
		complete: function(){ $.fancybox.hideLoading(); }
	});
}


function groupEdit(id)
{
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/tasks/groupsEdit',
		data: 'id='+id,
		beforeSend: function(){$.fancybox.showLoading()},
		success: function(data){
			$('#float').html(data)
			$.fancybox('#float');
		},
		error: function(e){alert(e=='' ? e : 'Возникла ошибка на сервере... Попробуйте позже.')},
		complete: function(){$.fancybox.hideLoading()}
	});
}

function groupSave()
{
	var form = $('#form')
	
	var errors = []
	var name = form.find('input[name=name]').val() 
	if(name=='')
		errors.push({"field": "name", "error": 'Введите значение!'})
		
	if(errors.length>0){
		showErrors(errors)
		return
	}

	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/tasks/groupsEditSubmit',
		data: 'id='+form.find('input[name=id]').val()+'&name='+encodeURIComponent(name)+'',
		dataType: 'json',
		beforeSend: function(){$.fancybox.showLoading()},
		success: function(data){
			if(data.errors==null){
				if(data.isNew)
					listAll()
				else
					$('#group-'+data.result.id+' h1').html(data.result.name)
				$.fancybox.close()
				notice('Сохранено!')
			}
			else
				showErrors(data.errors)
		},
		error: function(e){alert('Возникла ошибка на сервере... Попробуйте позже.')},
		complete: function(){$.fancybox.hideLoading()}
	});
}




function groupDelete(id)
{
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/tasks/groupsDelete',
		data: 'id='+encodeURIComponent(id),
		dataType: 'json',
		beforeSend: function(){$.fancybox.showLoading()},
		success: function(data){
			if(data.errors==null){
				$('#group-'+id).fadeOut();
			}
			else
				showErrors(data.errors)
		},
		error: function(e){alert('Возникла ошибка на сервере... Попробуйте позже.')},
		complete: function(){$.fancybox.hideLoading()}
	});
}


/*
function tasksList(groupId)
{
	if(typeof groupId == 'undefined')
		groupId = ''
	if($('#group-'+groupId+' .tasks').css('display') != 'none')
	{
		$('#group-'+groupId+' .tasks').slideUp('fast')
		return
		
	}
		
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/tasks/tasksList/',
		data: 'groupId='+encodeURIComponent(groupId),
		beforeSend: function(){$.fancybox.showLoading(); },
		success: function(data){
			$('#group-'+groupId+' .tasks').html(data).slideDown('fast')
		},
		error: function(){alert('Возникла ошибка...Попробуйте позже!')},
		complete: function(){ $.fancybox.hideLoading(); }
	});
}*/



function tasksListToggle(groupId)
{
	$('#group-'+groupId+' .tasks').slideToggle('fast')
}





function taskEdit(id, groupId)
{
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/tasks/tasksEdit',
		data: 'id='+id+'&groupId='+groupId,
		beforeSend: function(){$.fancybox.showLoading()},
		success: function(data){
			$('#float').html(data)
			$.fancybox('#float');
		},
		error: function(e){alert(e=='' ? e : 'Возникла ошибка на сервере... Попробуйте позже.')},
		complete: function(){$.fancybox.hideLoading()}
	});
}



function taskSave()
{
	var form = $('#form')
	
	var errors = []
	var title = form.find('textarea[name=title]').val() 
	//alert(title)
	if(title=='')
		errors.push({"field": "title", error: 'Введите задачу!'})
		
	if(errors.length>0){
		showErrors(errors)
		return
	}

	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/tasks/tasksEditSubmit',
		data: 'id='+form.find('input[name=id]').val() +'&title=' + encodeURIComponent(title)+ '&groupId=' + form.find('input[name=groupId]').val() + '',
		dataType: 'json',
		beforeSend: function(){$.fancybox.showLoading()},
		success: function(data){
			if(data.errors==null){
				if(data.isNew)
				{
					listAll()

                    if(data.group){
                        if(data.group.status.code == '<?=Status::DONE?>')
                            $('#group-'+data.group.id).addClass('group-status-<?=Status::DONE?>')
                        else
                            $('#group-'+data.group.id).removeClass('group-status-<?=Status::DONE?>')
                    }
				}
				else
					$('#task-'+data.result.id+' h1').html(data.result.title)
				$.fancybox.close()
				notice('Сохранено!')
			}
			else
				showErrors(data.errors)
		},
		error: function(e){alert('Возникла ошибка на сервере... Попробуйте позже.')},
		complete: function(){$.fancybox.hideLoading()}
	});
}



function taskDelete(id)
{
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/tasks/tasksDelete',
		data: 'id='+encodeURIComponent(id),
		dataType: 'json',
		beforeSend: function(){$.fancybox.showLoading()},
		success: function(data){
			if(data.errors==null){
				$('#task-'+id).fadeOut();
				notice('Удалено.')

				if(data.group!=null)
				{
					if(data.group.status.code == '<?=Status::DONE?>')
						$('#group-'+data.group.id).addClass('group-status-<?=Status::DONE?>')
					else
						$('#group-'+data.group.id).removeClass('group-status-<?=Status::DONE?>')
				}
			}
			else
				showErrors(data.errors)
		},
		error: function(e){alert('Возникла ошибка на сервере... Попробуйте позже.')},
		complete: function(){$.fancybox.hideLoading()}
	});
}





function taskCheckboxClick(id)
{
//	alert(id)
	//var isChecked = $('#task-done-'+id).is(':checked');
	//alert(isChecked)

	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/tasks/switchTaskStatus',
		data: 'id='+id,
		dataType: 'json',
		beforeSend: function(){$.fancybox.showLoading()},
		success: function(data){
			if(data.errors==null){
				if(data.status.code == '<?=Status::DONE?>')
				{
					$('#task-done-'+id).attr('checked', 'checked')
					$('#task-'+id).addClass('task-status-<?=Status::DONE?>')
				}
				else
				{
					$('#task-done-'+id).removeAttr('checked')
					$('#task-'+id).removeClass('task-status-<?=Status::DONE?>')
				}

				// 	пересмотр статуса группы
				//alert(data.group.status.code == '<?=Status::DONE?>')
                if(data.group){
                    if(data.group.status.code == '<?=Status::DONE?>')
                        $('#group-'+data.group.id).addClass('group-status-<?=Status::DONE?>')
                    else
                        $('#group-'+data.group.id).removeClass('group-status-<?=Status::DONE?>')
                }
			}
			else
				showErrors(data.errors)
		},
		error: function(e){alert(e=='' ? e : 'Возникла ошибка на сервере... Попробуйте позже.')},
		complete: function(){$.fancybox.hideLoading()}
	});
}



</script>


<h1><i aria-hidden="true" class="fa fa-sticky-note-o"></i> Задачи</h1>

<div id="tasks-list" class="users"></div>

<iframe name="frame1" style="display: none; width: 98%; border: 1px dashed #0e0e0e; height: 400px;">1</iframe>


<!--форма редактирования-->
<div id="float"  style="display: ; min-width: 700px; max-width: 700px;">!!</div>



