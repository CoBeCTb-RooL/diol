<?php
$item = $MODEL['item'];
//vd($item);
$error = $MODEL['error'];
?>



<?php 
if(!$error)
{?>
	<form id="admin-suggestion-form" method="" action="" onsubmit="adminSaveSuggestion(<?=$item->id?>); return false; ">
		<h2 style="margin: 0; "><?=$item->suggestionType->name?></h2>
		<!-- <div style="font-size: 1.1em; padding: 10px 10px 20px 10px; "><?=$item->text?></div> -->
		<div><textarea name="text" style="width: 100%;height: 40px; font-family: verdana;font-size: 13px;  " ><?=$item->text?></textarea></div>
		<div>Ответ: <textarea name="answer" style="width: 100%;height: 80px;font-family: verdana;font-size: 13px;  " ><?=$item->answer?></textarea></div>
		
		<!-- .row>span.label+span.value -->
		Имя: <input type="text" name="name" value="<?=$item->name?>" /><br>
		E-mail: <input type="text" name="email" value="<?=$item->email?>" /><br>
		Тел: <input type="text" name="phone" value="<?=$item->phone?>" /><br>
		
		<p>
		<input type="submit" value="сохранить" />
	</form>
<?php 	
}
else
{?>
	<?=$error?>
<?php 	
}?>