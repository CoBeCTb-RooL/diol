<?php
$list = $MODEL['list']; 
?>


<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>


<?php 
if(count($list) )
{?>

	<table class="t">
		<tr>
			<th>id</th>
			<th>Сущность</th>
			<th>Код</th>
			<th>joint</th>
			<th>linear</th>
			<th>Блоки</th>
			<th>Элементы</th>
			<th>Удалить</th>
		</tr>
		<?php 
		foreach($list as $key=>$essence)
		{
			//$essence->initFields();
			?>
			<tr id="row-<?=$essence->id?>"  >
				<td><?=$essence->id?></td>

				<td class="bold" ><?=$essence->name?></td>
				<td ><?=$essence->code?></td>
				
				<td class="center"><?=($essence->jointFields ? '<span style="color: green; font-weight: normal;">да</span>' : '<span style="color: #ccc;">нет</span>')?></td>
				<td class="center"><?=($essence->linear ? '<span style="color: green; font-weight: normal;">да</span>' : '<span style="color: #ccc;">нет</span>')?></td>
				
				<?php
				if($essence->jointFields || $essence->linear)
				{?>
					<td colspan="2" class="center">Элементы <a href="#fields" onclick="Slonne.Fields.list(<?=$essence->id?>, '<?=Entity2::TYPE_ELEMENTS?>'); return false; ">Поля (<?=count($essence->fields[Entity2::TYPE_ELEMENTS])?>)</a></td>
					
				<?php 
				} 
				else
				{?>
					<td class="center">Блоки <a href="#fields" onclick="Slonne.Fields.list(<?=$essence->id?>, '<?=Entity2::TYPE_BLOCKS?>'); return false; ">Поля (<?=count($essence->fields[Entity2::TYPE_BLOCKS])?>)</a></td>
					<td class="center">Элементы <a href="#fields" onclick="Slonne.Fields.list(<?=$essence->id?>, '<?=Entity2::TYPE_ELEMENTS?>'); return false; ">Поля (<?=count($essence->fields[Entity2::TYPE_ELEMENTS])?>)</a></td>
				<?php 	
				}
				?>
				
				
				<td class="center"><a href="#delete"  onclick="if(confirm('Уверены?')){delete1(<?=$essence->id?>);} return false;" style="color: red; font-size: 10px; ">&times; удалить</a></td>
			</tr>
		<?php 
		}?>
	</table>
	

	
	<input id="add-btn" type="button" onclick="edit(); " value="+ сущность">
<?php
}
else
{?>
	Ничего нет.
<?php 	
} 
?>