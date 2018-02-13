/*Slonne.Admins = {
		
		URL_SECTION : 'admin',
		
		
		list : function()
		{
			$('.admins .loading').css('visibility', 'visible')
			$('.admins .inner').css('opacity', '.3')
			
			$.ajax({
				url: '/'+Slonne.ADMIN_URL_SIGN+'/'+this.URL_SECTION+'/list/',
				data: '',
				success: function(data){
					$('.admins .inner').html(data)
				},
				error: function(){alert('Возникла ошибка...Попробуйте позже!')},
				complete: function(){
					$('.admins .loading').css('visibility', 'hidden')
					$('.admins .inner').css('opacity', '1')
				}
			});
		},
		
		
		
		
		edit : function(id)
		{
			if(typeof id == 'undefined') id = ''
			
			$.fancybox.showLoading()
			$.ajax({
				url: '/'+Slonne.ADMIN_URL_SIGN+'/'+this.URL_SECTION+'/edit/',
				data: 'id='+id,
				success: function(data){
					$('#float').html(data)
					$.fancybox('#float');
				},
				error: function(){alert('Возникла ошибка...Попробуйте позже!')},
				complete: function(){}
			});
		},
		
		
		
		
		editSubmitStart : function()
		{
			$('#edit-form input').removeClass('field-error')
			$('#edit-form .loading').css('display', 'block')
			$('#edit-form .info').html('')
		}, 
		editSubmitComplete : function()
		{
			$('#edit-form .loading').css('display', 'none')
		},
		
		
		
		
		delete : function(id)
		{
			if(!confirm("Уверены?"))
				return
			
			$('.admins .loading').css('visibility', 'visible')
			$('.admins .inner').css('opacity', '.3')
			
			$.ajax({
				url: '/'+Slonne.ADMIN_URL_SIGN+'/'+this.URL_SECTION+'/delete/',
				data: 'id='+id,
				dataType: 'json',
				success: function(data){
					if(data.error == '')
					{
						//$('#row-'+id).remove()
						$('#row-'+id).fadeOut()
						notice('Удалено!')
					}
					else
						error(data.error)
				},
				error: function(){alert('Возникла ошибка...Попробуйте позже!')},
				complete: function(){
					$('.admins .loading').css('visibility', 'hidden')
					$('.admins .inner').css('opacity', '1')
				}
			});	
		},
		
		
		
		listSubmitStart : function()
		{
			$('.admins .loading').css('visibility', 'visible')
			$('.admins .inner').css('opacity', '.3')
		},
		listSubmitComplete : function()
		{
			$('.admins .loading').css('visibility', 'hidden')
			$('.admins .inner').css('opacity', '1')
			Slonne.Admins.list()
		},
		
		
		auth : function()
		{
			var email = $('#auth-form input[name=email]').val()
			var password = $('#auth-form input[name=password]').val()

			var errors = []
			if(email == '' )
				errors.push({field:'email'}) 
			if(password == '' )
				errors.push({field:'password'}) 
			
			if(errors.length > 0)
			{
				for(var i in errors)
				{
					highlight('auth-form input[name='+errors[i].field+']')
				}
				error('Пожалуйста, заполните все поля! ')
				return;
			}
			$.ajax({
				url: '/'+Slonne.ADMIN_URL_SIGN+'/auth/auth',
				data: 'email='+email+'&password='+password,
				dataType: 'json',
				beforeSend: function(){
					$('#auth-form .loading').css('display', 'block');
				},
				success: function(data){
					if(data.error == '')
					{
						//alert('ew')
						if(data.result == 'ok')
						{
							notice('Ок!')
							highlight('auth-form input[name=email]', true)
							highlight('auth-form input[name=password]', true)
							location.href='/'+Slonne.ADMIN_URL_SIGN
						}
						
						if(data.result == 'not_found')
						{
							highlight('auth-form input[name=email]')
							highlight('auth-form input[name=password]')
							error('Неверный e-mail / пароль!')
						}
						
						if(data.result == 'tries_limit')
						{
							highlight('auth-form input[name=email]')
							highlight('auth-form input[name=password]')
							error('Превышен лимит попыток! Повторите через <b>'+data.delay+'</b>')
						}
						
						
					}
					else
						error(data.error)
				},
				error: function(){alert('Возникла ошибка...Попробуйте позже!')},
				complete: function(){
					$('#auth-form .loading').slideUp('fast');
				}
			});	
		},
		
		
		
		
		
		logout: function()
		{
			$.ajax({
				url: '/'+Slonne.ADMIN_URL_SIGN+'/auth/logout/',
				data: '',
				dataType: 'html',
				success: function(data){
					location.href = '/'+Slonne.ADMIN_URL_SIGN+'/auth'
				},
				error: function(){alert('Возникла ошибка...Попробуйте позже!')},
				complete: function(){}
			});	
		}
		
		
		
} */