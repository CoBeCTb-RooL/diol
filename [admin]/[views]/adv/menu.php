<?php
$menu = array(
			
		
		
		array(
				'groupName'=>'kj2',
				'items'=>array(
						array('url'=>'/'.ADMIN_URL_SIGN.'/categories/', 'title'=>'<i class="fa fa-tasks"></i> Категории '),
						
						array('url'=>'/'.ADMIN_URL_SIGN.'/props/', 'title'=>'<i class="fa fa-cube"></i> Свойства'),
						array('url'=>'/'.ADMIN_URL_SIGN.'/classes/', 'title'=>'<i class="fa fa-cubes"></i> Классы'),
						array('url'=>'/'.ADMIN_URL_SIGN.'/product_volume_unit/', 'title'=>'<i class="fa fa-balance-scale"></i> Единицы объёма товара'),
		
				),
		),
		
		array(
			'groupName'=>'rty',
			'items'=>array(
					array('url'=>'/'.ADMIN_URL_SIGN.'/adv/brands', 'title'=>'<i class="fa fa-cc-stripe"></i> Бренды'),
					array('url'=>'/'.ADMIN_URL_SIGN.'/adv/article_numbers', 'title'=>'<i class="fa fa-list-ol"></i> Артикульные номера'),
					array('url'=>'/'.ADMIN_URL_SIGN.'/adv/cat_brand_combine', 'title'=>'Категория + Бренд'),
					array('url'=>'/'.ADMIN_URL_SIGN.'/adv/brand_artnum_combine', 'title'=>'Бренд + Арт. номер'),
					array('url'=>'/'.ADMIN_URL_SIGN.'/adv/cat_brand_artnum_combine', 'title'=>'Категория + Бренд + Арт. номер'),
			),
		),
		
		
		
		
		array(
				'groupName'=>'kj',
				'items'=>array(
						array('url'=>'/'.ADMIN_URL_SIGN.'/adv/items/itemsList?1', 'title'=>'<i class="fa fa-list-alt"></i> Объявления'),
						array('url'=>'/'.ADMIN_URL_SIGN.'/adv/items/itemsList?status='.Status::code(Status::MODERATION)->num.'', 'title'=>'<i class="fa fa-clock-o"></i> Премодерация'),
		
				),
		),
		
		
		array(
				'groupName'=>'фыв',
				'dim'=>'0',
				'items'=>array(
			
						array('url'=>'/'.ADMIN_URL_SIGN.'/user/', 'title'=>'<span class="fa fa-users "></span> Пользователи'),
						array('url'=>'/'.ADMIN_URL_SIGN.'/comment/', 'title'=>'<i class="fa fa-comments"></i> Комментарии'),
						array('url'=>'/'.ADMIN_URL_SIGN.'/city/', 'title'=>'<i class="fa fa-globe"></i> Города'),
						
				),
		),
		
		array(
				'groupName'=>'фыв',
				'dim'=>'0',
				'items'=>array(
						array('url'=>'/'.ADMIN_URL_SIGN.'/tools/', 'title'=>'<i class="fa fa-cogs"></i> Служебные'),
						
				),
		),
		
		/*array(
			'groupName'=>'фыв',
			'dim'=>'1',
			'items'=>array(
					array('url'=>'/'.ADMIN_URL_SIGN.'/catalog/interface?type=adv', 'title'=>'Каталог:ОБЪЯВЛЕНИЯ'),
					array('url'=>'/'.ADMIN_URL_SIGN.'/catalog/interface?type=products', 'title'=>'Каталог:ТОВАРЫ'),
			),
		),
		array(
			'groupName'=>'qwe',
			'dim'=>'1',
			'items'=>array(
					array('url'=>'/'.ADMIN_URL_SIGN.'/catalog/types', 'title'=>'Типы каталогов'),
					array('url'=>'/'.ADMIN_URL_SIGN.'/catalog/props', 'title'=>'Свойства'),
					array('url'=>'/'.ADMIN_URL_SIGN.'/catalog/classes', 'title'=>'Классы'),
			),
		),*/
			
); 
?>


<style>
.submenu{ margin: 0; padding: 0 0 0 20px; }
.submenu li{display: inline-block; list-style: none; vertical-align: top; margin: 0 30px 0 0 ;  }
.submenu li a{display: block; margin: 0 0 4px 0; font-size: 11px; text-decoration: none; }
.submenu li a.active{font-weight: bold; }
</style>




<ul class="submenu">
<?php 
foreach($menu as $menuGroup)
{?>
	<li <?=$menuGroup['dim'] ? 'style="opacity: .3; "' : ''?>>
	<?
	foreach($menuGroup['items'] as $item)
	{
		$active = strpos($_SERVER['REQUEST_URI'], $item['url'])!==false ;
		?>
		<a class="<?=$active ? 'active' : '' ?>" href="<?=$item['url']?>"><?=$item['title']?></a>
	<?php 
	}?>
	</li>
<?php 	
}?>
</ul>

<hr />

