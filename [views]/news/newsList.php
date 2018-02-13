<?php
$news = $MODEL['news'];
$years = $MODEL['years'];
$chosenYear = $MODEL['chosenYear'];
$title = $MODEL['title'];
$crumbs = $MODEL['crumbs'];



$title = !$chosenYear ? $_CONST['ПОСЛЕДНИЕ НОВОСТИ'] : ''.$_CONST['НОВОСТИ ЗА'].' <b>'.$chosenYear.'</b> '.$_CONST['ГОД'].':';
?>



<!--крамбсы-->
<? Core::renderPartial(SHARED_VIEWS_DIR.'/crumbs.php', $crumbs);?>
<!--//крамбсы-->


<div class="news-section">
	
	
	
	<h1>Новости</h1>
	
	<?php
	if($MODEL['settings']['YEARS_NAVIGATION_IN_NEWS_LIST'])
	{?>
		<!--список годов-->
		<div class="years">
			<h3>Архив новостей</h3>
			<div class="years-wrapper">
			<?php
			foreach($years as $key=>$val) 
			{?>
				<a class="<?=($chosenYear == $val || (!intval($chosenYear) && $val == 'Все') ? "active" : "")?>" href="<?=Entity2::moduleUrl(News::ESSENCE)?>/year_<?=$val?>"><?=$val?></a>
			<?php 	
			}
			?>
			</div>
		</div>
		<!--//список годов-->
	<?php 
	} ?>
	
	<!--список  новостей-->
	<div class="news-wrapper">
		
		<!--<h3><?=$title?></h3>-->
		<?php 
		if(count($news))
		{?>
		<div class="news-list">
			<?php 
			foreach($news as $key=>$val)
			{
				$link = $val->url();
				?>
			<div class="item">
				<?
				if($val->attrs['pic'])
				{?>
					<a href="<?=$link?>"><img src="<?=Media::img($val->attrs['pic'].'&width=130')?>"></a>
				<?
				}?>
				<h2><a href="<?=$link?>"><?=$val->attrs['name']?></a></h2>
				<span class="anons"><?=$val->attrs['anons']?></span>
				
				<div class="clear"></div>
				<span class="date"><?=Funx::mkDate($val->attrs['dt'])?></span>
			</div>
			<?php 
			}
			?>
		</div>
		<?=Funx::drawPages($MODEL['totalElements'], $MODEL['page'], $MODEL['elPP']);?>
		<?php 
		}
		else
		{?>
			Новостей нет.
		<?php 	
		} ?>
		
		
		
		<div class="clear"></div>
	</div>
</div>
	<!--//список  новостей-->