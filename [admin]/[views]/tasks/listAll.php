<?php
$groups = $MODEL['groups']; 
$globalTasks = $MODEL['globalTasks'];
//vd($globalTasks);
//vd($groups);
?>

<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>


<style>
.group{margin: 0 0 15px 0; }
.group-name{text-decoration: none; }
.group-name h1{display: inline-block; margin: 0; padding: 0; font-size: 16px; text-shadow: none; font-weight: bold;   }
.group-edit-btn{padding: 0 0 0 7px; display: none;}
.group:hover > .group-edit-btn{display: inline-block; }

.group > input[type=checkbox]{visibility: hidden; } 
/*.group:hover >input[type=checkbox]{visibility: visible; }*/

.main-group > input[type=checkbox]{visibility: hidden !important; }
.main-group > .group-edit-btn{display: none !important; }
.main-group > .add-btn{display: inline-block !important; }

.task{padding: 5px 0 5px 20px; cursor: default;  ; }
.task:hover{background: #f8f8f8; }
.task > .task-edit-btn{visibility: hidden; }
.task:hover > .task-edit-btn{visibility: visible; } 

.tasks{padding: 0 0 0 30px; }

.task-status-<?=Status::DONE?> .title{text-decoration: line-through; color: #999; }
.group-status-<?=Status::DONE?> h1{text-decoration: line-through; color: #999; }
</style>

<?php
if($groups)
{?>
	<?php 
	foreach($groups as $group)
	{?>
	<div class="group <?=!$group->id?' main-group ':''?> group-status-<?=$group->status->code?>" id="group-<?=$group->id?>">
		<input type="checkbox" id="group-done-<?=$group->id?>"  /> 
		<a href="#" onclick="tasksListToggle(<?=$group->id?>); return false; " class="group-name"><h1><?=$group->name?></h1> </a> 
		<a href="#" title="редактировать" class="group-edit-btn" onclick="groupEdit(<?=$group->id?>); return false; "><i class="fa fa-pencil-square-o " aria-hidden="true"></i></a>
		<a href="#" title="добавить" class="group-edit-btn add-btn" onclick="taskEdit('', '<?=$group->id?>'); return false; "><i class="fa fa-plus" aria-hidden="true"></i></a>
		<a href="#" title="в архив" class="group-edit-btn to-archive-btn" onclick="setStatus('<?=$group->id?>', '<?=Status::ARCHIVED?>'); return false; " style="padding: 0 0 0 40px; "><i class="fa fa-archive" aria-hidden="true"></i></a>
		<a href="#" title="удалить" class="group-edit-btn" onclick="if(confirm('Удалить группу задач?')){groupDelete(<?=$group->id?>)}; return false; " style="padding: 0 0 0 20px ; color: red; "><i class="fa fa-trash" aria-hidden="true"></i></a>
		<div class="tasks" >
			<?php 
			if($group->tasks)
			{?>
				<?php 
				$i=0;
				foreach($group->tasks as $key=>$task)
				{?>
				<div class="task task-status-<?=$task->status->code?>" id="task-<?=$task->id?>">
					<input type="checkbox" id="task-done-<?=$task->id?>" <?=($task->status->code==Status::DONE)?' checked="checked" ':''?> onclick="taskCheckboxClick(<?=$task->id?>)" /> <span class="title" ondblclick="taskEdit(<?=$task->id?>)"><?=$task->title?></span>
					<a href="#" title="редактировать" onclick="taskEdit(<?=$task->id?>)" class="task-edit-btn"><i class="fa fa-pencil-square-o " aria-hidden="true"></i></a>
					<a href="#" title="удалить" class="task-edit-btn" onclick="if(confirm('Удалить задачу?')){taskDelete(<?=$task->id?>)}; return false; " style="padding: 0 0 0 20px ; color: red; "><i class="fa fa-trash" aria-hidden="true"></i></a>
				</div>
				
				<?php 	
				}?>
			<?php
			}
			else
			{?>
				<div class="task">-задач нет-</div>
			<?php 	
			} 
			?>
		</div>
	</div>
	<?php 	
	}?>	
<?php
}
else
{?>
	Ничего нет.
<?php 	
} 
?>


<div style="margin: 36px 0 0 0 ;"><a href="#" onclick="groupEdit(); return false; "><i class="fa fa-plus" aria-hidden="true"></i> добавить группу</a></div>
