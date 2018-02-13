

function notice(str)
{
	$.stickr({note:str, className:'stickr-notice', speed:'fast'});
}


function error(str, removePrevious)
{
	$.stickr({note:str, className:'stickr-error', speed:'fast'/*, sticked:true*/ ,removePrevious:(typeof removePrevious=='undefined' || removePrevious==true)});
}

function warning(str)
{
	$.stickr({note:str, className:'stickr-warning', speed:'fast'/*, sticked:true*/});
}










//	загрузка
function loading(a, id, speed)
{
	if(typeof id=='undefined')
		alert('"'+id+'" <<< No LoaDiNG LaBeL FouND!')
	if(typeof speed=='undefined')
		speed='medium'
		
	if(a>0)
	{
		$('#'+id).slideDown(speed)
		$('.'+id).slideDown(speed)
		//$('#cart-loading-div').css('display', 'block')
		//$('#'+id).fadeIn('fast')
	}
	else
	{
		$('#'+id).slideUp(speed)
		$('.'+id).slideUp(speed)
		//$('#cart-loading-div').css('display', 'none')
		//$('#'+id).fadeOut('fast')
	}
}




// 	новая функция-обработчик мессаг
function showErrors(errors)
{
	var color="#E0AFB2"
		
	//alert(errors)
	// 	текст ошибки
	var msg = ''
	if(errors.length==1 && false)
		msg = errors[0].error
	else{
		msg='<b>Проблемы:</b>'
		for(var i in errors)
			if(errors[i].error && errors[i].error!='' )
				msg+='<br> - '+errors[i].error+''
	}
	error(msg)
	
	
	// 	выделение поля
	for(var i in errors){
		if(typeof errors[i].field != 'undefined'){
			$('input[name='+errors[i].field+'], select[name='+errors[i].field+'], textarea[name='+errors[i].field+']')
				.addClass("field-error")
				.css("background-color", color)
				.animate({ backgroundColor: "#fff" }, "slow");
		}
	}
}





function showNotice(msg, id, speed)
{
	showMsg(msg, '', id, speed)
}


function showError(msg, id, speed)
{
	showMsg(msg, 'err', id, speed)
}



function showMsg(msg, err_no, id, speed)
{
	
	if(typeof id=='undefined')
		id='cart-info-div'
	if(typeof speed=='undefined')
		speed='fast'
	
	
	var bgColor
	var spanId
	var colorFrom
	if(err_no=='err')
	{
		spanId='error-'+id
		colorFrom='#ff4040	'
		$('#'+id).html('<span class="error" id="'+spanId+'" style="font-size: 11px;color: #c54725">'+msg+'</span>')
		bgColor = $('.error').css('background-color')
	}
	else
	{
		colorFrom='#94ff65'
		spanId='notice-'+id
		$('#'+id).html('<span class="notice" id="'+spanId+'" style="font-size: 11px; color: #309641">'+msg+'</span>')
		bgColor = $('.notice').css('background-color')
	}
		
	//$('#'+id).stop(true).css('display', 'none').slideDown(speed).css('opacity', '1')
	$('#'+id).stop(true).slideDown(speed).css('opacity', '1')
	/*$('#'+spanId).css("background-color", colorFrom)
	$('#'+spanId).animate({ backgroundColor: bgColor}, "slow");
	
	
	$('#'+id).fadeOut(6000)*/
	
	
}





//ф-ция подсвечивания
function highlight(id, ok, clean)
{
	//alert('highlight!')
	if(typeof ok == 'undefined')
		ok = false
	if(typeof clean == 'undefined')
		clean = false
		
	if(ok)
		var color="#8dd848"
	else
		var color="#E0AFB2"
			
	var qqq=[]
	if(typeof id !='object' && typeof id != 'array')
		qqq.push(id)
	else qqq=id
	
	for(var i in qqq)
	{
		//alert(qqq[i])
		/*if(i == 0)
			$('#'+qqq[i]).focus()*/
		//alert('#'+qqq[i])
		$('#'+qqq[i]).css("background-color", color)
		$('#'+qqq[i]).animate({ backgroundColor: "#fff" }, "slow");
		
		if(clean)
		{
			$('#'+qqq[i]).val('')
		}
	}
}


//	выделение
function markError(id, clean)
{
	//alert(123)
	if(typeof clean == 'undefined')
		clean = false
			
	var qqq=[]
	if(typeof id !='object' && typeof id != 'array')
		qqq.push(id)
	else qqq=id
	
	for(var i in qqq)
	{
		//alert(qqq[i])
		/*if(i == 0)
			$('#'+qqq[i]).focus()*/
			
		//$('#'+qqq[i]).addClass("error")
		$('#'+qqq[i]).addClass("field-error")
		
		
		if(clean)
		{
			$('#'+qqq[i]).val('')
		}
	}
}



 
function markErrors(arr, wrapperId, animate)
{
	var color="#E0AFB2"
		
	for(var i in arr)
	{
		//alert(arr[i].field)
		$(''+wrapperId+' *[name='+arr[i].field+']').addClass('field-error')
		//alert(''+wrapperId+' *[name='+arr[i].field+']')
		//alert(i+'----'+arr[i])
		if(animate==true)
		{
			$(''+wrapperId+' *[name='+arr[i].field+']').css("background-color", color)
			$(''+wrapperId+' *[name='+arr[i].field+']').animate({ backgroundColor: "#fff" }, "slow");
		}
	}
}





function switchTo(id, className)
{
	$('.'+className).slideUp();
	$('#'+id).slideDown()
}