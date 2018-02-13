<?php
$MODEL['questionsInModeration']=0;
?>


<style>
h1, h2, h3{margin: 5px 0; }
.section{font-size: 11px; margin: 20px; }
.inner{padding: 0 0 0 20px; }
</style>


<div class="index">
	Welcome to SLoNNE CMS! Fast, easy, no excess! 
	<p>

	
	
	<!-- объявления-->
	<div class="section">
		<h3>Новые объявления</h3>
		<div class="inner">
			<a href="/<?=ADMIN_URL_SIGN?>/adv/items/itemsList?status=<?=Status::code(Status::MODERATION)->num?>" target="_blank">Новые объявления: <?=$MODEL['advsCount'] ? '<span style="font-size: 19px; font-weight: bold; ">'.$MODEL['advsCount'].'</span>' : 'нет'?></a>
		</div>
	</div>
	
	
	
	<!-- вопросы, предложения -->
	<div class="section">
		<h3>Новые вопросы, предложения</h3>
		<div class="inner">
			<a href="/suggestions/?type=<?=SuggestionType::QUESTION?>&status=<?=Status::MODERATION?>" target="_blank">Новые ВОПРОСЫ: <?=$MODEL['questionsInModeration'] ? '<span style="font-size: 19px; font-weight: bold; ">'.$MODEL['questionsInModeration'].'</span>' : 'нет'?></a>
			<br />
			<a href="/suggestions/?type=<?=SuggestionType::SUGGESTION?>&status=<?=Status::MODERATION?>" target="_blank">Новые ПРЕДЛОЖЕНИЯ: <?=$MODEL['suggestionsInModeration'] ? '<span style="font-size: 19px; font-weight: bold; ">'.$MODEL['suggestionsInModeration'].'</span>' : 'нет'?></a>
		</div>
	</div>
	
	
	
	
	<!-- комменты -->
	<div class="section">
		<h3>Новые комментарии</h3>
		<div class="inner">
			<a href="/<?=ADMIN_URL_SIGN?>/comment/" target="_blank">Новые комментарии: <?=$MODEL['commentsCount'] ? '<span style="font-size: 19px; font-weight: bold; ">'.$MODEL['commentsCount'].'</span>' : 'нет'?></a>
		</div>
	</div>
	
	
	

</div>



