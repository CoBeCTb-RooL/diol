<?php
//vd($MODEL);
?>

<h1><span class="fa fa-sliders"></span> Настройки сайта</h1>


<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>







<form class="settings" method="post" action="">
	<fieldset>
		<legend>Заголовок сайта</legend>
		
		<div class="row">
			<span class="label">Название сайта в заголовке:</span>
			<span class="input"><input type="text" name="title_postfix" size="40" value="<?=addslashes($_CONFIG['SETTINGS']['title_postfix'])?>"></span>
			<div class="hint">Будет всегда добавляться после указанного в контроллере заголовка.</div>
		</div>
		
		<div class="row">
			<span class="label">Разделитель частей заголовка:</span>
			<span class="input"><input type="text" name="title_separator" size="2" value="<?=addslashes($_CONFIG['SETTINGS']['title_separator'])?>"></span>
			<div class="hint">Символ, разделяющий части заголовка (например "Новости - abc.kz")</div>
		</div>
	</fieldset>
	
	
	
	
	<fieldset>
		<legend>Мета-теги</legend>
		
		<div class="row">
			<span class="label">Description:</span>
			<span class="input"><textarea name="description"><?=$_CONFIG['SETTINGS']['description']?></textarea></span>
		</div>
		
		<div class="row">
			<span class="label">Keywords:</span>
			<span class="input"><textarea name="keywords"><?=$_CONFIG['SETTINGS']['keywords']?></textarea></span>
		</div>
	</fieldset>
	
	
	<input type="submit" name="go_btn" value="Сохранить">
	
	
	
</form>