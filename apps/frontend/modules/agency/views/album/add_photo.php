<div id="blind-wait" class="fs12 hide" style="position: absolute; background: #fff; opacity: 0.8; text-align: center;">
	<div clas="p20" style="margin-top: 128px;">
		<div>
			<img src="/loading2.gif" />
		</div>
		<div>
			Идет загрузка. Ждите...
		</div>
	</div>
</div>
<div>
	<form id="add_photo" action="/agency/album?aid=<?=$agency_album['id']?>" method="post">
		
		<div class="acenter mb10 hide">
			<input type="text" id="act" value="add_photo" />
		</div>
		
		<? include 'forms/default.php'; ?>
		
		<div class="mt10 acenter">
			<input type="button" id="submit" value="Сохранить" />
			<input type="button" id="cancel" value="Отмена" />
		</div>
	</form>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		var form = new Form('add_photo');
		form.data = {
			'act': 'add_photo'
		}
		form.onSuccess = function(response)
		{
			if(response.success)
				window.location = '/agency/album?aid=<?=$agency_album['id']?>';
		}
		
		$('#window-add-photo #submit').click(function(){
			$('#blind-wait').width($('#window-add-photo').width());
			$('#blind-wait').height($('#window-add-photo').height());
			$('#window-add-photo #submit').attr('disabled', true);
			$('#blind-wait').show();
			form.send();
		});
		
	});
</script>