/*Slonne.AdminGroups = {
		
		URL_SECTION : 'adminGroup',
		
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
			Slonne.AdminGroups.list()
		},
		
		
		
		rootPrivilegeClick : function(id)
		{
			//alert(id)
			var val = $('.privileges #module-'+id+' > label > input[type="checkbox"]')
			//var val = $('#root-priv-'+id)
			
			//$(val).slideUp()
			//alert('.privileges #module-'+id+' > label > input[type="checkbox"]')
			if(!$(val).is(':checked'))
				$('.privileges #module-'+id+' > .sub >label > input[type="checkbox"]').prop( "checked", false )
			else
				$('.privileges #module-'+id+' > .sub >label > input[type="checkbox"]').prop( "checked", true );
			//alert($(val).is(':checked'));
		}
		
		
} */