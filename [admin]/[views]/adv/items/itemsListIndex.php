<?php Core::renderPartial('adv/menu.php', $model);?>

<h1>Объявления</h1>

<div id="items" style="display: none; "> Загрузка....</div>

<script>
$(document).ready(function(){
	Slonne.Adv.AdvItems.LIST_OPTIONS.noTop=1;
	Slonne.Adv.AdvItems.LIST_OPTIONS.status='<?=$_REQUEST['status']?>';
	Slonne.Adv.AdvItems.LIST_OPTIONS.chosenUserId='<?=$_REQUEST['chosenUserId']?>';
	
	Slonne.Adv.AdvItems.itemsList();
});
</script>