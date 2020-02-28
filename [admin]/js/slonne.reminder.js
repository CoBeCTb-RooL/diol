var Reminder = {
	$_wrapper: null,
	$wrapper: function(){
		if(!this.$_wrapper)
			this.$_wrapper = $('.reminder-form')
		return this.$_wrapper
	},

	opts: {},

	list: function(){
		$.ajax({
			url: '/admin/clients/clientRemindersList',
			data: {clientId: this.opts.clientId},
			beforeSend: function(){},
			complete: function(){},
			success: function(data){
				$('.reminder-list').html(data)
			},
			error: function(){alert('Возникла ошибка на сервере! Пожалуйста, попробуйте позднее..')},
		})
	},

	submit: function(){
		this.msg('')
		var date = this.$wrapper().find('input[name=date]').val()
		var comment = this.$wrapper().find('textarea[name=comment]').val()
		var clientId = this.$wrapper().find('input[name=clientId]').val()
		var id = this.$wrapper().find('input[name=id]').val()
		var errors = []
		if(date=='')
			errors.push({error: 'Укажите корректную дату!'})
		if(comment=='')
			errors.push({error: 'Введите текст напоминания!'})

		data = {
			id: id,
			clientId: clientId,
			date: date,
			comment: comment
		}

		// alert(errors)
		if(errors.length > 0)
			this.error(errors[0].error)
		else{
			$.ajax({
				url: '/admin/clients/clientsReminderSave',
				data: data,
				dataType: 'json',
				beforeSend: function(){Reminder.loading()},
				complete: function(){Reminder.loading(false)},
				success: function(data){
					if(!data.errors){
						Reminder.msg('Сохранено!')
						Reminder.list()
						Reminder.$wrapper().find('.form').slideUp()
					}
					else
						Reminder.error(data.errors[0].error)
				},
				error: function(){Reminder.error('Возникла ошибка на сервере! Пожалуйста, попробуйте позднее..')},
			})
		}
	},

	initForm: function(obj){
		obj.id = obj.id || ''
		obj.clientId = obj.clientId || this.opts.clientId
		obj.date = obj.dt || ''
		obj.comment = obj.comment || ''

		// alert(obj.date)

		this.$wrapper().find('input[name=id]').val(obj.id)
		this.$wrapper().find('input[name=clientId]').val(obj.clientId)
		this.$wrapper().find('input[name=date]').val(obj.date)
		this.$wrapper().find('textarea[name=comment]').val(obj.comment)

		// 	отображаем форму
		Reminder.$wrapper().slideDown('fast');
		Reminder.$wrapper().find('.form').slideDown('fast');
		Reminder.msg('');
	},

	delete: function(id){
		if(!confirm('Удалить?'))
			return
		$.ajax({
			url: '/admin/clients/clientReminderDelete',
			data: {id: id},
			beforeSend: function(){},
			complete: function(){},
			success: function(data){
				//if(!data.error)
					$('.reminder-list .item-'+id+'').fadeOut()
				// else
				// 	alert(data.error)
			},
			error: function(){alert('Возникла ошибка на сервере! Пожалуйста, попробуйте позднее..')},
		})
	},


	edit: function(id){
		$.ajax({
			url: '/admin/clients/clientReminderJson',
			data: {id: id},
			dataType: 'json',
			beforeSend: function(){},
			complete: function(){},
			success: function(data){
				Reminder.initForm(data)
				//if(!data.error)
				// $('.reminder-list .item-'+id+'').fadeOut()
				// else
				// 	alert(data.error)
			},
			error: function(){alert('Возникла ошибка на сервере! Пожалуйста, попробуйте позднее..')},
		})
	},


	loading: function(isOn){
		if(typeof isOn == 'undefined')
			isOn = true
		if(isOn)
			this.$wrapper().find('.loading').show()
		else
			this.$wrapper().find('.loading').hide()
	},

	error: function(msg){
		this.msg('<span style="color: red; ">'+msg+'</span>')
	},
	msg: function(msg){
		this.$wrapper().find('.info').html(msg).show()
	},
}