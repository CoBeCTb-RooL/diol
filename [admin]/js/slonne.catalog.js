if(typeof Slonne == 'undefined')
	var Slonne ={} 

Slonne.Catalog = {
		
		URL_SECTION : 'catalog',
		
		LOADED_TREE_ITEMS: [], 
		
		Types:
		{
			typesList: function()
			{
				$('.cat-types .loading').css('visibility', 'visible')
				$('.cat-types .inner').css('opacity', '.3')
				
				$.ajax({
					url: '/'+Slonne.ADMIN_URL_SIGN+'/'+Slonne.Catalog.URL_SECTION+'/types/list',
					data: '',
					success: function(data){
						$('.cat-types .inner').html(data)
					},
					error: function(){alert('Возникла ошибка...Попробуйте позже!')},
					complete: function(){
						$('.cat-types .loading').css('visibility', 'hidden')
						$('.cat-types .inner').css('opacity', '1')
					}
				});
			},
			
			
			//	редактирование типа каталога
			typeEdit : function(id)
			{
				if(typeof id == 'undefined') id = ''
				
				$.fancybox.showLoading()
				$.ajax({
					url: '/'+Slonne.ADMIN_URL_SIGN+'/'+Slonne.Catalog.URL_SECTION+'/types/edit/',
					data: 'id='+id,
					success: function(data){
						$('#float').html(data)
						$.fancybox('#float');
					},
					error: function(){alert('Возникла ошибка...Попробуйте позже!')},
					complete: function(){}
				});
			},
			
			typeEditSubmitStart : function()
			{
				$('#edit-form input').removeClass('field-error')
				$('#edit-form .loading').css('display', 'block')
				$('#edit-form .info').html('')
			}, 
			typeEditSubmitComplete : function(result)
			{
				$('#edit-form .loading').css('display', 'none')
				if(result.error != '')
				{
					for(var i in result.problems)
					{
						highlight("edit-form input[name='"+result.problems[i]+"']")
						$("#edit-form *[name='"+result.problems[i]+"']").addClass("field-error")
					}
					
					error(result.error)
				}
				else
				{
					$.fancybox.close();
					notice('Сохранено!')
					Slonne.Catalog.Types.typesList()
				}
			},
			
	
			typeDelete : function(id)
			{
				if(!confirm("Уверены?"))
					return
				
				$('.cat-types .loading').css('visibility', 'visible')
				$('.cat-types .inner').css('opacity', '.3')
				
				$.ajax({
					url: '/'+Slonne.ADMIN_URL_SIGN+'/'+Slonne.Catalog.URL_SECTION+'/types/delete/',
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
						$('.cat-types .loading').css('visibility', 'hidden')
						$('.cat-types .inner').css('opacity', '1')
					}
				});	
			}
		},
		
		
		
		
		
		
		Props: 
		{
			propsList: function()
			{
				$('.cat-types .loading').css('visibility', 'visible')
				$('.cat-types .inner').css('opacity', '.3')
				
				$.ajax({
					url: '/'+Slonne.ADMIN_URL_SIGN+'/'+Slonne.Catalog.URL_SECTION+'/props/list',
					data: '',
					success: function(data){
						$('.cat-types .inner').html(data)
					},
					error: function(){alert('Возникла ошибка...Попробуйте позже!')},
					complete: function(){
						$('.cat-types .loading').css('visibility', 'hidden')
						$('.cat-types .inner').css('opacity', '1')
					}
				});
			},
			
			
			propsEdit : function(id)
			{
				if(typeof id == 'undefined') id = ''
				
				$.fancybox.showLoading()
				$.ajax({
					url: '/'+Slonne.ADMIN_URL_SIGN+'/'+Slonne.Catalog.URL_SECTION+'/props/edit/',
					data: 'id='+id,
					success: function(data){
						$('#float').html(data)
						$.fancybox('#float');
					},
					error: function(){alert('Возникла ошибка...Попробуйте позже!')},
					complete: function(){}
				});
			},
			
			propsEditSubmitStart : function()
			{
				$('#edit-form input').removeClass('field-error')
				$('#edit-form .loading').css('display', 'block')
				$('#edit-form .info').html('')
			}, 
			propsEditSubmitComplete : function(result)
			{
				$('#edit-form .loading').css('display', 'none')
				if(result.error != '')
				{
					for(var i in result.problems)
					{
						highlight("edit-form input[name='"+result.problems[i]+"']")
						$("#edit-form *[name='"+result.problems[i]+"']").addClass("field-error")
					}
					
					error(result.error)
				}
				else
				{
					$.fancybox.close();
					notice('Сохранено!')

					if(result.warnings && result.warnings.length > 0)
						for(var i in result.warnings)
							warning(result.warnings[i])
							
					Slonne.Catalog.Props.propsList()
				}
			},
			
	
			propsDelete : function(id)
			{
				if(!confirm("Уверены?"))
					return
				
				$('.cat-types .loading').css('visibility', 'visible')
				$('.cat-types .inner').css('opacity', '.3')
				
				$.ajax({
					url: '/'+Slonne.ADMIN_URL_SIGN+'/'+Slonne.Catalog.URL_SECTION+'/props/delete/',
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
						$('.cat-types .loading').css('visibility', 'hidden')
						$('.cat-types .inner').css('opacity', '1')
					}
				});	
			},
			
			propsOptionEdit: function(optId)
			{
				//alert(optId)
				var val=$('#opt-'+optId+'-input input').val()
				//alert(val);
				$.ajax({
					url: '/'+Slonne.ADMIN_URL_SIGN+'/'+Slonne.Catalog.URL_SECTION+'/props/optionValueSubmit/',
					data: 'id='+optId+'&val='+encodeURIComponent(val),
					dataType: 'json',
					success: function(data){
						if(data.error == '')
						{
							notice('Сохранено!')
							$('#opt-'+optId+'-value').slideDown().html(val); 
							$('#opt-'+optId+'-input').slideUp();
						}
						else
							error(data.error)
					},
					error: function(){alert('Возникла ошибка...Попробуйте позже!')},
					complete: function(){
						$('.cat-types .loading').css('visibility', 'hidden')
						$('.cat-types .inner').css('opacity', '1')
					}
				});	
			},
			
			
			propsOptionDelete:function(optId)
			{
				//alert(val);
				$.ajax({
					url: '/'+Slonne.ADMIN_URL_SIGN+'/'+Slonne.Catalog.URL_SECTION+'/props/optionDelete/',
					data: 'id='+optId,
					dataType: 'json',
					success: function(data){
						if(data.error == '')
						{
							notice('Опция удалена!')
							$('#opt-row-'+optId).fadeOut()
						}
						else
							error(data.error)
					},
					error: function(){alert('Возникла ошибка...Попробуйте позже!')},
					complete: function(){
						$('.cat-types .loading').css('visibility', 'hidden')
						$('.cat-types .inner').css('opacity', '1')
					}
				});	
			}
		},
		
		
		
		
		
		
		
		
		Classes:
		{
			classesList: function()
			{
				$('.cat-types .loading').css('visibility', 'visible')
				$('.cat-types .inner').css('opacity', '.3')
				
				$.ajax({
					url: '/'+Slonne.ADMIN_URL_SIGN+'/'+Slonne.Catalog.URL_SECTION+'/classes/list',
					data: '',
					success: function(data){
						$('.cat-types .inner').html(data)
					},
					error: function(){alert('Возникла ошибка...Попробуйте позже!')},
					complete: function(){
						$('.cat-types .loading').css('visibility', 'hidden')
						$('.cat-types .inner').css('opacity', '1')
					}
				});
			},
			
			
			//	редактирование типа каталога
			classesEdit : function(id)
			{
				if(typeof id == 'undefined') id = ''
				
				$.fancybox.showLoading()
				$.ajax({
					url: '/'+Slonne.ADMIN_URL_SIGN+'/'+Slonne.Catalog.URL_SECTION+'/classes/edit/',
					data: 'id='+id,
					success: function(data){
						$('#float').html(data)
						$.fancybox('#float');
					},
					error: function(){alert('Возникла ошибка...Попробуйте позже!')},
					complete: function(){}
				});
			},
			
			classesEditSubmitStart : function()
			{
				$('#edit-form input').removeClass('field-error')
				$('#edit-form .loading').css('display', 'block')
				$('#edit-form .info').html('')
			}, 
			classesEditSubmitComplete : function(result)
			{
				$('#edit-form .loading').css('display', 'none')
				if(result.error != '')
				{
					for(var i in result.problems)
					{
						highlight("edit-form input[name='"+result.problems[i]+"']")
						$("#edit-form *[name='"+result.problems[i]+"']").addClass("field-error")
					}
					
					error(result.error)
				}
				else
				{
					$.fancybox.close();
					notice('Сохранено!')
					Slonne.Catalog.Classes.classesList()
				}
			},
			
	
			classesDelete : function(id)
			{
				if(!confirm("Уверены? Класс будет удалён БЕЗВОЗВРАТНО!"))
					return
				
				$('.cat-types .loading').css('visibility', 'visible')
				$('.cat-types .inner').css('opacity', '.3')
				
				$.ajax({
					url: '/'+Slonne.ADMIN_URL_SIGN+'/'+Slonne.Catalog.URL_SECTION+'/classes/delete/',
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
						$('.cat-types .loading').css('visibility', 'hidden')
						$('.cat-types .inner').css('opacity', '1')
					}
				});	
			}
		},
		
		
		
		
		
		
		
		
		Interface: 
		{
			//alert(123)
			
			EXPAND_PLUS : '<i class="fa fa-plus-square-o"></i>',
			EXPAND_MINUS : '<i class="fa fa-minus-square-o"></i>',
			
			TREE_SETTINGS: {},
			
			
			treeItemHtml : function(item)
			{
				//alert(item)
				var html = $('#tree-item-template').html()
				
				html = html.replace(/_NAME_/g, item.name);
				html = html.replace(/_ID_/g, item.id);
				html = html.replace(/_IDX_/g, item.idx);
				html = html.replace(/_PLUS_/g, Slonne.Catalog.Interface.EXPAND_PLUS);
				
				if(item.class == null)
					html = html.replace(/_CAT_/g, 'класс: <span class="no-class" >НЕТ </span>');
				else
					html = html.replace(/_CAT_/g, 'класс: <a href="/admin/catalog/classes?id='+item.class.id+'" target="_blank"  ><b>'+item.class.name+' : '+item.class.id+'</b></a>');
				//alert(html)
				return html
			},
			
			
			
			catsList : function(catType, pid, lang, p)
			{
				if(typeof p == 'undefined') p=0
				
				$('#item-'+pid+'> .loading').css('display', 'block')
				$('#item-'+pid+' .expand-btn').css('display', 'none')
				$('#subs-'+pid+'').css('opacity', '.3')
				//$('#item-'+pid).css('opacity', '.3')
				Slonne.Catalog.TREE_SETTINGS = {
					pid: pid,
					catType: catType,
					p: p,
					lang: lang
				}
				
				$.ajax({
					url: '/'+Slonne.ADMIN_URL_SIGN+'/catalog/interface/catsListJson/',
					data: 'catType='+catType+'&pid='+pid+'&lang='+lang+'&p='+p,
					dataType: 'json',
					success: function(data, textStatus){
						if(!data.error){
							var parentId = 0;
							$('#tree-loading-global').css('display', 'none')
							
							$.each(data.treeItems, function(index, item)
							{
								//alert(item)
								var exists = false
								for(var i in Slonne.Catalog.LOADED_TREE_ITEMS)
									if(Slonne.Catalog.LOADED_TREE_ITEMS[i] == item.id)
										exists = true;
								if(!exists)
									Slonne.Catalog.LOADED_TREE_ITEMS.push(item.id)
								//alert(Slonne.Catalog.LOADED_TREE_ITEMS)
								if(index==0)
								{
									parentId = item.pid
									$('#subs-'+item.pid).html('')
									$('#item-'+item.id+' .expand-btn:first').html(Slonne.Catalog.EXPAND_PLUS)								
								}
								
								$('#subs-'+item.pid).append(Slonne.Catalog.Interface.treeItemHtml(item))
								
								if(item.active == 0)
									$('#item-'+item.id).addClass('inactive')
									
								if(item.untouchable > 0)
									$('#item-'+item.id).addClass('untouchable')
								
								if(item.childBlocksCount == 0 || item.childBlocksCount == null)
									$('#item-'+item.id+' .expand-btn:first').css('visibility', 'hidden')
								
								if(item.childElementsCount != null)
								{
									$('#item-'+item.id+' .sub-elements-btn:first').css('visibility', 'visible')
									$('#item-'+item.id+' .sub-elements-btn:first .num').html(item.childElementsCount)
								}
								else
									$('#item-'+item.id+' .sub-elements-btn:first').css('visibility', 'hidden')
							});
							$('#subs-'+parentId).slideDown('fast')
							
							//alert(data.pagesHTML);
							$('#item-'+parentId+' > .pg').html(data.pagesHTML)
							//alert('#item-'+parentId+' > .pg')
							$('#item-'+parentId+' > .pg').slideDown('fast')
							$('#subs-'+parentId+'').css('opacity', '1')
							
							$('#item-'+parentId+' .expand-btn:first').html(Slonne.Catalog.Interface.EXPAND_MINUS)
						}	
						else{
							error(data.error)
						}
					},
					error: function(){alert('Возникла ошибка...Попробуйте позже!')},
					complete: function(){
						$('#item-'+pid+' .loading').css('display', 'none')
						$('#item-'+pid+' .expand-btn').css('display', 'block')
						$('#item-'+pid).css('opacity', '1')
					}
				});
			},
			
			
			
			expandClick : function(id)
			{
				var item = $('#subs-'+id)
				var pg = $('#item-'+id+' .pg')

				if($(item).css('display') == 'none' || id == 0)
					Slonne.Catalog.Interface.catsList(Slonne.Catalog.CatType, id, Slonne.Catalog.LANG)
				else
				{
					$('#item-'+id+' .expand-btn:first').html(Slonne.Catalog.Interface.EXPAND_PLUS)
					$(item).slideUp('fast')
					$(pg).slideUp('fast')
				}
			},
			
			
			treeNameClick : function(id)
			{
				$('.item').removeClass('active')
				$('#item-'+id+'').addClass('active')
				
				
				//Slonne.Catalog.Interface.catView(id, Slonne.Entities.LANG)
				Slonne.Catalog.Interface.itemsList(id)
			},
			
			
			catView : function(id, lang)
			{
				Slonne.Catalog.Interface.LAST_VIEWED = id
				Slonne.Catalog.Interface.LAST_ACTION = 'catView'
				
				$('#list-loading-global').css('visibility', 'visible')
				$('.list .inner').css('opacity', '.3')
				$.ajax({
					url: '/'+Slonne.ADMIN_URL_SIGN+'/catalog/interface/cat_view/',
					data: 'id='+id+'&lang='+lang+'&catType='+Slonne.Catalog.CatType,
					success: function(data){
						$('.list .inner').html(data)
					},
					error: function(){alert('Возникла ошибка...Попробуйте позже!')},
					complete: function(){
						$('#list-loading-global').css('visibility', 'hidden')
						$('.list .inner').css('opacity', '1')
					}
				});
			},
			
			
			
			
			catEdit : function(catType, id, lang, pid)
			{
				if(typeof pid == 'undefined') pid = ''
				/*$('#list-loading-global').css('visibility', 'visible')
				$('.list .inner').css('opacity', '.3')*/
					$.fancybox.showLoading()
				$.ajax({
					url: '/'+Slonne.ADMIN_URL_SIGN+'/catalog/interface/catEdit/',
					data: 'catType='+catType+'&id='+id+'&lang='+lang+'&pid='+pid+'',
					success: function(data){
						//$('.list .inner').html(data)
						$('#float').html(data)
						$.fancybox('#float');
					},
					error: function(){alert('Возникла ошибка...Попробуйте позже!')},
					complete: function(){
						$('#list-loading-global').css('visibility', 'hidden')
						$('.list .inner').css('opacity', '1')
					}
				});
			},
			

			
			catEditStart : function()
			{
				$('#edit-form input, #edit-form select').removeClass('field-error')
				$('#edit-form .loading').css('visibility', 'visible')
				$('#edit-form .info').html('')
				$('#edit-form submit').attr('disabled', 'disabled')
			},
			
			
			catEditComplete : function(data)
			{
				//	ошибка
				if(data.error != '')
				{
					error(data.error)
					//	подсвечиваем
					for(var i in data.problems)
					{
						highlight(data.problems[i].field)
						markError(data.problems[i].field)
					}
				}
				else	//	Всё ок!
				{
					notice('Сохранено!')
					
					$(document).scrollTop();
					$.fancybox.close();
					Slonne.Catalog.Interface.initiateLastAction()
					
					$("#item-"+data.cat.id+" .name-wrapper:first a").html(data.cat.name)
					if(!data.edit)
					{
						$("#subs-"+data.cat.pid+"").append(Slonne.Catalog.Interface.treeItemHtml(data.cat));
						$("#item-"+data.cat.pid+" .expand-btn:first").css("visibility", "visible");
						$("#item-"+data.cat.id+" .expand-btn:first").css("visibility", "hidden");
						
						
						/*
						{
							if(data.e.type == Slonne.Catalog.TYPE_ELEMENTS)
								$("#item-"+data.e.pid+" .sub-elements-btn:first .num").html(parseInt($("#item-"+data.e.pid+" .sub-elements-btn:first .num").html())+1)
		
						}*/
					}
					if(data.cat.pid == 0)
						$(".list .inner").html("");
					
					//	перемещение в дереве
					if(data.edit && data.pidWas!=null && data.pidWas!=data.cat.pid)
					{
						$("#subs-"+data.cat.pid+"").append($("#item-"+data.cat.id+""));
						$("#item-"+data.cat.pid+" .expand-btn:first").css("visibility", "visible")
					}
					
					if(data.cat.active > 0)
						$("#item-"+data.cat.id+"").removeClass("inactive")
					else
						$("#item-"+data.cat.id+"").addClass("inactive")
						
					if(data.cat.untouchable > 0)
						$("#item-"+data.cat.id+"").addClass("untouchable")
					else
						$("#item-"+data.cat.id+"").removeClass("untouchable")
				}
				
				//	варнинги
				for(var i in data.warnings)
				{
					warning(data.warnings[i].problem)
				}
				
				$('#edit-form submit').removeAttr('disabled')
				$('#edit-form .loading').css('visibility', 'hidden')
			},
			
			
			
			initiateLastAction : function()
			{
				if(Slonne.Catalog.Interface.LAST_ACTION != '')
				{
					//alert(Slonne.Entities.LAST_ACTION)
					if(Slonne.Catalog.Interface.LAST_ACTION == 'catView')
						Slonne.Catalog.Interface.treeNameClick(Slonne.Catalog.Interface.LAST_VIEWED)
					
					/*if(Slonne.Catalog.Interface.LAST_ACTION == 'list')
						Slonne.Catalog.Interface.entitiesList(Slonne.Entities.LAST_VIEWED, Slonne.Entities.LAST_VIEWED_TYPE);*/
				}
			},
			
			
			
			itemsList : function(pid, order, desc, p)
			{
				if(typeof order == 'undefined') order=''
				if(typeof desc == 'undefined') desc=''
				if(typeof p == 'undefined') p=0
				
				Slonne.Catalog.Interface.LAST_VIEWED = pid
				
				$('.item').removeClass('active')
				$('#item-'+pid+'').addClass('active')
				
				//alert(p)
				
				Slonne.Catalog.Interface.LIST_SETTINGS = {
					pid: pid,
					order: order,
					desc: desc,
					p: p,
					lang: Slonne.Catalog.Interface.LANG
				}
				//Slonne.Entities.LIST_P = p
					
				//alert('essenceCode='+Slonne.Entities.ESSENCE+'&pid='+pid+'&type='+type+'&lang='+Slonne.Entities.LANG+'&order='+order+'&desc='+desc)
				$('#list-loading-global').css('visibility', 'visible')
				$('.list .inner').css('opacity', '.3')
				$.ajax({
					url: '/'+Slonne.ADMIN_URL_SIGN+'/catalog/itemsList/',
					data: 'pid='+pid+'&lang='+Slonne.Catalog.Interface.LANG+'&order='+order+'&desc='+desc+'&p='+p,
					success: function(data){
						$('.list .inner').html(data)
					},
					error: function(){alert('Возникла ошибка...Попробуйте позже!')},
					complete: function(){
						$('#list-loading-global').css('visibility', 'hidden')
						$('.list .inner').css('opacity', '1')
					}
				});
			},
			
			
			
			
			itemEdit : function(itemId, pid, lang)
			{
				if(typeof pid == 'undefined') pid = ''
				/*$('#list-loading-global').css('visibility', 'visible')
				$('.list .inner').css('opacity', '.3')*/
				$.fancybox.showLoading()
				$.ajax({
					url: '/'+Slonne.ADMIN_URL_SIGN+'/catalog/interface/itemEdit/',
					data: 'id='+itemId+'&pid='+pid+'&lang='+lang+'',
					success: function(data){
						//$('.list .inner').html(data)
						$('#float').html(data)
						$.fancybox('#float');
					},
					error: function(){alert('Возникла ошибка...Попробуйте позже!')},
					complete: function(){
						$('#list-loading-global').css('visibility', 'hidden')
						$('.list .inner').css('opacity', '1')
					}
				});
			},
			itemEditSubmitStart : function()
			{
				$('#edit-form input').removeClass('field-error')
				$('#edit-form .loading').css('display', 'block')
				$('#edit-form .info').html('')
			}, 
			itemEditSubmitComplete : function(result)
			{
				$('#edit-form .loading').css('display', 'none')
				if(result.error != '')
				{
					for(var i in result.problems)
					{
						highlight("edit-form input[name='"+result.problems[i]['field']+"']")
						$("#edit-form *[name='"+result.problems[i]['field']+"']").addClass("field-error")
					}
					
					error(result.error)
				}
				else
				{
					//$.fancybox.close();
					notice('Сохранено!')
					Slonne.Catalog.Interface.itemsList(Slonne.Catalog.Interface.LIST_SETTINGS['pid'], Slonne.Catalog.Interface.LIST_SETTINGS['order'], Slonne.Catalog.Interface.LIST_SETTINGS['desc'], Slonne.Catalog.Interface.LIST_SETTINGS['p'])
				}
			},
			
			listSaveChangesStart : function()
			{
				//alert(123)
			}, 
			
			catClassEdit : function(catId)
			{	
				$.fancybox.showLoading();
				$.ajax({
					url: '/'+Slonne.ADMIN_URL_SIGN+'/catalog/interface/catClassEdit/',
					data: 'id='+catId,
					success: function(data){
						//$('.list .inner').html(data)
						$('#float').html(data)
						$.fancybox('#float');
					},
					error: function(){alert('Возникла ошибка...Попробуйте позже!')},
					complete: function(){
						$('#list-loading-global').css('visibility', 'hidden')
						$('.list .inner').css('opacity', '1')
					}
				});
			},
			catClassEditSubmitStart : function()
			{
				$('#edit-form input').removeClass('field-error')
				$('#edit-form .loading').css('display', 'block')
				$('#edit-form .info').html('')
			}, 
			catClassEditSubmitComplete : function(result)
			{
				$('#edit-form .loading').css('display', 'none')
				if(result.error != '')
				{
					for(var i in result.problems)
					{
						highlight("edit-form input[name='"+result.problems[i]+"']")
						$("#edit-form *[name='"+result.problems[i]+"']").addClass("field-error")
					}
					
					error(result.error)
				}
				else
				{
					$.fancybox.close();
					notice('Сохранено!')
					//Slonne.Catalog.Types.typesList()
				}
			},
			
			
			
			changeActive : function(id) 
			{
				$('#row-'+id+' .active-cb-wrapper input[type="checkbox"]').css('display', 'none')
				$('#row-'+id+' .active-cb-wrapper .loading').css('display', 'block')

				var value = 0
				if($('#active-'+id).is(':checked'))
					value=1

				$.ajax({
					url: '/'+Slonne.ADMIN_URL_SIGN+'/catalog/interface/catItemSetActive/',
					data: {id: id, value: value},
					dataType: "json",
					success: function(data, textStatus){
						if(data.error == '')
						{
							if(value > 0)
							{
								$('#row-'+id).removeClass('inactive')
								//$('.tree #item-'+id+'').removeClass('inactive')
							}
							else
							{
								$('#row-'+id).addClass('inactive')
								//$('.tree #item-'+id+'').addClass('inactive')
							}
							
							notice("Изменено!")
							
						}
						else
							error(data.error)
					},
					error: function(){alert('Возникла ошибка...Попробуйте позже!')},
					complete: function(){
						$('#row-'+id+' .active-cb-wrapper input[type="checkbox"]').css('display', 'inline')
						$('#row-'+id+' .active-cb-wrapper .loading').css('display', 'none')
					}
				});	
			
			},
			
			
		}
		
	
		
} 