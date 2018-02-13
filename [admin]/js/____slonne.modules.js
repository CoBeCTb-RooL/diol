Slonne.Modules = {
		
		URL_SECTION : 'module',
		
		list : function()
		{
			$('.modules .loading').css('visibility', 'visible')
			$('.modules .inner').css('opacity', '.3')
			
			$.ajax({
				url: '/'+Slonne.ADMIN_URL_SIGN+'/'+this.URL_SECTION+'/list/',
				data: '',
				success: function(data){
					$('.modules .inner').html(data)
				},
				error: function(){alert('Возникла ошибка...Попробуйте позже!')},
				complete: function(){
					$('.modules .loading').css('visibility', 'hidden')
					$('.modules .inner').css('opacity', '1')
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
			
			$('.modules .loading').css('visibility', 'visible')
			$('.modules .inner').css('opacity', '.3')
			
			$.ajax({
				url: '/'+Slonne.ADMIN_URL_SIGN+'/'+this.URL_SECTION+'/delete/',
				data: 'id='+id,
				dataType: 'json',
				success: function(data){
					if(data.error == '')
					{
						//$('#row-'+id).remove()
						$('#row-'+id).fadeOut()
					}
					else
						error(data.error)
				},
				error: function(){alert('Возникла ошибка...Попробуйте позже!')},
				complete: function(){
					$('.modules .loading').css('visibility', 'hidden')
					$('.modules .inner').css('opacity', '1')
				}
			});	
		},
		
		
		
		listSubmitStart : function()
		{
			$('.modules .loading').css('visibility', 'visible')
			$('.modules .inner').css('opacity', '.3')
		},
		listSubmitComplete : function()
		{
			$('.modules .loading').css('visibility', 'hidden')
			$('.modules .inner').css('opacity', '1')
			Slonne.Modules.list()
		}
		
		
} 