<div class="acenter">
	<input type="file" id="uploadify" />
</div>

<div class="mt10 acenter hide">
	<input type="text" id="uploadify-photos-list" />
</div>

<div id="uploadify-photos" class="mt10 p10" style="border: 1px solid #ccc; height: 200px; overflow: auto;">
	<div class="clear"></div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		
		$('#window-add-photo #submit').attr('disabled', true);
		
		$('#uploadify').uploadify({
			'uploader': '/uploadify.swf',
			'script': '/imgserve',
			'fileDataName': 'image',
			'buttonImg': '/buttons/upload_photo.png',
			'width': '153',
			'scriptData': 
			{
				'act': 'upload',
				'uid': '<?=session::get_user_id()?>',
				'key': 'image'
			},
			'cancelImg': '/cancel.png',
			'transparent': true,
			'folder': '/',
			'fileDesc': 'jpg; gif; png; jpeg;',
			'fileExt': '*.jpg;*.gif;*.png;*.jpeg;',
			'auto': true,
			'multi': true,
			'onError': function(event, queueID, fileObj, response)
			{
			},
			'onSelectOnce': function()
			{
				$('#uploadify-photos-list').val('');
				
				$('#uploadify-photos')
					.html('')
					.append($('<div />').addClass('clear'));
			},
			'onSelectOnce': function()
			{
				$('#blind-wait').show();
				$('#blind-wait').width($('#window-add-photo').width());
				$('#blind-wait').height($('#window-add-photo').height());
			},
			'onComplete': function(event, queueID, fileObj, pid, data)
			{
				var row = $('<div />');
				
				$(row).attr({
					'class': 'left mr10 mb10'
				})
				.css({
					'width': '100px',
					'height': '100px',
					'background': "url('/imgserve?pid="+pid+"&h=80') no-repeat center",
					'border': '1px dotted #ccc'
				});
				
				var val = $('#uploadify-photos-list').val();
				if(val != '')
					val = val +','+ pid;
				else
					val = pid;
				
				$('#uploadify-photos-list').val(val);
				
				$("#uploadify-photos > div[class='clear']").before($(row));
			},
			'onAllComplete': function(event, data) {
				$('#window-add-photo #submit').attr('disabled', false);
				$('#blind-wait').hide();
			}
		});
		
	});
</script>