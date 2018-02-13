Slonne.Essences = {
		
		URL_SECTION : 'essence',
		
		
		/*list : function()
		{
			$('.essences .loading').css('visibility', 'visible')
			$('.essences .inner').css('opacity', '.3')
			
			$.ajax({
				url: '/'+Slonne.ADMIN_URL_SIGN+'/'+this.URL_SECTION+'/list/',
				data: '',
				success: function(data){
					$('.essences .inner').html(data)
				},
				error: function(){alert('Возникла ошибка...Попробуйте позже!')},
				complete: function(){
					$('.essences .loading').css('visibility', 'hidden')
					$('.essences .inner').css('opacity', '1')
				}
			});
		},*/
		
		
		
		
		/*edit : function(id)
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
		},*/
		
		
		
		
		/*editSubmitStart : function()
		{
			$('#edit-form input').removeClass('field-error')
			$('#edit-form .loading').css('display', 'block')
			$('#edit-form .info').html('')
		}, 
		editSubmitComplete : function()
		{
			$('#edit-form .loading').css('display', 'none')
		},*/
		
		
		
		
		/*delete : function(id)
		{
			if(!confirm("Уверены?"))
				return
			
			$('.essences .loading').css('visibility', 'visible')
			$('.essences .inner').css('opacity', '.3')
			
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
					$('.essences .loading').css('visibility', 'hidden')
					$('.essences .inner').css('opacity', '1')
				}
			});	
		},*/
		
		
		
		listSubmitStart : function()
		{
			$('.essences .loading').css('visibility', 'visible')
			$('.essences .inner').css('opacity', '.3')
		},
		listSubmitComplete : function()
		{
			$('.essences .loading').css('visibility', 'hidden')
			$('.essences .inner').css('opacity', '1')
			Slonne.Essences.list()
		}
		
		
} 