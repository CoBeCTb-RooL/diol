var Cabinet = {
	
	
	
	checkAuthForm: function(formId)
	{
		//	зачистка эрроров формы
		$('#'+formId+' *').each(function(n,element){
			$(element).removeClass('error')
		});
		//$('#'+formId+' .info').html('').slideUp('fast')
		$('#'+formId+' .info').slideUp('fast')
		var email=$('#'+formId+' input[name="email"]').val()
		var pass=$('#'+formId+' input[name="password"]').val()
		
		var problems=[]
		
		var email=$('#'+formId+' input[name="email"]').val()
		var pass=$('#'+formId+' input[name="password"]').val()

		if(email == '')
			problems.push({field: 'email', error: 'Введите все данные!'})
		if(pass == '')
			problems.push({field: 'password', error: 'Введите все данные!'})
		
		
		if(problems.length!=0)
			this.showAuthErrors(formId, problems)
		else
		{
			$.ajax({
				url: '/cabinet/profile/authSubmit/?email='+email+'&password='+pass,
				dataType: 'json',
				beforeSend: function(){$('#'+formId+' .loading').slideDown('fast'); },
				success: function (data, textStatus){
					if(data.errors.length == 0)
					{
						
						$('#'+formId+' .info').html('Вы успешно авторизованы!').slideDown('fast');
						
						setTimeout(function(){
							//location.reload();
							location.href='/profile/'
						} , 400)
					}
					else
						Cabinet.showAuthErrors(formId, data.errors)
				},
				error: function (data, textStatus){showError('Ошибка сервера.. попробуйте позднее', ''+formId+' .info')},
				complete: function(){$('#'+formId+' .loading').slideUp('fast'); }
			});
		}
			
		return false; 
	}, 
	
	showAuthErrors: function(formId, errors)
	{
		for(var i in errors)
			$('#'+formId+' input[name="'+errors[i].field+'"]').addClass('error')
		
		$('#'+formId+' .info').html(errors[0].error).slideDown('fast')
	},
	
	
	logout: function()
	{
		$.get('/cabinet/profile/logout', function( data ) {
			location.reload(); 
		});
	}
	
	
	
	
	
}







