<?php
$cat = $MODEL['cat'];
$classes = $MODEL['classes'];
?>



<?php
if($cat)
{?>
	<div class="view" >
		<form id="edit-form" enctype="multipart/form-data" method="post" action="/<?=ADMIN_URL_SIGN?>/catalog/interface/catClassEditSubmit" target="frame2" onsubmit="Slonne.Catalog.Interface.catClassEditSubmitStart()" >				
			<input type="hidden" name="id" value="<?=$cat->id?>">
			
				<h1><?=$cat->name?> <span class="gray"> : Настройка класса</span></h1>
				
					<div class="field-wrapper" >
						<span class="label" style="padding-top: 10px;">Класс: </span>
						<span class="value" >
							<div style="margin: 10px 0 ;">
								<label>
									<input type="radio" name="class" value="" checked="checked"> 
									<b style="font-size: 15px;">-без класса-</b>
								</label>
							</div>
							<?php
							//vd($classes);
							foreach($classes as $key=>$class)
							{?>
								<div style="margin: 10px 0 ;">
									<label>
										<input type="radio" name="class" value="<?=$class->id?>"  <?=($cat->classId == $class->id ? ' checked="checked" ' : '')?> > 
										<b style="font-size: 15px;"><?=$class->name?> : </b>
										<?php
										$tmp=null;
										$class->initProps($activeOnly=true);
										foreach($class->props as $key=>$prop)
											$tmp[] = $prop->name;
										echo '<span style="font-size: 11px;">'.join(', ', $tmp).'</span>'; 
										?>
									</label>
								</div>
							<?php 	
							} 
							?>
							
						</span>
						<div class="clear"></div>
					</div>
				
				
					

			<input type="submit" value="Сохранить">
				
			<span class="loading" style="visibility: hidden">Секунду...</span>
			<div class="info"></div>
		</form>
	</div>
	
	<iframe name="frame2" style="display: none; width: 99%; border: 1px dashed #0e0e0e; height: 400px;">1</iframe>
	
<?php 	
}
else 
{
	echo 'Категория не найдена. ['.$_REQUEST['id'].']';
}
?>

