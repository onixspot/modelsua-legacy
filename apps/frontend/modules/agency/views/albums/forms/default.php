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
	var init_form;
	
	$(document).ready(function(){
		
		init_form = function(data)
		{
			$('#album_name').val((typeof data.name != 'undefined') ? data.name : '');
			$('#album_description').val((typeof data.description != 'undefined') ? data.description : '');
			$("form[id='add_album'] #aid").val((typeof data.aid != 'undefined') ? data.aid : 0);
			$("form[id='add_album'] #act").val((typeof data.act != 'undefined') ? data.act : 'add_album');
		}
		
	});
</script>