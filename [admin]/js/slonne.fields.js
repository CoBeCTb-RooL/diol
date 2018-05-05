Slonne.Fields = {
		
		URL_SECTION : 'field',
		CURRENT_TYPE : '',	//	здесь выбранный тип
		CURRENT_ESSENCE : '',	//	здесь выбранная сущность
		
		list : function(pid, type)
		{
			this.CURRENT_TYPE = type;
			this.CURRENT_ESSENCE = pid;
			
			$('.fields .loading').css('visibility', 'visible')
			$('.fields .inner').css('opacity', '.3')
			
			$.ajax({
				url: '/'+Slonne.ADMIN_URL_SIGN+'/'+this.URL_SECTION+'/list/',
				data: 'pid='+pid+'&type='+this.CURRENT_TYPE,
				success: function(data){
					$('.fields .inner').html(data)
				},
				error: function(){alert('Возникла ошибка...Попробуйте позже!')},
				complete: function(){
					$('.fields .loading').css('visibility', 'hidden')
					$('.fields .inner').css('opacity', '1')
				}
			});
		},
		
		
		
		
		edit : function(id)
		{
			if(typeof id == 'undefined') id = ''
			
			$.fancybox.showLoading()
			$.ajax({
				url: '/'+Slonne.ADMIN_URL_SIGN+'/'+this.URL_SECTION+'/edit/',
				data: 'id='+id+'&essenceId='+this.CURRENT_ESSENCE+'&type='+this.CURRENT_TYPE,
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
			$('#field-edit-form input').removeClass('field-error')
			$('#field-edit-form .loading').css('display', 'block')
			$('#field-edit-form .info').html('')
		}, 
		editSubmitComplete : function()
		{
			$('#field-edit-form .loading').css('display', 'none')
		},
		
		
		
		
		delete : function(id)
		{
			if(!confirm("Уверены?"))
				return
			
			$('.fields .loading').css('visibility', 'visible')
			$('.fields .inner').css('opacity', '.3')
			
			$.ajax({
				url: '/'+Slonne.ADMIN_URL_SIGN+'/'+this.URL_SECTION+'/delete/',
				data: 'id='+id,
				dataType: 'json',
				success: function(data){
					if(data.error == '')
					{
						//$('#row-'+id).remove()
						notice("Удалено!")
						$('#row-'+id).fadeOut()
					}
					else
						error(data.error)
				},
				error: function(){alert('Возникла ошибка...Попробуйте позже!')},
				complete: function(){
					$('.fields .loading').css('visibility', 'hidden')
					$('.fields .inner').css('opacity', '1')
				}
			});	
		},
		
		
		
		listSubmitStart : function()
		{
			$('.fields .loading').css('visibility', 'visible')
			$('.fields .inner').css('opacity', '.3')
		},
		listSubmitComplete : function()
		{
			$('.fields .loading').css('visibility', 'hidden')
			$('.fields .inner').css('opacity', '1')
			Slonne.Fields.list(this.CURRENT_ESSENCE, this.CURRENT_TYPE)
		},
		
		
		
		changeFieldType : function(type)
		{
			$('.dop-info').slideUp()
			if(type != '')
				$('.dop-info.'+type+'').slideDown();
		}
		
		
} 