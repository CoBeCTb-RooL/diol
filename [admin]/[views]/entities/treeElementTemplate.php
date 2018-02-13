<!--шаблон элемента дерева-->
<div id="tree-item-template" style="display: none;">
	<div class="item" id="item-_ID_">
		<a class="expand-btn" href="#expand-_ID_" onclick="Slonne.Entities.expandClick(_ID_); return false ">_PLUS_</a>
		
		<div class="loading"><img src="/<?=ADMIN_DIR?>/img/tree-loading.gif"></div>
		<div class="info">
			<span class="untouchable-sign"><i class="fa fa-lock"></i></span>
			<a class="sub-elements-btn" href="#sub_elements_list" onclick="Slonne.Entities.entitiesList(_ID_, '<?=Entity2::TYPE_ELEMENTS?>'); return false;" ><span class="fa fa-th"></span>:<span class="num">0</span></a>
			<input class="idx idx-_ID_" name="idx[_ID_]" type="text" value="_IDX_"  />
			<span class="id">_ID_)</span>
			<div class="name-wrapper">
				<a href="#get-entities-_ID_" onclick="Slonne.Entities.treeNameClick(_ID_); return false">_NAME_</a>
			</div>
		</div>
		<div class="subs" id="subs-_ID_"></div>
		<div class="pg" id="pages-_ID_"></div>
	</div>
</div>
<!--//шаблон элемента дерева-->