<div class="cat-types"> 
	<div class="inner"></div>
	<div class="loading" style="visibility: hidden; "> <img src="/<?=ADMIN_DIR?>/img/tree-loading.gif" > </div>
</div>


<!--форма редактирования-->
<div id="float"  style="display: none; min-width: 450px; max-width: 700px;">!!</div>

<script>
$(document).ready(function(){
	Slonne.Catalog.Props.propsList();
	<?php
	if($id=$_REQUEST['propId'])
	{?>
		Slonne.Catalog.Props.propsEdit(<?=$id?>);
	<?php 	
	}?>
})
</script>