<div>
	<div class="left mr5">
		<input type="button" value="Создать участника" onclick="window.location = '/adminka/umanager?frame=form'" />
	</div>
	<div class="left mr5">
		<input type="button" id="button-remove" value="Удалить участника(-ов)" />
	</div>
	<div class="right">
		<select id="folder">
			<option value="">Все (<?=$countAllItems?>)</option>
			<option value="archive" <? if($filter == 'archive'){ ?>selected<? } ?>>Архив (<?=$countInArchiveItems?>)</option>
			<option value="reserv" <? if($filter == 'reserv'){ ?>selected<? } ?>>Резерв (<?=$countInReservItems?>)</option>
		</select>
	</div>
	<div class="clear"></div>
</div>

<div class="table mt10">
	<table id="list" cellpadding="5" cellspacing="2" style="width: 100%; border: 1px solid #ccc;">
		<tr>
			<th align="center">
				<input type="checkbox" id="list-selectall" />
			</th>
			<th>ID</th>
			<th><?=t('Дескрипция')?></th>
			<th><?=t('Действия')?></th>
		</tr>
		<? foreach($list as $id){ ?>
			<? if( ! $item = xprofile_peer::instance()->get_item($id)) continue; ?>
			<tr id="list-item-<?=$id?>">
				<td align="center">
					<input type="checkbox" id="list-item-select-<?=$id?>" />
				</td>
				<td align="center"><?=$id?></td>
				<td>
					<div>
						<div class="left mr5">
							<? $imgUrl = '/imgserve?pid='.$item['pid'].'&w=100' ?>
							<? if($item['pid'] == 0){ ?>
								<? $imgUrl = '/no_image.png' ?>
							<? } ?>
							<div><img src="<?=$imgUrl?>" width="100px" /></div>
							<? if($item['active']){ ?>
								<div class="mt5 p5 acenter cwhite" style="background: #090; border-radius: 5px;">
									Активн<? if($item['sex'] != 1){ ?>ый<? } else { ?>ая<? } ?>
								</div>
							<? } ?>
						</div>
						<div class="left">
							<div>
								<a href="/profile?id=<?=$item['user_id']?>" target="_blank">
									<?=profile_peer::get_name($item)?>
								</a>
							</div>
							<div>
								<?=profile_peer::get_type($item['type'], $item['status'])?>
							</div>
						</div>
						<div class="clear"></div>
					</div>
				</td>
				<td align="center">
					<div><a href="/profile/edit?id=<?=$id?>" target="_blank">Изменить</a></div>
					<? if( ! $item['active'] && ! in_array($filter, array('archive', 'reserv'))){ ?>
						<div><a id="list-item-invite-<?=$id?>" href="javascript:void(0);">Пригласить (<?= (db_key::i()->exists('invite_nomodels_'.$id)) ? db_key::i()->get('invite_nomodels_'.$id) : 0 ?>)</a></div>
					<? } ?>
					<? if( ! in_array($filter, array('archive', 'reserv'))){ ?>
						<div>
							<div style="width: 100px;">
								<a id="list-item-move-<?=$id?>" href="javascript:void(0);">Переместить</a>
								<div id="block-list-item-move-<?=$id?>" class="pt5 pb5 hide" style="position: absolute; background: white; border: 1px solid #000000; box-shadow: 0px 1px 3px black; width: 98px;">
									<div><a id="list-item-movetoarchive-<?=$id?>" href="javascript:void(0);">В архив</a></div>
									<div><a id="list-item-movetoreserv-<?=$id?>" href="javascript:void(0);">В резерв</a></div>
								</div>
							</div>
						</div>
					<? } else { ?>
						<div><a id="list-item-recove-<?=$id?>" href="javascript:void(0);">Восстановить</a></div>
					<? } ?>
					<div><a id="list-item-remove-<?=$id?>" href="javascript:void(0);">Удалить</a></div>
				</td>
			</tr>
		<? } ?>
	</table>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		
		$("a[id^='list-item-invite']").click(function(){
			if(confirm('Вы действительно хотите отправить приглашение?'))
			{
				var uid = $(this).attr('id').split('-')[3];
				$.post('/adminka/umanager', {
					'act': 'send_mail',
					'alias': 'invite_nomodels',
					'uid': uid
				}, function(response){
					console.log(response);
				}, 'json');
			}
		})
		
		$("a[id^='list-item-recove']").click(function(){
			if(confirm('Вы дейстыительно хотите восстановить профиль?'))
			{
				var uid = $(this).attr('id').split('-')[3];
				$.post('/adminka/umanager', {
					'act': 'recove',
					'uid': uid
				}, function(response){
					if(response.success){
						$('#list-item-'+uid).remove();
						paint_over();
					}
				}, 'json');
			}
		});
		
		$("a[id^='list-item-movetoreserv']").click(function(){
			if(confirm('Вы дейстыительно хотите отправить профиль в резерв?'))
			{
				var uid = $(this).attr('id').split('-')[3];
				$.post('/adminka/umanager', {
					'act': 'move_to_reserv',
					'uid': uid
				}, function(response){
					if(response.success){
						$('#list-item-'+uid).remove();
						paint_over();
					}
				}, 'json');
			}
		});
		
		$("a[id^='list-item-movetoarchive']").click(function(){
			if(confirm('Вы дейстыительно хотите отправить профиль в архив?'))
			{
				var uid = $(this).attr('id').split('-')[3];
				$.post('/adminka/umanager', {
					'act': 'move_to_archive',
					'uid': uid
				}, function(response){
					if(response.success){
						$('#list-item-'+uid).remove();
						paint_over();
					}
				}, 'json');
			}
		});
		
		$('#folder').change(function(){
			var filter = $(this).val();
			if(filter != '')
				window.location = '/adminka/umanager?filter='+filter;
			else
				window.location = '/adminka/umanager';
		});
		
		$("a[id^='list-item-move-']").click(function(){
			var id = $(this).attr('id').split('-')[3];
			show_block('move-'+id);
		});
		
		$('#button-remove').attr('disabled', true)
		.click(function(){
			if(confirm('Вы действительно хотите удалить?'))
			{
				for(var i in selectedItems)
					remove(selectedItems[i], true);
			}
		});
		
		var selectedItems = new Array();
		
		$("input[id^='list-item-select']").change(function(){
			var uid = $(this).attr('id').split('-')[3];
			var state = $(this).attr('checked') != 'checked' ? 0 : 1;
			if(state != 0)
			{
				if($.inArray(uid, selectedItems) < 0)
					selectedItems.push(uid);
			}
			else
			{
				for(var i = 0; i < selectedItems.length; i++)
				{
					if(selectedItems[i] == uid)
						selectedItems.splice(i, 1);
				}
			}

			if(selectedItems.length > 0)
				$('#button-remove').attr('disabled', false);
			else
				$('#button-remove').attr('disabled', true);
		});
		
		$('#list-selectall').click(function(){
			var state = $(this).attr('checked') != 'checked' ? false : true;
			$("input[id^='list-item-select']").each(function(){
				$(this)
					.attr('checked', state)
					.change();
			});
		});
		
		$("a[id^='list-item-remove']").click(function(){
			var uid = $(this).attr('id').split('-')[3];
			remove(uid);
		});
		
		var remove = function(uid, force)
		{
			if(typeof force == 'undefined')
				force = false;
			
			if(force || confirm('Вы действительно хотите удалить этот профиль?'))
			{
				$.post('/adminka/umanager', {
					'act': 'remove',
					'uid': uid
				}, function(response){
					if(response.success)
					{
						$('#list-item-'+uid).remove();
						paint_over();
					}
				}, 'json');
			}
			
			
		}
		
		var paint_over = function()
		{
			var cnt = 0;
			var flag = false;
			$('#list tr').each(function(){
				if(cnt > 0)
				{
					flag = flag ? false : true;
					$(this).css('background', (flag ? '#fff' : '#eee'));
				}	
				
				cnt++;
			})
			
		}
		
		var show_block = function(key)
		{
			if($('#block-list-item-'+key).is(':visible'))
				return;
			
			$("#list-item-"+key)
				.attr('rel', 'show')
				.css('color', 'white')
				.parent()
					.css({
						'background': '#000000',
						'box-shadow': '0px 1px 3px black'
					});
			
			$('#block-list-item-'+key)
				.show()
				.mouseover(function(){
					show_block(key);
				})
				.mouseout(function(){
					hide_block(key)
				});
		}
		
		var hide_block = function(key)
		{
			$('#block-list-item-'+key).hide();
			$("#list-item-"+key)
				.attr('rel', '')
				.css('color', '#000000')
				.parent()
					.css({
						'background': '',
						'box-shadow': ''
					});
		}
		
		paint_over();
	});
</script>
