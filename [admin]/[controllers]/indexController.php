<?php
class IndexController extends MainController{
	
	
	
	function index()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		
		# 	объявления
		$statuses = array(
				Status::code(Status::MODERATION),
		);
		$MODEL['advsCount'] = AdvItem::getCount($params=null, $statuses );
		
		
		# 	вопросы, предложения
		$params = array(
				'status' => Status::code(Status::MODERATION),
				'suggestionType' => SuggestionType::code(SuggestionType::QUESTION),
		);
		$MODEL['questionsInModeration'] = Suggestion::getCount($params);
		$params['suggestionType'] = SuggestionType::code(SuggestionType::SUGGESTION);
		$MODEL['suggestionsInModeration'] = Suggestion::getCount($params);
		//vd($tmp);
		
		
		# 	комменты
		$params = array(
				'status' => Status::code(Status::MODERATION),
		);
		$MODEL['commentsCount'] = Comment::getCount($params);
			
		
		
		
		Core::renderView('index/indexView.php', $MODEL);
	}
	
	
	
}




?>