<?php
class Mail
{
	public $from;
	public $to;
	public $subject;
	public $msg;
	
	
	
	
	function send()
	{
		//http://phpclub.ru/detail/article/mail
		$bound = "qwerty"; // генерируем разделитель 
		
		//$header .= "Return-Path: ".$from."\n";
		
		//$header .= "Envelope-to: $to\n";
		$header  = "From: <".$this->from.">\n";
		$header .= "To: <".$this->to.">\n";
		$header .= "Subject: $this->subject\n";
		//$header .= "Date: ".date('r',time())."\n";
		$header .= "X-Mailer: PHP/".phpversion()."\n";
		$header.=	"Reply-To: $this->from\n";
		//$header .= "Message-ID: ".md5(uniqid(time()))."@aaa.kz\n";
		$header .= "Mime-Version: 1.0\n";
		$header .= "Content-Type: multipart/mixed; boundary=\"$bound\"\n";
		
		$body  = "--$bound\n";
		$body .= "Content-Type: text/html; charset=\"utf-8\"\n";
		$body .= "Content-Transfer-Encoding: 8bit\n\n";
		$body .= $this->msg;
		$body .= "\n\n";
		
		
		return mail($this->to, $this->subject, $body, $header);
	}
	
	
	
	
	
	
	
	function getMsgForNewCommentForAdvNotification($arr)
	{
		if($arr)
		{
			ob_start();
			?>
			<h4>Здравствуйте, <?=$arr['name']?></h4>
			К одному из Ваших объявлений поступил новый комментарий.
			<p>
			Чтобы увидеть его, пройдите по ссылке: <br/>
			<a href="<?=$arr['url']?>"><?=$arr['url']?></a>
			<p>
			С уважением,<br/> 
			&nbsp;&nbsp;&nbsp;&nbsp;команда <a href="http://<?=$_SERVER['HTTP_HOST']?>"><?=DOMAIN_FIRST_CAPITAL?></a>
			<?php
			$ret = ob_get_clean();
		}
		return $ret; 
	}
	
	
	
	
	
	function getMsgForRegistration($arr)
	{
		if($arr)
		{
			ob_start();
			?>
			<h4>Здравствуйте, <?=$arr['name']?></h4>
			<p>Вы успешно зарегистрированы на сайте <a href="http://<?=$_SERVER['HTTP_HOST']?>"><?=DOMAIN_CAPITAL?></a>!</p>

			<p>Чтобы активировать Вашу учётную запись, пройдите по ссылке:<br />
			<a href="<?=$arr['url']?>"><?=$arr['url']?></a></p>
			
			<p>
			С уважением,<br/> 
			&nbsp;&nbsp;&nbsp;&nbsp;команда <a href="http://<?=$_SERVER['HTTP_HOST']?>"><?=DOMAIN_FIRST_CAPITAL?></a>
			<?php
			$ret = ob_get_clean();
		}
		return $ret; 
	}
	
	
	
	
	
	# 	письмо с запросом на ВОССТАНОВЛЕНИЕ ПАРОЛЯ
	function getMsgForPasswordResetClaim($arr)
	{
		if($arr)
		{
			ob_start();
			?>
				<p><?=$arr['name']?>, Вы получили это письмо, потому что забыли пароль от учётной записи на сайте <a href="http://<?=$_SERVER['HTTP_HOST']?>"><?=DOMAIN_CAPITAL?></a>, и начали процедуру его восстановления.</p>

				<p>Чтобы восстановить пароль, пройдите по ссылке:<br />
				<a href="<?=$arr['url']?>"><?=$arr['url']?></a></p>
				
				<p>Если письмо пришло к Вам по ошибке, и Вы не пытались восстановить пароль, просто проигнорируйте это письмо.</p>
				
				<p>
				С уважением,<br/> 
				&nbsp;&nbsp;&nbsp;&nbsp;команда <a href="http://<?=$_SERVER['HTTP_HOST']?>"><?=DOMAIN_FIRST_CAPITAL?></a>

			<?php
			$ret = ob_get_clean();
		}
		return $ret; 
	}




    # 	письмо с НОВЫМ ПАРОЛЕМ
    function getMsgForNewPasswordInfo($arr)
    {
        if($arr)
        {
            ob_start();
            ?>
            <p><?=$arr['name']?>, Вы успешно восстановили пароль на сайте <a href="http://<?=$_SERVER['HTTP_HOST']?>"><?=DOMAIN_CAPITAL?></a>!</p>

            <p>Ваши авторизационные данные:</p>

            <p>e-mail: <strong><?=$arr['email']?></strong><br />
                пароль: <strong><?=$arr['password']?></strong></p>

            <p>
            С уважением,<br/>
            &nbsp;&nbsp;&nbsp;&nbsp;команда <a href="http://<?=$_SERVER['HTTP_HOST']?>"><?=DOMAIN_FIRST_CAPITAL?></a>

            <?php
            $ret = ob_get_clean();
        }
        return $ret;
    }




    # 	письмо ОБЪЯВЛЕНИЕ ОДОБРЕНО
    function advApproved($arr)
    {
        if($arr)
        {
            ob_start();
            ?>
            <p><?=$arr['name']?>, Ваше объявление на сайте <a href="http://<?=$_SERVER['HTTP_HOST']?>"><?=DOMAIN_CAPITAL?></a> прошло модерацию, и теперь видно всем!</p>
            <p>
                <?//vd($arr)?>
            Посмотреть его Вы можете здесь: <a href="http://<?=$_SERVER['HTTP_HOST']?><?=$arr['adv']->url()?>" target="_blank"><?=$arr['adv']->name?></a>
            <p>
            С уважением,<br/>
            &nbsp;&nbsp;&nbsp;&nbsp;команда <a href="http://<?=$_SERVER['HTTP_HOST']?>"><?=DOMAIN_FIRST_CAPITAL?></a>

            <?php
            $ret = ob_get_clean();
        }
        return $ret;
    }



    # 	письмо ОБЪЯВЛЕНИЕ УДАЛЕНО
    function advDeleted($arr)
    {
        if($arr)
        {
            ob_start();
            ?>
            <p><?=$arr['name']?>, Ваше объявление <b>"<?=$arr['adv']->name?>"</b> на сайте <a href="http://<?=$_SERVER['HTTP_HOST']?>"><?=DOMAIN_CAPITAL?></a> было удалено модератором.</p>
            <p>
                Причина: <?=$arr['adv']->reason ? : 'не указана'?>
            <p>
            С уважением,<br/>
            &nbsp;&nbsp;&nbsp;&nbsp;команда <a href="http://<?=$_SERVER['HTTP_HOST']?>"><?=DOMAIN_FIRST_CAPITAL?></a>

            <?php
            $ret = ob_get_clean();
        }
        return $ret;
    }
	
	
	
	
}