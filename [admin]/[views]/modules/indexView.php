<h1><?=$_GLOBALS['CURRENT_MODULE']->icon?> Модули</h1>

<div id="modules-list" class="modules"> 	
	<div class="inner"></div>
	<div class="loading" style="visibility: hidden; "> <img src="/<?=ADMIN_DIR?>/img/tree-loading.gif" > </div>
</div>

<!--форма редактирования-->
<div id="float"  style="display: none; min-width: 700px; max-width: 700px;">!!</div>

<script>
$(document).ready(function(){
	Slonne.Modules.list()
})
</script>