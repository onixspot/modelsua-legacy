<div>
	<form id="add_album" action="/albums" method="post">
		<div class="mb10 acenter hide">
			<input type="text" id="aid" value="0" />
			<input type="text" id="act" value="add_album" />
		</div>
		
		<? if(in_array($category_key, array('fashion', 'defile', 'advertisement', 'catalogs', 'contest'))){ ?>
			<? include 'forms/'.$category_key.'.php'; ?>
		<? } else { ?>
			<? include 'forms/default.php'; ?>
		<? } ?>

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
		form.data = {
			'uid': '<?=$uid?>',
			'category': '<?=$category_key?>'
		}
		form.onSuccess = function(resp)
		{
			if(resp.success)
			{
				if(typeof resp.aid != 'undefined')
					window.location = '/albums/album?aid='+resp.aid+'&uid=<?=$uid?>&show=add_photo';
				else
					window.location = '/albums?uid=<?=$uid?><?= $category_key ? '&filter='.$category_key : '' ?>&show=add_photo';
			}
		}
		
		$('#window-add-album #submit').click(function(){
			form.send();
		});
	});
</script>