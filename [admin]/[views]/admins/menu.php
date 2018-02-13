<?php 
$sectionActive = $MODEL['sectionActive'];
?>

<style>
.submenu .active{background: #203042; color: #fff; text-decoration: none; padding: 4px 6px;  }
</style>

<div class="submenu">
	<a href="/<?=ADMIN_URL_SIGN?>/admin" onclick="adminsList(); return false; " class="<?=$sectionActive=='admins' ? 'active' : ''?>">Администраторы</a>
	<a href="/<?=ADMIN_URL_SIGN?>/adminGroup" onclick="groupsList();return false; " class="<?=$sectionActive=='groups' ? 'active' : ''?>">Группы</a>
</div>