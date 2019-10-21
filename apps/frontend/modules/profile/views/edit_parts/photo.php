<div id="profile-edit-frame-photo">
		
	<div class="left mr10" style="border: 1px solid black;">
		<img id="photo_avatar" src="<? if($profile['pid']){ ?>/imgserve?pid=<?= $profile['pid']?><? } else { ?>/no_image.png<? } ?>" width="200" />
	</div>

	<div class="left">
		<div class="mb10"><?=t('Загрузите свою фотографию')?></div>
		<div>
			<input type="file" id="uploadify" />
			<script type="text/javascript">
				$(document).ready(function(){
					$('#uploadify').uploadify({
						'uploader': '/uploadify.swf',
						'script': '/imgserve',
						'fileDataName': 'image',
						'scriptData': {
							'act': 'upload',
							'uid': '<?=$user_id?>',
							'key': 'image'
						},
						'cancelImg': '/cancel.png',
						'transparent': true,
						'folder': '/',
						'fileDesc': 'jpg; gif; png; jpeg;',
						'fileExt': '*.jpg;*.gif;*.png;*.jpeg;',
						'auto': true,
						'multi': false,
						'onError': function(event, queueID, fileObj, response){
//								console.log(response);
						},
						'onComplete': function(event, queueID, fileObj, response, data){
							$.post('/profile/edit?group=photo', {
								'uid': '<?=$user_id?>',
								'pid': response
							}, function(resp){
								if(resp.success){
									$('#photo_avatar').attr('src', '/imgserve?pid='+resp.pid);
									$('#photo_avatar_preview > img').attr('src', '/imgserve?pid='+resp.pid);
								}
							}, 'json');
						}
					});
				});
			</script>
		</div>
		<div id="photo_crop" >
			<div class="mt10" style="border: 1px solid black; width: 100px; height: 100px;">
				<div id="photo_avatar_preview" style="width: 100px; height: 100px; overflow: hidden;">
					<img src="<? if($profile['pid']){ ?>/imgserve?pid=<?= $profile['pid']?><? } else { ?>/no_image.png<? } ?>" width="100px" height="100px" />
				</div>
			</div>

			<div class="mt10">
				<div class="left">
					<input type="button" value="<?=t('Сохранить')?>" id="profile-photo-save" />
				</div>
				<div id="msg-success-photo" class="left pt5 ml10 acenter hide" style="color: #090">
					<?=t('Данные сохранены успешно')?>
				</div>
			</div>
		</div>
	</div>

	<div class="clear"></div>

</div>
