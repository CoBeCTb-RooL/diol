<?php
$cats = $MODEL['cats']; 
$about = $MODEL['about'];
$dealTypes = $MODEL['dealTypes'];

$totalCount = $MODEL['totalCount'];
$totalCountCurrentCity = $MODEL['totalCountCurrentCity'];

$lastAdvs = $MODEL['lastAdvs'];
?>



<div class="index">
	
	<div class="top-pic-wrapper">
		<img src="/img/index-pic.jpg" alt="" />
	</div>
	
	<div class="middle">
		
		<!--//слева-->
		<div class="col left">
			
			<div class="cats">
				<h3>Объявления</h3>
				<ul>
				<?php 
				foreach($cats as $cat)
				{?>
					<li>
						<!-- <a href="<?=$cat->url()?>"><?=$cat->name?></a> -->
						<?php 
						if(count($cat->subCats))
						{?>
							<h2><?=$cat->name?></h2>
							<ul class="subs">
							<?php 
							foreach($cat->subCats as $subcat)
							{?>
								<li>
									<a href="<?=$subcat->url()?>"><?=$subcat->name?>
									<?php 
									if($subcat->advsCount)
									{?>
										<sup><?=Funx::numberFormat($subcat->advsCount)?></sup>
									<?php 	
									}?>
									</a>
								</li>
							<?php 	
							}?>
							</ul>
						<?php 
						}
						else
						{?>	
							<h2>
								<a href="<?=$cat->url()?>">
									<?=$cat->name?>
									<?php 
									if($cat->advsCount)
									{?>
										<sup><?=Funx::numberFormat($cat->advsCount)?></sup>
									<?php 	
									}?>
								</a>
							</h2>
							
						<?php 	
						}?>
					</li>
				<?php 	
				}?>
				</ul>
			</div>
			
		</div>
		<!--//слева-->
		
		<!--main-->
		<div class="col main">
			<div class="about">
				<h3 class="about">О проекте</h3>
				<div class="txt">
					Проект <a href="<?=Route::getByName(Route::MAIN)->url()?>">OSTATKI.KZ</a> ориентирован на производителей мебели и наружной рекламы* -  словом, <b>для тех, кто при производстве своего продукта изпользует какие-либо материалы. </b>


					<p>
						<img src="/img/index/pic2.png" alt="" style="float: right; width: 180px; padding: 0 15px 15px 15px ; "/>
					Допустим, Вам нужно изготовить шкаф или лайтбокс. Материала для этого требуется немного, и покупать его за полную стоимость <b  class="red">не рационально</b>.
					<p>А представьте, что кто-то уже приобрёл материалы, которые Вы ищете, и их остатки лежат без надобности, и занимают место.
					<br>Или Вы - обладатель тех остатков, и желаете от них избавиться, а выкинуть  - жалко, да и расточительно...
					<p>
					<b>Тут пригодимся мы!</b>
					<p><b><a class="red" href="<?=Route::getByName(Route::MAIN)->url()?>">OSTATKI.KZ</a></b> -  это площадка для обмена информацией по остаткам материалов, сводящая вместе спрос и предложения. Размещая объявления о поиске или наличии остатков, Вы сможете значительно <b class="green">оптимизировать свой бизнес</b>.

					<div style="font-size: .8em; font-style: italic; "><p>* В перспективе проект <a href="<?=Route::getByName(Route::MAIN)->url()?>">OSTATKI.KZ</a> ставит задачу охватить более широкую область, относящеюся к теме остатков (строительство, торговля, сервис, и другие сферы производства).</div>

					<hr />

					<div style="font-size: 1.1em; line-height: 160%; ">
						Сейчас на сайте <b><span class="blue2" style="font-size: 18px; "><?=Funx::numberFormat($totalCount)?></span> <?=Funx::okon($totalCount, array('актуальных', 'актуальное', 'актуальных'))?> <?=Funx::okon($totalCount, array('объявлений', 'объявление', 'объявленя'))?></b><br>
						 <?
						 if($totalCountCurrentCity)
						 {?>
						 Из них - <a  href="<?=Route::getByName(Route::SPISOK_KATEGORIY)->url('?cityId='.$_GLOBALS['city']->id)?>"><b><?=Funx::numberFormat($totalCountCurrentCity)?> <?=Funx::okon($totalCountCurrentCity, array('объявлений', 'объявление', 'объявленя'))?></b> по <b >г. <?=$_GLOBALS['city']->name?></b></a>
						 <?
						 }
						 else
						 {?>
						 	К сожалению, для города <b ><?=$_GLOBALS['city']->name?></b> пока ничего нет.
						 <?php
						 }?>
						 <span class="change-city-btn" style="font-size: .7em; ">[<a href="#" onclick="$('#city-pick').slideDown();  "class="blue">сменить город</a>]</span>
					</div>
					<hr />

				</div>
			</div>
			
			
			
			<h3>Последние объявления</h3>
			
			
			<div class="last-advs">
			<?php
			foreach($lastAdvs as $item)
			{?>
				<a href="<?=$item->url()?>" class="item" style="background-image: url(<?=$item->media[0] ? $item->media[0]->resizeSrc(): AdvMedia::noPhotoSrc()?>&width=350); " title="<?=$item->name?>">
					<span class="date"><?=Funx::mkDate($item->dateCreated, 'with_time_without_seconds')?></span>
					<span class="title"><?=Funx::getShortStr($item->name, $maxLen=40)?></span>
				</a>
			<?php
			}?>
			</div>
			
			
			
			
			
			<!-- <div style="margin: 30px 0 0 0;">
			
			<?php 
			$typeBuy = DealType::code(DealType::BUY);
			$typeSell = DealType::code(DealType::SELL);
			?>
				<a href="<?=Route::getByName(Route::SPISOK_KATEGORIY)->url()?>?type=<?=DealType::code(DealType::BUY)->code?>" class="btn2" style="font-size: 20px;"><?=$typeBuy->icon?> <?=$typeBuy->titleInf?></a>
				или
				<a href="<?=Route::getByName(Route::SPISOK_KATEGORIY)->url()?>?type=<?=DealType::code(DealType::SELL)->code?>" class="btn2" style="font-size: 20px;"><?=$typeSell->icon?> <?=$typeSell->titleInf?></a>
			</div> -->
			
		</div>
		<!--//main-->
		
	</div>
	
</div>