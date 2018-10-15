<?php
$item = $MODEL['item'];
$error = $MODEL['error'];
//vd($item);
$title = $item ? 'Редактирование:' : 'Создание:';
?>


<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>




<h1><?=$title?></h1>
<form id="form" method="" action="" onsubmit="editSubmit(); return false; ">
	<input type="hidden" name="id" value="<?=$item->id?>" />
	Мера объёма: <input type="text" name="name" value="<?=$item->name?>" />
	<p>
	<input type="submit" value="сохранить" />
</form>
