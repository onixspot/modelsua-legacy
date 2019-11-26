<div>
	<input type="button" value="Назад" onclick="window.location = '/adminka/umanager'" />
</div>
<div class="mt10">
	<form id="umanager-form" action="/adminka/umanager" method="post">
		
		<div id="msgid-systemerror" class="p10 mb10 hide" style="background: #ff9999; border-top: 1px solid #990000; border-bottom: 1px solid #990000">
			Ошибка: Системная ошибка
		</div>
		
		<div>
			<div class="left mt5" style="width: 100px;">Имя:</div>
			<div class="left">
				<input type="text" id="first_name" style="width: 200px;" /><span style="color: red"> * </span>
			</div>
			<div id="msgid-first_name" class="left ml5 mt5 hide" style="color: red">
				Ошибка: Пустое поле.
			</div>
			<div class="clear"></div>
		</div>

		<div class="mt10">
			<div class="left mt5" style="width: 100px;">Фамилия:</div>
			<div class="left">
				<input type="text" id="last_name" style="width: 200px;" /><span style="color: red"> * </span>
			</div>
			<div id="msgid-last_name" class="left ml5 mt5 hide" style="color: red">
				Ошибка: Пустое поле.
			</div>
			<div class="clear"></div>
		</div>

		<div class="mt10">
			<div class="left mt5" style="width: 100px;">Email:</div>
			<div class="left">
				<input type="text" id="email" style="width: 200px;" />
			</div>
			<div id="msgid-email" class="left ml5 mt5 hide" style="color: red">
				Ошибка: Такой пользователь уже есть.
			</div>
			<div class="clear"></div>
		</div>

		<div class="mt10">
			<div class="left mt5" style="width: 100px;">Группа:</div>
			<div class="left">
				<select id="group" style="width: 200px;">
					<option value="0">&mdash;</option>
					<? foreach(profile_peer::get_types_list() as $typeKey => $typeVal){ ?>
						<option value="<?=$typeKey?>"><?=$typeVal['type']?></option>
					<? } ?>
				</select><span style="color: red"> * </span>
			</div>
			<div id="msgid-group" class="left ml5 mt5 hide" style="color: red">
				Ошибка: Пустое поле.
			</div>
			<div class="clear"></div>
		</div>
		
		<div class="mt10">
			<div class="left mt5" style="width: 100px;">Статус:</div>
			<div class="left">
				<select id="status" style="width: 200px;">
					<option value="0">&mdash;</option>
				</select><span style="color: red"> * </span>
			</div>
			<div id="msgid-status" class="left ml5 mt5 hide" style="color: red">
				Ошибка: Пустое поле.
			</div>
			<div class="clear"></div>
		</div>
		
		<div id="block-agency" class="mt10 hide">
			<div class="left mt5" style="width: 100px;">Агентство:</div>
			<div class="left">
				<select id="agency" style="width: 200px;">
					<option value="0">&mdash;</option>
					<? $agency_list = agency_peer::instance()->get_list(array("public" => 1, 'page_active' => true), array(), array("id ASC")) ?>
					<? foreach($agency_list as $agency_id){ ?>
						<? $agency = agency_peer::instance()->get_item($agency_id); ?>
						<option value="<?=$agency["id"]?>"><?=$agency["name"]?></option>
					<? } ?>
				</select>
			</div>
			<div class="clear"></div>
		</div>

		<div class="mt10">
			<div class="left mt5" style="width: 100px;">&nbsp;</div>
			<div class="left">
				<input type="submit" value="Создать" />
			</div>
			<div class="clear"></div>
		</div>
		
	</form>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		
		var status = new Array();
		
		$('#status').change(function(){
			var val = $(this).val();
			if(val == 42)
				$('#block-agency').show();
			else
				$('#block-agency').hide();
		});
		
		<? foreach(profile_peer::get_types_list() as $typeKey => $typeVal){ ?>
			<? foreach($typeVal['status'] as $stKey => $stVal){ ?>
				status.push({'typeId': '<?=$typeKey?>', 'statusId': '<?=$stKey?>', 'status': '<?=$stVal?>'});
			<? } ?>
		<? } ?>
		
		$('#group').change(function(){
			var typeId = $(this).val();
			
			$('#status').empty();
			
			var option = $('<option />');
			$(option)
				.val(0)
				.html('&mdash;');
				
			$('#status').append($(option));
			
			$.each(status, function(){
				if(this.typeId != typeId)
					return;
					
				var option = $('<option />');
				$(option)
					.val(this.statusId)
					.html(this.status);
				
				$('#status').append($(option));
			});
		});
		
		var form = new Form('umanager-form');
		form.onSuccess = function(response)
		{
			if(response.success)
				window.location = '/adminka/user_manager?filter=all';
			else
			{
				$("div[id^='msgid']").hide();
				var msgid = response.msgid.split('&');
				for(var i in msgid)
				{
					$('#msgid-'+msgid[i]).show();
				}
			}
		}
		$("#umanager-form input[type='submit']").click(function(){
			form.data['act'] = 'add';
			form.send();
		});
		
	});
</script>
