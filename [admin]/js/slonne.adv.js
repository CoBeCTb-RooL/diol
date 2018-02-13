Slonne.Adv = {
		
		URL_SECTION : 'adv',
		
		Brands:
		{
			/*list : function()
			{
				$('.brands .loading').css('visibility', 'visible');
				$('.brands .inner').css('opacity', '.3');
				
				$.ajax({
					url: '/'+Slonne.ADMIN_URL_SIGN+'/'+Slonne.Adv.URL_SECTION+'/brands/list/',
					data: '',
					success: function(data){
						$('.brands .inner').html(data)
					},
					error: function(){alert('Возникла ошибка...Попробуйте позже!')},
					complete: function(){
						$('.brands .loading').css('visibility', 'hidden');
						$('.brands .inner').css('opacity', '1')
					}
				});
			},*/
			
			

			/*edit : function(id)
			{
				if(typeof id == 'undefined') id = '' 
				
				$.fancybox.showLoading()
				$.ajax({
					url: '/'+Slonne.ADMIN_URL_SIGN+'/'+Slonne.Adv.URL_SECTION+'/brands/edit/',
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
				$('#edit-form input').removeClass('field-error');
				$('#edit-form .loading').css('display', 'block');
				$('#edit-form .info').html('');
			}, 
			editSubmitComplete : function()
			{
				$('#edit-form .loading').css('display', 'none');
			},*/
			
			
			
			/*
			delete1: function(id)
			{
				if(!confirm("Уверены?"))
					return
				
				$('.brands .loading').css('visibility', 'visible')
				$('.brands .inner').css('opacity', '.3')
				
				$.ajax({
					url: '/'+Slonne.ADMIN_URL_SIGN+'/'+Slonne.Adv.URL_SECTION+'/brands/delete/',
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
						$('.brands .loading').css('visibility', 'hidden');
						$('.brands .inner').css('opacity', '1');
					}
				});	
			},
			*/

			/*listSubmitStart : function()
			{
				$('.brands .loading').css('visibility', 'visible');
				$('.brands .inner').css('opacity', '.3');
			},
			listSubmitComplete : function()
			{
				$('.brands .loading').css('visibility', 'hidden');
				$('.brands .inner').css('opacity', '1');
				Slonne.Adv.Brands.list();
			}*/
		}, 
		
		
		ArtNums:
		{
			/*list : function()
			{
				$('.article-numbers .loading').css('visibility', 'visible')
				$('.article-numbers .inner').css('opacity', '.3')
				
				$.ajax({
					url: '/'+Slonne.ADMIN_URL_SIGN+'/'+Slonne.Adv.URL_SECTION+'/article_numbers/list/',
					data: '',
					success: function(data){
						$('.article-numbers .inner').html(data)
					},
					error: function(){alert('Возникла ошибка...Попробуйте позже!')},
					complete: function(){
						$('.article-numbers .loading').css('visibility', 'hidden');
						$('.article-numbers .inner').css('opacity', '1')
					}
				});
			},*/
			
			

			/*edit : function(id)
			{
				if(typeof id == 'undefined') id = ''
				
				$.fancybox.showLoading()
				$.ajax({
					url: '/'+Slonne.ADMIN_URL_SIGN+'/'+Slonne.Adv.URL_SECTION+'/article_numbers/edit/',
					data: 'id='+id,
					success: function(data){
						$('#float').html(data)
						$.fancybox('#float');
					},
					error: function(){alert('Возникла ошибка...Попробуйте позже!')},
					complete: function(){}
				});
			},*/
			
			
			
			/*
			editSubmitStart : function()
			{
				$('#edit-form input').removeClass('field-error');
				$('#edit-form .loading').css('display', 'block') ;
				$('#edit-form .info').html('');
			}, 
			editSubmitComplete : function()
			{
				$('#edit-form .loading').css('display', 'none');
			},*/
			
			
			
			
			/*delete1: function(id)
			{
				if(!confirm("Уверены?"))
					return
				
				$('.article-numbers .loading').css('visibility', 'visible');
				$('.article-numbers .inner').css('opacity', '.3');
				
				$.ajax({
					url: '/'+Slonne.ADMIN_URL_SIGN+'/'+Slonne.Adv.URL_SECTION+'/article_numbers/delete/',
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
						$('.article-numbers .loading').css('visibility', 'hidden');
						$('.article-numbers .inner').css('opacity', '1');
					}
				});	
			},*/
			

			/*listSubmitStart : function()
			{
				$('.article-numbers .loading').css('visibility', 'visible');
				$('.article-numbers .inner').css('opacity', '.3');
			},
			listSubmitComplete : function()
			{
				$('.article-numbers .loading').css('visibility', 'hidden');
				$('.article-numbers .inner').css('opacity', '1');
				Slonne.Adv.ArtNums.list()
			}*/
		},
		
		
		AdvItems: {
			LIST_OPTIONS : {},
			
			itemsList: function(catId, opts)
			{
				$.fancybox.showLoading()
				
				if(typeof opts !='undefined')
					Slonne.Adv.AdvItems.LIST_OPTIONS = opts
				//alert(catId);
				//alert(opts);
				var optsGETString = ''
				for(var i in Slonne.Adv.AdvItems.LIST_OPTIONS)
					optsGETString+=i+'='+encodeURIComponent(Slonne.Adv.AdvItems.LIST_OPTIONS[i])+'&'
				//alert(JSON.stringify(opts))
				//alert(optsGETString)
				//return
				$.ajax({
					url: '/'+Slonne.ADMIN_URL_SIGN+'/adv/items/listAjax',
					data: 'catId='+catId+'&'+optsGETString,
					success: function(data){
						$('#cats').slideUp('fast')
						$('#items').slideDown('fast').html(data)
						$.fancybox.hideLoading();
					},
					error: function(e){alert(e=='' ? e : 'Возникла ошибка на сервере... Попробуйте позже.')},
					complete: function(){}
				});
			}
		}
		
		

		
} 