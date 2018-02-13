<?php
$comments = $MODEL['comments'];
$item = $MODEL['item'];
//vd($comments);

//vd($USER);
?>


<style>
.comments .item.comment-owner{border: 1px dashed #aaa; }

.comment-status-<?=Status::DELETED?>{opacity: .5; background: #ececec; }
.comment-status-<?=Status::MODERATION?>{background: #D0DEF5; }

.comment-status-<?=Status::ACTIVE?> .odobrit-btn{display: none; }
.comment-status-<?=Status::ACTIVE?> .return-btn{display: none; }
.comment-status-<?=Status::DELETED?> .odobrit-btn{display: none; }
.comment-status-<?=Status::DELETED?> .delete-btn{display: none; }
.comment-status-<?=Status::MODERATION?> .return-btn{display: none; }


/*метки для админов*/
.status{display: none;}
.comment-status-<?=Status::ACTIVE?> .label-status-<?=Status::ACTIVE?>{display: inline !important; }
.comment-status-<?=Status::MODERATION?> .label-status-<?=Status::MODERATION?>{display: inline !important; }
.comment-status-<?=Status::DELETED?> .label-status-<?=Status::DELETED?>{display: inline !important; }


.admin-panel{}
.admin-panel a{font-size: 11px; font-weight: bold; }
.admin-panel a.delete-btn{font-size: 11px; font-weight: bold; color: red; margin: 0 0 0 20px; }

.status{font-size: 17px; font-weight: bold; color: #777; }
.comment-status-<?=Status::ACTIVE?> .status{display: none; }
</style>


<script>
function setStatus(id, status)
{
	$.ajax({
		url: '/<?=ADMIN_URL_SIGN?>/comment/setStatus',
		data: 'id='+id+'&status='+encodeURIComponent(status)+'',
		dataType: 'json',
		befodeSend: function(){$.fancybox.showLoading();},
		success: function(data){
			//alert('ok')
			if(!data.errors)
			{
				$('#comment-'+id).removeClass('comment-status-<?=Status::ACTIVE?>').removeClass('comment-status-<?=Status::MODERATION?>').removeClass('comment-status-<?=Status::DELETED?>').addClass('comment-status-'+data.status)
			}
			else
				showErrors(data.errors)
		},
		complete: function(){$.fancybox.hideLoading();},
		error: function(){alert('Возникла ошибка на сервере...попробуйте позже.')},
	});
}



function ownerDeleteComment(id)
{
	$.ajax({
		url: '/<?=Adv::FRONTEND_CONTROLLER?>/comment/delete',
		data: 'id='+id+'',
		dataType: 'json',
		befodeSend: function(){},
		success: function(data){
			//alert('ok')
			if(data.errors==null)
			{
				$('#comment-'+id).fadeOut();
			}
			else
				alert(data.errors[0].error)
		},
		complete: function(){},
		error: function(){alert('Возникла ошибка на сервере...попробуйте позже.')},
	});
}

function sendComment()
{
	var error = false
	var errors = []
	var f = $('.comments form');
	var text = f.find('#text')
	var captcha = f.find('#captcha')
	text.removeClass('error')
	captcha.removeClass('error')
	
	if(text.val()=='')
		errors.push({"field": "text", "error": "Введите <b>комментарий</b>"})
	if(captcha.val()=='')
		errors.push({"field": "captcha", "error": "Введите <b>код</b>"})
	
	if(errors.length>0 )
		showCommentErrors(errors)
	else{
		$.ajax({
			url: '/adv/comment/add',
			data: 'id=<?=$item->id?>&text='+encodeURIComponent(text.val())+'&captcha='+encodeURIComponent(captcha.val())+'',
			dataType: 'json',
			beforeSend: function(){f.find('.loading').css('display', 'inline')},
			success: function(data){
				//alert('ok')
				if(data.errors==null){
					f.slideUp();
					$('.comments form+.success').slideDown()
				}
				else{
					showCommentErrors(data.errors)
				}
			},
			complete: function(){f.find('.loading').css('display', 'none')},
			error: function(){f.find('.info').html('Возникла ошибка на сервере...попробуйте позже.')},
		});
	}
}


function showCommentErrors(errors){
	var str="Проблемы: <br/>";
	for(var i in errors){
		$('#'+errors[i].field).addClass('field-error')
		str+=" - "+errors[i].error+"<br/>";
	}
		
	window.top.error(str)
}

</script>


<div class="comments">

	<h2>Комментарии</h2>
	
	<?php 
	if(count($comments) )
	{?>
		<?php 
		foreach($comments as $key=>$c)
		{
			$isCommentOwner = $c->userId==$USER->id;
			$isAdvOwner = $c->userId == $item->userId; 
			?>
			<div class="item comment-status-<?=$c->status->code?> <?=$isCommentOwner ? "comment-owner" : ""?>" id="comment-<?=$c->id?>" >
				
				<div class="name"><?=$c->user ? $c->user->name : 'Гость'?> <?=$isAdvOwner ? '<span class="adv-owner">(хозяин объявления)</span>' : ''?></div>
				<div class="date">, <?=mb_strtolower(Funx::mkDate($c->dateCreated, 'with_time'), 'utf-8')?></div>
				<?php 
				if(Admin::isAdmin())
				{?>
					<span>#<?=$c->id?></span>
					<span class="status label-status-<?=Status::ACTIVE?>"><?=Status::code(Status::ACTIVE)->name?></span>
					<span class="status label-status-<?=Status::MODERATION?>"><?=Status::code(Status::MODERATION)->name?></span>
					<span class="status label-status-<?=Status::DELETED?>"><?=Status::code(Status::DELETED)->name?></span>
				<?php 	
				}
				else // отображение метки МОДЕРАЦИЯ для хозяев комментариев, ожидающих модерацию
				{?>
					<span class="status label-status-<?=Status::MODERATION?>"><?=Status::code(Status::MODERATION)->name?></span>
				<?php 	
				}?>
				<div class="text"><?=$c->text?></div>
				
				<div class="admin-panel admin">
					<?php 
					if(Admin::isAdmin())
					{?>
					<a href="#" class="odobrit-btn" onclick="if(confirm('Уверены?')){setStatus(<?=$c->id?>, '<?=Status::ACTIVE?>');} return false; ">одобрить</a>
					<a href="#" class="return-btn" onclick="if(confirm('Уверены?')){setStatus(<?=$c->id?>, '<?=Status::ACTIVE?>');} return false;">вернуть</a>
					<a href="#" class="delete-btn" onclick="if(confirm('Уверены?')){setStatus(<?=$c->id?>, '<?=Status::DELETED?>');} return false;">&times; удалить</a>
					<?php 	
					}
					elseif($isCommentOwner) 	// 	хозяин комментария
					{?>
						<span style="font-size: 11px; font-weight: bold; ">Это Ваш комментарий. &nbsp;Действия: </span>
						<a href="#" class="delete-btn" onclick="if(confirm('Уверены?')){ownerDeleteComment(<?=$c->id?>);} return false;">&times; удалить</a>
					<?php 	
					}?>
				</div>
				
			</div>
		<?php 	
		}?>
	<?php 	
	}
	else
	{?>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Комментариев нет.
	<?php 	
	}?>	
	
	
	
	
	
	<hr style="margin: 20px 0  17px 0;" />

	<a href="#add_comment" class="btn-square" onclick="$(this).slideUp('fast'); $('#add-comment-form').slideDown('fast'); return false; ">Написать комментарий</a>
	<form id="add-comment-form" style="display: none; " action="" onsubmit="sendComment(); return false; ">
		<h4>Ваш комментарий:</h4>
		<textarea id="text" ></textarea>
		<table border="0">
			<tr>
				<td width="1" valign="top">
					<img src="/<?=INCLUDE_DIR?>/kcaptcha/?<?=session_name()?>=<?=session_id()?>" id="captcha-pic">
					
				</td>
				<td valign="top" >
					Введите текст на изображении<i class="req">*</i>: <br>
					<input type="text"  id="captcha" >
					<br/><a href="javascript:void(0)" onclick="$('#captcha-pic').attr('src', '/<?=INCLUDE_DIR?>/kcaptcha/?'+(new Date()).getTime());" id="re-captcha">Не вижу код</a>
				</td>
			</tr>
		</table>
		<input type="submit" value="ОТПРАВИТЬ" />
		<span class="loading" style="display: none; ">Секунду...</span>
		<span class="info"></span>
	</form>
	
	<div class="success" >
		<h4>Ваш комментарий отправлен!</h4>
		<h5>После модерации он появится на сайте.</h5>
	</div>
</div>
