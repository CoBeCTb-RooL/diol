var Notifier = {
	$_wrapper: null,
	$wrapper: function(){
		if(!this.$_wrapper)
			this.$_wrapper = $('.notifier')
		return this.$_wrapper
	},
	
	list: function () {
		$.ajax({
			url: '/admin/notifier/list',
			data: {},
			// dataType: 'json',
			beforeSend: function(){},
			complete: function(){},
			success: function(data){
				Notifier.$wrapper().find('.inner').html(data)
			},
			error: function(){alert('Возникла ошибка на сервере! Пожалуйста, попробуйте позднее..')},
		})
	},


	setDone: function(id){
		if(!confirm('Отметить как выполненный?'))
			return
		$.ajax({
			url: '/admin/clients/clientsReminderSetDone',
			data: {id: id},
			beforeSend: function(){},
			complete: function(){},
			success: function(data){
				$('.notifier .inner .item-'+id).fadeOut()
			},
			error: function(){alert('Возникла ошибка на сервере! Пожалуйста, попробуйте позднее..')},
		})
	},


	postpone: function(id, period){
		if(!confirm('Отложить '+period+'?'))
			return
		$.ajax({
			url: '/admin/clients/clientsReminderPostpone',
			data: {id: id, period: period},
			beforeSend: function(){},
			complete: function(){},
			success: function(data){
				$('.notifier .inner .item-'+id).fadeOut()
			},
			error: function(){alert('Возникла ошибка на сервере! Пожалуйста, попробуйте позднее..')},
		})
	},
	
	
	
	
}