<div>
	<form id="add_album" action="/agency/albums?id=<?=$agency_id?>" method="post">
		<div class="mb10 acenter hide">
			<input type="text" id="aid" value="0" />
			<input type="text" id="act" value="add_album" />
		</div>
		
		<? include 'forms/default.php'; ?>

		<div class="mt10">
			<div class="left pt5" style="width: 128px">&nbsp;</div>
			<div class="left">
				<input type="button" id="submit" value="<?=t('Сохранить')?>" />
				<input type="button" id="cancel" value="<?=t('Отмена')?>" />
			</div>
			<div class="clear"></div>
		</div>
	</form>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		
		var form = new Form('add_album');
		form.onSuccess = function(response)
		{
			if(response.success)
			{
				if($("form[id='add_album'] #act").val() != 'add_album')
				{
					window.location.reload();
					return;
				}
					
				window.location = '/agency/album?aid='+response.aid+'&show=add_photo';
			}
		}
		
		$('#window-add-album #submit').click(function(){
			form.send();
		});
		
		$("#window-add-album input[id='cancel']").click(function(){
			$('#window-add-album').hide();
		});
		
	});
</script>