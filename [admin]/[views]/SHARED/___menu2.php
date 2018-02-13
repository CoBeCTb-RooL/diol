<?php
$menu = $MODEL['menu'];

?>




<div class="top-menu-wrapper">
	<div id="menu">
		<ul class="primary" >
	<?php
	foreach($menu as $key=>$val)
	{
		$module = $val['item'];
		if($_GLOBALS['ADMIN']->hasPrivilege($module->id) || 1)
		{?>
			<li>
				<a href="/<?=ADMIN_URL_SIGN?>/<?=$module->path?>" class="<?=($module==$_GLOBALS['CURRENT_MODULE'] ? 'active' : '')?>"><?=($module->icon ? $module->icon : '')?> <?=$module->name?></a>
				<?php
				if($val['subs'])
				{?>
					<ul>
					<?foreach($val['subs'] as $key2=>$val2)
					{
						if($_GLOBALS['ADMIN']->hasPrivilege($val2->id) || 1)
						{?>
							<li><a href="/<?=ADMIN_URL_SIGN?>/<?=$val2->path?>" class="<?=($val2==$_GLOBALS['CURRENT_MODULE'] ? 'active' : '')?>"><?=($val2->icon ? $val2->icon : '')?> <?=$val2->name?></a></li>
						<?php 
						}?>
					<?php 
					}?>
					</ul>
				<?php 	
				} 
				?>
			</li>
		<?php 
		}
		else
		{
			continue; ?>
			<a href="/<?=ADMIN_URL_SIGN?>/<?=$module->path?>" class="inactive <?=($module==$_GLOBALS['CURRENT_MODULE'] ? 'active' : '')?>"><?=($module->icon ? $module->icon : '')?> <?=$module->name?></a>
		<?php 	
		} 	
	} 
	?>
		</ul>
	</div>
	
	<a href="#logout" class="exit2" onclick="if(confirm('Выйти из системы?')){logout(); return false;} else{return false} "><img src="/<?=ADMIN_DIR?>/img/exit.png" height="24" style="vertical-align: middle; " alt="" /><!-- <span class="fa fa-road"></span> -->Выйти</a>
</div>