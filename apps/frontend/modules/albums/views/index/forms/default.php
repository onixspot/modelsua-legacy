<div>
	<div class="left pt5 cgray" style="width: 128px"><?=t('Наименование')?>:</div>
	<div class="left">
		<input type="text" id="album_name" style="width: 272px;" />
	</div>
	<div class="clear"></div>
</div>

<div class="mt10">
	<div class="left pt5 cgray" style="width: 128px"><?=t('Описание')?>:</div>
	<div class="left">
		<textarea id="album_description" style="width: 272px; height: 80px"></textarea>
	</div>
	<div class="clear"></div>
</div>

<script type="text/javascript">
	var load_form;
	$(document).ready(function(){
		
		load_form = function(){
			if(typeof album.additional != 'undefined')
			{
				$('#album_name').val(album.name);
				$('#album_description').val(album.description);
				album = new Object();
			}
		}
		
	});
</script>