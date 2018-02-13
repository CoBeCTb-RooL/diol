<?php
class Form
{
	function getEmails()
	{
		global $_CONFIG;
		return $_CONFIG['DEFAULT_DELIVERY_EMAILS'];
	}

	function errorStatic($formId, $errors)
	{
		/*$backtrace = debug_backtrace(); 
		vd($backtrace);*/
		//vd($errors);
		die(('
			<script>
				window.top.showError("'.$errors[0]['msg'].'", "'.$formId.' .info");
				window.top.loading(0, "'.$formId.' .loading", "fast");
				window.top.$("#'.$formId.' input, #'.$formId.' select").each(function(n,element){
					window.top.$(element).removeClass("error")
				});
				errors = '.json_encode($errors).';
				for(var i in errors)
				{
					window.top.$(\'#'.$formId.' input[name="\'+errors[i].name+\'"]\').addClass(\'error\');
				}
			</script>'));
	}
	
	
	function successStatic($formId)
	{
		echo '
		<script>
			window.top.$("#'.$formId.'-success").slideDown();
			window.top.$("#'.$formId.'").slideUp();
			
			window.top.loading(0, \''.$formId.' .loading\', \'fast\');
		</script>';
	}
	
	
	
	function sendEmails($emails, $subject, $msg)
	{
		if(!count($emails))
			echo '<script>alert("No eMaiLS iN CoNFiG.. =(")</script>';
		
		foreach($emails as $key=>$eml)
		{
			vd($eml);
			Funx::sendMail($eml, 'robot@'.$_SERVER['HTTP_HOST'], $subject, $msg.ReferalTail::info());
		}
	}
	
	
	
	
	function getErrors($fields)
	{
		foreach($fields as $key=>$val)
		{
			if(!mysql_real_escape_string(trim($_REQUEST[$val['name']])))
			{
				$errors[] = $val;
			}
		}
		return $errors;
	}
	
	
} 
?>