<?php
$item = $MODEL['item'];
?>


<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>


<h1><?=$item ? $item->fio() : 'Клиент'?><span class="title-gray"> : <?=$item ? 'Редактирование' : 'Добавление'?></span></h1>

<form id="client-edit-form" enctype="multipart/form-data" method="post" action="/<?=ADMIN_URL_SIGN?>/clients/editSubmit" target="frame8" onsubmit="editSubmitStart(); " >
	<input type="hidden" name="clientId" value="<?=$item->id?>" />
	
	
	<div class="field-wrapper">
		<span class="label" >Активен: </span>
		<span class="value" >
			<input type="checkbox" name="active" <?=$item->status->code == Status::code(Status::ACTIVE)->code || !$item ? ' checked="checked" ' : ''?>>
		</span>
		<div class="clear"></div>
	</div>

    <div class="field-wrapper">
        <span class="label" >Фамилия<span class="required">*</span>: </span>
        <span class="value" >
			<input type="text" name="surname" value="<?=$item->surname?>" />
		</span>
        <div class="clear"></div>
    </div>
    <div class="field-wrapper">
        <span class="label" >Имя<span class="required">*</span>: </span>
        <span class="value" >
			<input type="text" name="name" value="<?=$item->name?>" />
		</span>
        <div class="clear"></div>
    </div>
    <div class="field-wrapper">
        <span class="label" >Отчество: </span>
        <span class="value" >
			<input type="text" name="fathername" value="<?=$item->fathername?>" />
		</span>
        <div class="clear"></div>
    </div>

    <hr>

    <div class="field-wrapper">
        <span class="label" >Телефон: </span>
        <span class="value" >
			<input type="text" name="phone" value="<?=$item->phone?>" />
		</span>
        <div class="clear"></div>
    </div>



    <div class="field-wrapper">
        <span class="label" >Адрес: </span>
        <span class="value" >
			<input type="text" name="address" value="<?=$item->address?>"  style="width: 400px;  "/>
		</span>
        <div class="clear"></div>
    </div>

    <hr>


    <div class="clear"></div>

    <?
    foreach ($item->media as $m)
    {?>
        <div class="pic-wrap" id="media-<?=$m->id?>" >
            <img class="delete" src="/<?=ADMIN_DIR?>/img/delete.png" onclick="deleteMedia(<?=$m->id?>)" alt="удалить" title="удалить">
            <div class="delete-loading">загрузка..</div>
            <div class="id">id: <b><?=$m->id?></b></div>
            <div class="src"><?=$m->src()?></div>
            <a href="<?=$m->src()?>" onclick="return hs.expand(this)" class="highslide ">
                <img src="<?=Media::img($m->path.'&height=100')?>" >
            </a>
            <textarea name="media[<?=$m->id?>]" ><?=$m->title?></textarea>
        </div>
    <?
    }?>

    <div class="clear"></div>
    <input type="file" name="media[]" multiple >




    <p>
    <div class="clear"></div>
    <input type="submit" value="Сохранить" />
	<div class="loading" style="display: none; ">секунду...</div>
	
</form>



<iframe name="frame8" style="display: none; "></iframe>





