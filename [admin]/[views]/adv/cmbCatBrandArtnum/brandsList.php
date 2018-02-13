<?php
$error = $MODEL['error'];
$brands = $MODEL['brandsList'];
?>



<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>



<style>
	label{display: block; margin: 0 0 9px 0; }
	.status-2{color: #ccc; }
	.brand a{ text-decoration: none; font-size: 13px; }
	.brand a.active{font-weight: bold; text-decoration: underline;  }
	.brand {margin: 0 0 6px 0; }
</style>


<script>

</script>
</script>


<?php
if(!$error)
{
	if($brands)
	{?>
	<div class="wrapper" >
	<?php 
		foreach($brands as $key=>$brand)
		{?>
		<div class="brand" ><a href="#" id="brand-<?=$brand->id?>" class="primary-cat status-<?=$brand->status->code?>" onclick="changeBrand(<?=$brand->id?>); return false; "> <span style="font-size: 10px;">(<?=$brand->id ?>)</span> <?=$brand->name?> </span> <span style="font-size: 10px; font-weight: normal; ">| арт.номеров: <b><?=count($brand->brandArtnumCombines)?></b></span> </a></div>
		<?php 
		}?>
	</div>
	<?php 
	}
	else 
	{?>
		Брендов нет.
	<?php 
	}?>
<?php 	
}
else
	echo $error;  
?>