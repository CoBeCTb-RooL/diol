<?php
$item = $MODEL['admin'];
$groups = $MODEL['groups'];

if(!$item)	
{
	$titlePrefix = 'Администратор';
	$titlePostfix = ' : добавление';
}
else
{
	$titlePrefix = $item->name;
	$titlePostfix = ' : редактирование';
}
?>


<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>

<?php
if($item || 1)
{?>
	<div class="view" >
		<form id="edit-form" method="post" action="/<?=ADMIN_URL_SIGN?>/admin/editSubmit2" target="frame7" onsubmit="adminsEditSubmitStart();" >	
			<input type="hidden" name="id" value="<?=$item->id?>">
				<h1><?=$titlePrefix?><span class="title-gray"><?=$titlePostfix?></span></h1>
					
				
					<div class="field-wrapper">
						<span class="label">ФИО<span class="required">*</span>: </span>
						<span class="value">
							<input type="text" name="name" value="<?=htmlspecialchars($item->name)?>">
						</span>
						<div class="clear"></div>
					</div>
					
					<div class="field-wrapper">
						<span class="label">E-mail<span class="required">*</span>: </span>
						<span class="value">
							<input type="text" name="email" value="<?=htmlspecialchars($item->email)?>">
						</span>
						<div class="clear"></div>
					</div>
					
					
					<div class="field-wrapper">
						<span class="label">Группа<span class="required">*</span>: </span>
						<span class="value">
							<select name="groupId">
								<option value="">-выберите группу-</option>
								<?
								foreach($groups as $key=>$group)
								{?>
								<option value="<?=$group->id?>" <?=($group->id == $item->groupId ? ' selected="selected" ' : '')?>    class="group-status-<?=$group->status->code?>"><?=$group->name?> <span class="hint">(<?=$group->status->name?>)</span></option>
								<?php 
								}?>
							</select>
						</span>
						<div class="clear"></div>
					</div>
					
					
					
					<hr>
					
					
					<div class="field-wrapper">
						<span class="label">Пароль: </span>
						<span class="value">
							<input type="password" name="password" autocomplete="off">
						</span>
						<div class="clear"></div>
						<br>
						<span class="label">Ещё раз: </span>
						<span class="value">
							<input type="password" name="password2" autocomplete="off">
						</span>
						<div class="clear"></div>
					</div>
					
					
				
				
			
			<input type="submit" value="Сохранить">
				
			<div class="loading" style="display: none;">Секунду...</div>
			<div class="info"></div>
		</form>
	</div>
	
<?php 	
}
else 
{
	echo 'Админ не найден! ['.$_REQUEST['id'].']';
}
?>

<iframe name="frame7" style="display: none; width: 98%; border: 1px dashed #0e0e0e; height: 400px;">1</iframe>