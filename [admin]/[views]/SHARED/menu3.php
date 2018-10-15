<?php

//$uri = $_SERVER['REQUEST_URI'];
$uri = $_SERVER['PATH_INFO'];
//vd($uri);
//vd($_SERVER);
$section = '';
$subsection = '';

if(		strpos($uri, '/'.ADMIN_URL_SIGN.'/entity/showList/pages') === 0)
{
	$section = 'content';
	$subsection = 'pages';
}
elseif(strpos($uri, '/'.ADMIN_URL_SIGN.'/entity/showList/services') === 0)
{
	$section = 'services';
//	$subsection = 'news';
}

elseif(strpos($uri, '/'.ADMIN_URL_SIGN.'/adminGroup') === 0)
{
	$section = 'system';
	$subsection = 'adminGroup';
}

elseif(strpos($uri, '/'.ADMIN_URL_SIGN.'/admin') === 0 )
{
	$section = 'system';
	$subsection = 'admin';
}

elseif(strpos($uri, '/'.ADMIN_URL_SIGN.'/settings') === 0 )
{
	$section = 'system';
	$subsection = 'settings';
}


elseif(strpos($uri, '/'.ADMIN_URL_SIGN.'/tasks') === 0 )
	$section = 'tasks';

elseif(strpos($uri, '/'.ADMIN_URL_SIGN.'/clients') === 0 )
	$section = 'clients';

elseif(strpos($uri, '/'.ADMIN_URL_SIGN.'/schedule') === 0 )
	$section = 'schedule';


# 	ЭТО ДОЛЖНО БЫТЬ В САМОМ КОНЦЕ!
elseif(strpos($uri, '/'.ADMIN_URL_SIGN) === 0)
	$section = 'index';	
	
	
	
//vd($subsection);
?>

<div class="top-menu-wrapper">
	<div id="menu">
		<ul class="primary">
<!--			<li><a class="--><?//=$section=='index' ? 'active' : ''?><!--" href="/--><?//=ADMIN_URL_SIGN?><!--/"><i class="fa fa-pagelines"></i> Главная</a></li>-->


<!--            <li>-->
<!--                <a class="--><?//=$section=='content' ? 'active' : ''?><!--" href="#"><i class="fa  fa-book" aria-hidden="true"></i> Контент</a>-->
<!--                <ul>-->
<!--                    <li class="--><?//=$subsection=='pages' ? 'active' : ''?><!--"><a  href="/--><?//=ADMIN_URL_SIGN?><!--/entity/showList/pages/"><i class="fa fa-sitemap"></i> Разделы</a></li>-->
<!--                    <li class="--><?//=$subsection=='news' ? 'active' : ''?><!--"><a  href="/--><?//=ADMIN_URL_SIGN?><!--/entity/showList/news/"><i class="fa fa-newspaper-o"></i> Новости</a></li>-->
<!--                </ul>-->
<!--            </li>-->


			<?
			if($ADMIN->hasRole(Role::SUPER_ADMIN | Role::SYSTEM_ADMINISTRATOR) || 1) ////!!!!
			{?>
                <li><a class="<?=$section=='schedule' ? 'active' : ''?>" href="/<?=ADMIN_URL_SIGN?>/schedule"><i class="fa fa-user"></i> Журнал</a></li>
				<?
			}?>

			<?
			if($ADMIN->hasRole(Role::SUPER_ADMIN | Role::SYSTEM_ADMINISTRATOR))
			{?>
                <li><a class="<?=$section=='clients' ? 'active' : ''?>" href="/<?=ADMIN_URL_SIGN?>/clients"><i class="fa fa-user"></i> Клиенты</a></li>
				<?
			}?>



			<?
			if($ADMIN->hasRole(Role::SUPER_ADMIN | Role::SYSTEM_ADMINISTRATOR))
			{
			    ?>
                <li><a class="<?=$section=='services' ? 'active' : ''?>" href="/<?=ADMIN_URL_SIGN?>/entity/showList/services/"><i class="fa fa-pagelines"></i> Услуги</a></li>
				<?
			}?>


			<?
			if($ADMIN->hasRole(Role::SUPER_ADMIN | Role::SYSTEM_ADMINISTRATOR))
			{?>
                <li><a class="<?=$subsection=='admin' ? 'active' : ''?>" href="/<?=ADMIN_URL_SIGN?>/admin"><i class="fa fa-user"></i> Врачи</a></li>
				<?
			}?>

			
			<?php 
			if($ADMIN->hasRole(Role::SUPER_ADMIN | Role::SYSTEM_ADMINISTRATOR | Role::ADMIN_GROUPS_MODERATOR | Role::ADMINS_MODERATOR)  && !$ADMIN->isOperator() )
			{?>
			<li>
				<a class="<?=$section=='system' ? 'active' : ''?>" href="#"><i class="fa fa-cubes"></i> Системные</a>
				<ul>
					<!-- <li><a class="" href="/admin/module/"><i class="fa fa-cogs"></i> Модули</a></li> -->
					<?php 
					if($ADMIN->hasRole(Role::SUPER_ADMIN ))
					{?>
					<li class="<?=$subsection=='essence' ? 'active' : ''?>"><a href="/<?=ADMIN_URL_SIGN?>/essence/"><i class="fa fa-puzzle-piece"></i> Сущности</a></li>
					<li class="delimiter"><hr /></li>
					<?php 
					}?>
					
					
					
					<?php 
					if($ADMIN->hasRole(Role::ADMINS_MODERATOR ))
					{?>
					<li class="<?=$subsection=='admin' ? 'active' : ''?>"><a href="/<?=ADMIN_URL_SIGN?>/admin/"><i class="fa fa-user "></i> Администраторы</a></li>
					<?php 
					}?>
					
					<?php 
					if($ADMIN->hasRole(Role::ADMIN_GROUPS_MODERATOR))
					{?>
					<li class="<?=$subsection=='adminGroup' ? 'active' : ''?>"><a href="/<?=ADMIN_URL_SIGN?>/adminGroup/"><i class="fa fa-users "></i> Группы админов</a></li>
					<?php 
					}?>
					<li class="delimiter"><hr /></li>
					
<!--					--><?php //
//					if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR ))
//					{?>
<!--					<li class="--><?//=$subsection=='settings' ? 'active' : ''?><!--" ><a href="/--><?//=ADMIN_URL_SIGN?><!--/settings/"><i class="fa fa-sliders"></i> Настройки сайта</a></li>-->
<!--					<li class="--><?//=$subsection=='backup' ? 'active' : ''?><!--"><a href="/--><?//=ADMIN_URL_SIGN?><!--/backup/"><i class="fa fa-database"></i> Бэкап базы</a></li>-->
<!--                    <li class="delimiter"><hr /></li>-->
<!--					--><?php //
//					}?>


                    <?php
                    if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR ) || $ADMIN->hasRole(Role::ADMINS_MODERATOR ) && !$ADMIN->isOperator())
                    {?>

                        <li class="<?=$subsection=='adminActivity' ? 'active' : ''?>"><a href="/<?=ADMIN_URL_SIGN?>/adminActivity/"><i class="fa fa-user "></i> Активность админов</a></li>
                        <?php
                    }?>


				</ul>
			</li>
			<?php 
			}?>
			
			
			

			
		</ul>
	</div>
	<a href="#logout" class="exit2" onclick="if(confirm('Выйти из системы?')){logout(); return false;} else{return false} "><img src="/<?=ADMIN_DIR?>/img/exit.png" height="24" style="vertical-align: middle; " alt="" /><!-- <i class="fa fa-road"></i> -->Выйти</a>
</div>

