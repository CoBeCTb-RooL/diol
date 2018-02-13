Slonne.Entities = {
		
		EXPAND_PLUS : '<i class="fa fa-plus-square-o"></i>',
		EXPAND_MINUS : '<i class="fa fa-minus-square-o"></i>',
		
		LAST_VIEWED : 0,
		LAST_ACTION : '',
		LAST_VIEWED_TYPE : '',
		
		LIST_SETTINGS : {},
		TREE_SETTINGS : {},
		
		TYPE_ELEMENTS : 'elements',
		TYPE_BLOCKS : 'elements',
		
		
		init : function()
		{ 
			alert('eNTiTieS! ')
		},
		
		
		treeItemHtml : function(item)
		{
			//alert(item)
			var html = $('#tree-item-template').html()
			
			html = html.replace(/_NAME_/g, item.attrs.name);
			html = html.replace(/_ID_/g, item.id);
			html = html.replace(/_IDX_/g, item.idx);
			html = html.replace(/_PLUS_/g, Slonne.Entities.EXPAND_PLUS);
			//alert(html)
			return html
		},
		
		
		
		
		getEntities : function(essenceCode, pid, type, lang, p)
		{
			if(typeof p == 'undefined') p=0
			
			$('#item-'+pid+'> .loading').css('display', 'block')
			$('#item-'+pid+' .expand-btn').css('display', 'none')
			$('#subs-'+pid+'').css('opacity', '.3')
			//$('#item-'+pid).css('opacity', '.3')
			Slonne.Entities.TREE_SETTINGS = {
				pid: pid,
				type: type,
				p: p,
				lang: lang,
				essenceCode: essenceCode
			}
			
			$.ajax({
				url: '/'+Slonne.ADMIN_URL_SIGN+'/entity/listJson/',
				data: 'essenceCode='+essenceCode+'&pid='+pid+'&type='+type+'&lang='+lang+'&p='+p,
				dataType: 'json',
				success: function(data, textStatus){
					if(!data.error){
						var parentId = 0;
						$('#tree-loading-global').css('display', 'none')
						
						$.each(data.treeItems, function(index, item)
						{
							//alert(item)
							var exists = false
							for(var i in Slonne.Entities.LOADED_TREE_ITEMS)
								if(Slonne.Entities.LOADED_TREE_ITEMS[i] == item.id)
									exists = true;
							if(!exists)
								Slonne.Entities.LOADED_TREE_ITEMS.push(item.id)
							//alert(Slonne.Entities.LOADED_TREE_ITEMS)
							if(index==0)
							{
								parentId = item.pid
								$('#subs-'+item.pid).html('')
								$('#item-'+item.id+' .expand-btn:first').html(Slonne.Entities.EXPAND_PLUS)								
							}
							
							$('#subs-'+item.pid).append(Slonne.Entities.treeItemHtml(item))
							
							if(item.active == 0)
								$('#item-'+item.id).addClass('inactive')
								
							if(item.untouchable > 0)
								$('#item-'+item.id).addClass('untouchable')
							
							if(item.childBlocksCount == 0)
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
						
						$('#item-'+parentId+' .expand-btn:first').html(Slonne.Entities.EXPAND_MINUS)
					}	
					else{
						alert(error(data.error))
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
				Slonne.Entities.getEntities(Slonne.Entities.ESSENCE, id, Slonne.Entities.TYPE, Slonne.Entities.LANG)
			else
			{
				$('#item-'+id+' .expand-btn:first').html(Slonne.Entities.EXPAND_PLUS)
				$(item).slideUp('fast')
				$(pg).slideUp('fast')
			}
		},
		
		
		
		
		
		view : function(essenceCode, id, type, lang)
		{
			Slonne.Entities.LAST_VIEWED = id
			Slonne.Entities.LAST_ACTION = 'view'
			
			$('#list-loading-global').css('visibility', 'visible')
			$('.list .inner').css('opacity', '.3')
			$.ajax({
				url: '/'+Slonne.ADMIN_URL_SIGN+'/entity/view/',
				data: 'essenceCode='+essenceCode+'&id='+id+'&type='+type+'&lang='+lang,
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
		
		
		
		
		treeNameClick : function(id)
		{
			$('.item').removeClass('active')
			$('#item-'+id+'').addClass('active')
			
			
			Slonne.Entities.view(Slonne.Entities.ESSENCE, id, Slonne.Entities.TYPE, Slonne.Entities.LANG)
		},
		
		
		
		
		
		edit : function(essenceCode, id, type, lang, pid)
		{
			if(typeof pid == 'undefined') pid = ''
			/*$('#list-loading-global').css('visibility', 'visible')
			$('.list .inner').css('opacity', '.3')*/
				$.fancybox.showLoading()
			$.ajax({
				url: '/'+Slonne.ADMIN_URL_SIGN+'/entity/edit/',
				data: 'essenceCode='+essenceCode+'&id='+id+'&type='+type+'&lang='+lang+'&pid='+pid+'&LAST_VIEWED='+Slonne.Entities.LAST_VIEWED,
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
		
		
		
		
		
		editStart : function()
		{
			$('#edit-form input, #edit-form select').removeClass('field-error')
			$('#edit-form .loading').css('visibility', 'visible')
			$('#edit-form .info').html('')
			$('#edit-form submit').attr('disabled', 'disabled')
		},
		
		
		editComplete : function(data)
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
				Slonne.Entities.initiateLastAction()
				
				if(data.e.type != Slonne.Entities.TYPE_ELEMENTS  || (data.e.type == Slonne.Entities.TYPE_ELEMENTS && data.e.essence.jointFields) )
					$("#item-"+data.e.id+" .name-wrapper:first a").html(data.e.attrs.name)
					
				if(!data.edit)
				{
					$("#subs-"+data.e.pid+"").append(Slonne.Entities.treeItemHtml(data.e));
					$("#item-"+data.e.pid+" .expand-btn:first").css("visibility", "visible");
					$("#item-"+data.e.id+" .expand-btn:first").css("visibility", "hidden");
					
					if(data.e.essence.linear || data.e.essence.jointFields)
						$("#item-"+data.e.id+" .sub-elements-btn:first").css("visibility", "hidden");
					else
					{
						if(data.e.type == Slonne.Entities.TYPE_ELEMENTS)
							$("#item-"+data.e.pid+" .sub-elements-btn:first .num").html(parseInt($("#item-"+data.e.pid+" .sub-elements-btn:first .num").html())+1)
	
					}
				}
				if(data.e.pid == 0)
					$(".list .inner").html("");
				
				//	перемещение в дереве
				if(data.edit && data.pidWas!=null && data.pidWas!=data.e.pid)
				{
					$("#subs-"+data.e.pid+"").append($("#item-"+data.e.id+""));
					$("#item-"+data.e.pid+" .expand-btn:first").css("visibility", "visible")
				}
				
				if(data.e.active > 0)
					$("#item-"+data.e.id+"").removeClass("inactive")
				else
					$("#item-"+data.e.id+"").addClass("inactive")
					
				if(data.e.untouchable > 0)
					$("#item-"+data.e.id+"").addClass("untouchable")
				else
					$("#item-"+data.e.id+"").removeClass("untouchable")
			}
			
			//	варнинги
			for(var i in data.warnings)
			{
				warning(data.warnings[i].problem)
			}
			
			$('#edit-form submit').removeAttr('disabled')
			$('#edit-form .loading').css('visibility', 'hidden')
		},
		
		
		
		
		
		entitiesList : function(pid, type, order, desc, p)
		{
			if(typeof order == 'undefined') order=''
			if(typeof desc == 'undefined') desc=''
			if(typeof p == 'undefined') p=0
			
			Slonne.Entities.LAST_VIEWED = pid
			Slonne.Entities.LAST_ACTION = 'list'
			Slonne.Entities.LAST_VIEWED_TYPE = type
			
			$('.item').removeClass('active')
			$('#item-'+pid+'').addClass('active')
			
			//alert(p)
			
			Slonne.Entities.LIST_SETTINGS = {
				pid: pid,
				type: type,
				order: order,
				desc: desc,
				p: p,
				lang: Slonne.Entities.LANG,
				essenceCode: Slonne.Entities.ESSENCE
			}
			//Slonne.Entities.LIST_P = p
				
			//alert('essenceCode='+Slonne.Entities.ESSENCE+'&pid='+pid+'&type='+type+'&lang='+Slonne.Entities.LANG+'&order='+order+'&desc='+desc)
			$('#list-loading-global').css('visibility', 'visible')
			$('.list .inner').css('opacity', '.3')
			$.ajax({
				url: '/'+Slonne.ADMIN_URL_SIGN+'/entity/entitiesList/',
				data: 'essenceCode='+Slonne.Entities.ESSENCE+'&pid='+pid+'&type='+type+'&lang='+Slonne.Entities.LANG+'&order='+order+'&desc='+desc+'&p='+p,
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
		
		
		
		
		
		
		changeLang : function(lang)
		{
			$('.langs a').removeClass('active')
			$('#lang-'+lang).addClass('active')
			Slonne.Entities.LANG = lang
//alert(Slonne.Entities.LANG)
			$('#list-loading-global').css('visibility', 'visible')
			$('.list .inner').css('opacity', '.3')
			$('.tree').css('opacity', '.3')
			
			//	ПЕРЕВОДЫ
			if(!Slonne.Entities.LINEAR)
			{
				$.ajax({
					url: '/'+Slonne.ADMIN_URL_SIGN+'/entity/entitiesListTranslationJson/',
					data: {essenceCode: Slonne.Entities.ESSENCE, lang: Slonne.Entities.LANG, ids: Slonne.Entities.LOADED_TREE_ITEMS},
					dataType: "json",
					success: function(data, textStatus){
						
						$.each(data.result.treeItems, function(index, item){
							//alert(item.attrs.name)
							$('#item-'+item.id+' .name-wrapper:first a').html(item.attrs.name)
							$('#item-'+item.id+' .id:first').html(item.id+')')
							//alert(item.attrs.name);
						});
					},
					error: function(){alert('Возникла ошибка...Попробуйте позже!')},
					complete: function(){
						$('#list-loading-global').css('visibility', 'hidden')
						$('.list .inner').css('opacity', '1')
						$('.tree').css('opacity', '1')
					}
				});
			}
			
			//	ВОСПРОИЗВОДИМ ДЕЙСТВИЕ СПРАВА
			Slonne.Entities.initiateLastAction();
			
		},
		
		
		
		
		
		initiateLastAction : function()
		{
			if(Slonne.Entities.LAST_ACTION != '')
			{
				//alert(Slonne.Entities.LAST_ACTION)
				if(Slonne.Entities.LAST_ACTION == 'view')
					Slonne.Entities.treeNameClick(Slonne.Entities.LAST_VIEWED)
				
				if(Slonne.Entities.LAST_ACTION == 'list')
					Slonne.Entities.entitiesList(Slonne.Entities.LAST_VIEWED, Slonne.Entities.LAST_VIEWED_TYPE);
			}
		},
		
		
		
		
		
		treeSaveChanges : function()
		{
			$('.tree').css('opacity', '.3')
			$('#tree-loading-global').css('display', 'block')
			$('#tree-form').submit()
		},
		
		treeSaveChangesComplete : function()
		{
			$('.tree').css('opacity', '1')
			$('#tree-loading-global').css('display', 'none')
		},
		
		
		
		
		listSaveChanges : function()
		{
			$('.list').css('opacity', '.3')
			$('#list-loading-global').css('visibility', 'visible')
			//$('#list-form').submit()
		},
		
		listSaveChangesComplete : function()
		{
			$('.list').css('opacity', '1')
			$('#list-loading-global').css('visibility', 'hidden')
			
			Slonne.Entities.initiateLastAction()
			//Slonne.Entities.expandClick(Slonne.Entities.LAST_VIEWED)
			Slonne.Entities.getEntities(Slonne.Entities.ESSENCE, Slonne.Entities.LAST_VIEWED, Slonne.Entities.TYPE, Slonne.Entities.LANG)
		},
		
		
		
		
		delete: function(id, type)
		{
			if(!confirm("Уверены?"))
				return; 
			
			$('.list').css('opacity', '.3')
			$('#list-loading-global').css('visibility', 'visible')
			
			$.ajax({
				url: '/'+Slonne.ADMIN_URL_SIGN+'/entity/delete/',
				data: {essenceCode: Slonne.Entities.ESSENCE, id: id, type: type},
				dataType: "json",
				success: function(data, textStatus){
					if(data.error == '')
					{
						$('.list .inner').fadeOut().html('').fadeIn()
						$('#item-'+id).fadeOut()
						notice("Удалено!")
					}
					else
					{
						error(data.error)
					}
				},
				error: function(){alert('Возникла ошибка...Попробуйте позже!')},
				complete: function(){
					$('#list-loading-global').css('visibility', 'hidden')
					$('.list').css('opacity', '1')
				}
			});
		},
		
		
		
		
		changeActive : function(id) 
		{
			$('#row-'+id+' .active-cb-wrapper input[type="checkbox"]').css('display', 'none')
			$('#row-'+id+' .active-cb-wrapper .loading').css('display', 'block')

			var value = 0
			if($('#active-'+id).is(':checked'))
				value=1

			$.ajax({
				url: '/'+Slonne.ADMIN_URL_SIGN+'/entity/setActive/',
				data: {essenceCode: Slonne.Entities.ESSENCE, id: id, type: Slonne.Entities.LAST_VIEWED_TYPE, value: value, lang: Slonne.Entities.LANG},
				dataType: "json",
				success: function(data, textStatus){
					if(data.error == '')
					{
						if(value > 0)
						{
							$('#row-'+id).removeClass('inactive')
							$('.tree #item-'+id+'').removeClass('inactive')
						}
						else
						{
							$('#row-'+id).addClass('inactive')
							$('.tree #item-'+id+'').addClass('inactive')
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
		
		
		
		
		
		
		
		deleteMedia : function(id)
		{
			if(!confirm("Уверены?"))
				return
			
			$.ajax({
				url: '/'+Slonne.ADMIN_URL_SIGN+'/entity/deleteMedia/',
				data: {id: id, lang:Slonne.Entities.LANG},
				dataType: "json",
				beforeSend: function(){
					$('#multipic-'+id+' .delete').css('display', 'none')
					$('#multipic-'+id+' .delete-loading').css('display', 'block')
				},
				success: function(data, textStatus){
					if(data.error == '')
					{
						notice("Картинка удалена")
						$('#multipic-'+id).fadeOut()
					}
					else
						error(data.error)
				},
				error: function(){alert('Возникла ошибка...Попробуйте позже!')},
				complete: function(){
					$('#multipic-'+id+' .delete').css('display', 'block')
					$('#multipic-'+id+' .delete-loading').css('display', 'none')
				}
			});	
		}
		
		
		
	 } 







