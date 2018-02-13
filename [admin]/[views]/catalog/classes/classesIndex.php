<div class="cat-types"> 
	<div class="inner"></div>
	<div class="loading" style="visibility: hidden; "> <img src="/<?=ADMIN_DIR?>/img/tree-loading.gif" > </div>
</div>


<!--форма редактирования-->
<div id="float"  style="display: none; min-width: 450px; max-width: 700px;">!!</div>

<?php
//vd($_REQUEST); 
?>
<script>
$(document).ready(function(){
	Slonne.Catalog.Classes.classesList();
	<?php
	if($id=$_REQUEST['id'])
	{?>
		Slonne.Catalog.Classes.classesEdit(<?=$id?>);
	<?php 	
	}?>
})
</script>