<?php
$item = $MODEL['group'];


//vd($item);


if(!$item)	
{
	$titlePrefix = 'Группа администраторов';
	$titlePostfix = ' : добавление';
}
else
{
	$titlePrefix = $item->name;
	$titlePostfix = ' : редактирование';
}
?>



<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>



<style>
	.role{margin: 0 0 4px 0; }
</style>


<?php
if($item || 1)
{?>
	<div class="view" >
		<form id="edit-form" method="post" action="/<?=ADMIN_URL_SIGN?>/adminGroup/editSubmit2" target="frame7" onsubmit="groupsEditSubmitStart();" >	
			<input type="hidden" name="id" value="<?=$item->id?>">
				<h1><?=$titlePrefix?><span class="title-gray"><?=$titlePostfix?></span></h1>
					
				
					<div class="field-wrapper">
						<span class="label">Название<span class="required">*</span>: </span>
						<span class="value">
							<input type="text" name="name" value="<?=htmlspecialchars($item->name)?>">
						</span>
						<div class="clear"></div>
					</div>
					
					
					<?php 
					if($item || 1)
					{?>
						<div class="field-wrapper">
							<span class="label">Роли: </span>
							<span class="value">
								<?php
								//vd($item);
								foreach(Role::$items as $key=>$r)
								{
									# 	сверх-админов могут выставлять только сверх-админы
									if(!($r->num==Role::SUPER_ADMIN && !$ADMIN->hasRole(Role::SUPER_ADMIN)))
									{?>
									<div class="role" >
										<label><input type="checkbox" name="role[<?=$r->num?>]" <?=($item->role & $r->num ? '  checked="checked" ' : '')?> /> <?=$r->name?></label> 
									</div>
									<?php
									}
								}
								?>
							</span>
							<div class="clear"></div>
						</div>
					<?php 	
					}?>
					
					
			
			<input type="submit" value="Сохранить">
				
			<div class="loading" style="display: none;">Секунду...</div>
			<div class="info"></div>
		</form>
	</div>
	
<?php 	
}
else 
{
	echo 'Группа не найдена! ['.$_REQUEST['id'].']';
}
?>


<iframe name="frame7" style="display: none; width: 98%; border: 1px dashed #0e0e0e; height: 400px;">1</iframe>	
