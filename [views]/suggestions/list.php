<?php
$suggestionType = $MODEL['suggestionType'];
$status = $MODEL['status'];
$items = $MODEL['items'];

$suggestionTypes = array(
		SuggestionType::code(SuggestionType::QUESTION),
		SuggestionType::code(SuggestionType::SUGGESTION),
);


$isAdmin = Admin::isAdmin();
//vd($isAdmin);
?>




<?php ob_start()?>

<style>
.suggestion-status{display: none; }
.suggestion-status-<?=Status::MODERATION?> .suggestion-status-label-<?=Status::MODERATION?>{display: inline-block; background: #55AFCF; padding: 2px 5px; color: #fff; font-weight: bold; font-family: cuprum; } 
</style>


<script>
function addSuggestion()
{
	var error = false
	var errors = []
	var f = $('#add-suggestion-form');
	var text = f.find('#text')
	var captcha = f.find('#captcha')
	var name = f.find('#name')
	var email = f.find('#email')
	var phone = f.find('#phone')
	f.find('*').removeClass('field-error')
	
	if(text.val()=='')
		errors.push({"field": "text", "error": "Введите <b>комментарий</b>"})
	if(captcha.val()=='')
		errors.push({"field": "captcha", "error": "Введите <b>код</b>"})
	
	if(errors.length>0 )
		showSuggestionErrors(errors)
	else{
		$.ajax({
			url: '/suggestions/add',
			data: 'text='+encodeURIComponent(text.val())+'&captcha='+encodeURIComponent(captcha.val())+'&name='+encodeURIComponent(name.val())+'&email='+encodeURIComponent(email.val())+'&phone='+encodeURIComponent(phone.val())+'&suggestionType='+encodeURIComponent('<?=$suggestionType->code ?>')+'',
			dataType: 'json',
			beforeSend: function(){$.fancybox.showLoading()},
			success: function(data){
				//alert('ok')
				if(data.errors==null){
					f.slideUp();
					$('#add-suggestion-form +.success').slideDown()
				}
				else{
					showSuggestionErrors(data.errors)
				}
			},
			complete: function(){$.fancybox.hideLoading()},
			error: function(){window.top.error('Возникла ошибка на сервере...попробуйте позже.')},
		});
	}
}


function showSuggestionErrors(errors){
	var str="Проблемы: <br/>";
	for(var i in errors){
		$('#'+errors[i].field).addClass('field-error')
		str+=" - "+errors[i].error+"<br/>";
	}
		
	window.top.error(str)
}


<?php 
if($isAdmin)
{?>

		function suggestionEdit(id)
		{
			$.ajax({
				url: '/suggestions/editForm',
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



		function adminSaveSuggestion(id)
		{
			var f = $('#admin-suggestion-form');
			var name = f.find('input[name=name]')
			var email = f.find('input[name=email]')
			var phone = f.find('input[name=phone]')
			var text = f.find('textarea[name=text]')
			var answer = f.find('textarea[name=answer]')

			if(text == '')
				error('Введите текст!')
			else
			{
				$.ajax({
					url: '/suggestions/update',
					data: 'id='+id+'&text='+encodeURIComponent(text.val())+'&answer='+encodeURIComponent(answer.val())+'&name='+encodeURIComponent(name.val())+'&email='+encodeURIComponent(email.val())+'&phone='+encodeURIComponent(phone.val())+'',
					dataType: 'json',
					beforeSend: function(){$.fancybox.showLoading()},
					success: function(data){
						//alert('ok')
						if(data.errors==null){
							error('Сохранено')
							setTimeout('location.reload()', 500)
						}
						else{
							showSuggestionErrors(data.errors)
						}
					},
					complete: function(){$.fancybox.hideLoading()},
					error: function(){window.top.error('Возникла ошибка на сервере...попробуйте позже.')},
				});
			}
		}


		function suggestionSetStatus(id, status)
		{
			//alert(123)
			$.ajax({
				url: '/suggestions/setStatus',
				data: 'id='+id+'&status='+status+'',
				dataType: 'json',
				beforeSend: function(){$.fancybox.showLoading()},
				success: function(data){
					//alert('ok')
					if(data.errors==null){
						error('Сохранено')
						setTimeout('location.reload()', 300)
					}
					else{
						showSuggestionErrors(data.errors)
					}
				},
				complete: function(){$.fancybox.hideLoading()},
				error: function(){window.top.error('Возникла ошибка на сервере...попробуйте позже.')},
			});
		}


<?php 	
}?>


</script>
<?php $CONTENT->sectionHeader .= ob_get_clean()?>







<!--крамбсы-->
<? Core::renderPartial(SHARED_VIEWS_DIR.'/crumbs.php', $MODEL['crumbs']);?>
<!--//крамбсы-->






<div class="switch-btns-wrapper on-advs-list" >
<?php
foreach($suggestionTypes as $type)
{?>
	<a href="?type=<?=$type->code?>" class="<?=$type->code == $suggestionType->code?'active':''?>"><?=$type->namePlural?></a>
<?php 	
}?>
</div>


<?php 
if($isAdmin)
{
	$statuses = array(
				Status::code(Status::ACTIVE),
				Status::code(Status::MODERATION),	
	);
	?>
	<div class="admin-panel admin">
		<?php 
		foreach($statuses as $s)
		{?>
			&nbsp;&nbsp;&nbsp;<a href="?type=<?=$suggestionType->code?>&status=<?=$s->code?>" style="<?=$status->code==$s->code?"font-weight: bold; ":""?>; "><?=$s->name?></a>
		<?php 	
		}?>
	</div>
<?php 
}?>


<h1><?=$suggestionType->namePlural?></h1>




<div class="suggestions">
<?php
if(count($items))
{?>
	<?php
	foreach($items as $key=>$item)
	{?>
	<div class="item suggestion-status-<?=$item->status->code?>">
		
		<span class="suggestion-status suggestion-status-label-<?=Status::MODERATION?>">МОДЕРАЦИЯ</span>
		
		<span class="date"><?=Funx::mkDate($item->dateCreated, 'with_time_without_seconds')?></span>
		<span class="name"><?=$item->name ? $item->name : 'Анонимно'?></span>
		<?php 
		if($item->email)
		{?>
			&nbsp;<span class="email">(<a href="mailto:<?=$item->email?>"><?=$item->email?></a>)</span>
		<?php 	
		}?>
		
		
		
		
		
		<div class="text"><?=$suggestionType->icon?> <?=$item->text?></div>
		<?php 
		if($item->answer)
		{?>
		<div class="answer"><span class="answer-label">Ответ: </span><?=$item->answer?></div>
		<?php 	
		}?>
		
		<?php 
		if($isAdmin )
		{?>
		<div class="admin">
			<span style="font-size: 11px; font-weight: bold; ">id: <?=$item->id?> | &nbsp;</span>
			<a href="#edit_suggestion" onclick="suggestionEdit(<?=$item->id?>); return false; " style="font-size: 11px; ">редактировать</a>
			<?php 
			if($item->status->code != Status::ACTIVE)
			{?>
			&nbsp;&nbsp;<a href="#approve_suggestion" onclick="if(confirm('Одобрить?')){suggestionSetStatus(<?=$item->id?>, '<?=Status::ACTIVE?>')}; return false; " style="font-size: 11px; ">одобрить</a>
			<?php 
			}?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#delete_suggestion" onclick="if(confirm('Удалить?')){suggestionSetStatus(<?=$item->id?>, '<?=Status::DELETED?>')}; return false; " style="color: red; font-size: 11px; ">&times; удалить</a>
		</div>
		<?php 	
		}?>
	</div>	
	
	<?php
	//vd($MODEL['p']);
	}?>
	<div ><?=Funx::drawPages($MODEL['totalCount'], $MODEL['p']-1, $MODEL['elPP']);?></div>
<?php 	
}
else
{?>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Пока ничего нет.
<?php 	
}?>
</div>






<?php
$btnTitle = 'Задать вопрос';
$heading = 'Ваш вопрос';
$success1 = 'Ваш вопрос отправлен!';
$success2 = 'После модерации он появится на сайте.';
if($suggestionType->code == SuggestionType::SUGGESTION)
{
	$btnTitle = 'Отправить предложение';
	$heading = 'Ваше предложение';
	$success1 = 'Ваше предложение отправлено!';
	$success2 = 'После модерации оно появится на сайте.';
}
?>
<hr style="margin: 20px 0  17px 0;" />

	<a href="#add_suggestion" class="btn-square" onclick="$(this).slideUp('fast'); $('#add-suggestion-form').slideDown('fast'); return false; "><?=$btnTitle?></a>
	<form id="add-suggestion-form" style="display: none; " action="" onsubmit="addSuggestion(); return false; ">
		<div class="row">
			<div class="label">Имя: </div>
			<div class="value"><input type="text" id="name" /></div>
		</div>
		<div class="row">
			<div class="label">E-mail: </div>
			<div class="value"><input type="text" id="email" /></div>
		</div>
		<div class="row">
			<div class="label">Телефон: </div>
			<div class="value"><input type="text" id="phone" /></div>
		</div>
		
		<h3 style="margin: 0; "><?=$heading?><span class="req">*</span>:</h3>
		<textarea id="text" style="width: 300px; height: 70px; " ></textarea>
		<table border="0">
			<tr>
				<td width="1" valign="top">
					<img src="/<?=INCLUDE_DIR?>/kcaptcha/?<?=session_name()?>=<?=session_id()?>" id="captcha-pic">
					
				</td>
				<td valign="top" style="font-size: 11px; ">
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
		<h3><?=$success1?></h4>
		<h4><?=$success2?></h5>
	</div>



<!--форма редактирования-->
<div id="float"  style="display: none; min-width: 700px; max-width: 700px;"></div>





