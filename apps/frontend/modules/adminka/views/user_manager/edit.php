<div>
	
	<form id="adminka-edit" action="/adminka/user_manager?act=<? if($user_id != 0){ ?>modify&user_id=<?=$user_id?><? } else { ?>add<? } ?>" method="post">
		
		<? if($user_id != 0){ ?>
			<? $profile = profile_peer::instance()->get_item($user_id); ?>
		<? } ?>
		
		<? if(is_array($profile)){ ?>
		<div class="mb5">
			<div class="left pt5 mr5 aright" style="width: 100px">&nbsp;</div>
			<div class="left">
				<a href="/profile?id=<?=$profile["user_id"]?>"><?=profile_peer::get_name($profile, "&fn &ln")?></a>
			</div>
			<div class="clear"></div>
		</div>
		<? } ?>
		
		<div class="mb5">
			<div class="left pt5 mr5 aright" style="width: 100px">Имя: </div>
			<div class="left">
				<input type="text" id="first_name" value="<?=profile_peer::get_name($profile, "&fn")?>" />
			</div>
			<div class="clear"></div>
		</div>

		<div class="mb5">
			<div class="left pt5 mr5 aright" style="width: 100px">Фамилия: </div>
			<div class="left">
				<input type="text" id="last_name" value="<?=profile_peer::get_name($profile, "&ln")?>" />
			</div>
			<div class="clear"></div>
		</div>

		<div class="mb5">
			<div class="left pt5 mr5 aright" style="width: 100px">E-Mail: </div>
			<div class="left">
				<input type="text" id="email" value="<?=$profile["email"]?>" />
			</div>
			<div class="clear"></div>
		</div>

		<div class="mb5">
			<div class="left pt5 mr5 aright" style="width: 100px">&nbsp;</div>
			<div class="left">
				<input type="checkbox" id="hidden" <? if(is_array($profile)){ ?><? if($profile["hidden"]){ ?>checked<? } ?><? } else { ?>checked<? } ?> />
			</div>
			<div class="left pt5 ml5">Не публичный</div>
			<div class="clear"></div>
		</div>

		<div class="mb5">
			<div class="left pt5 mr5 aright" style="width: 100px">&nbsp;</div>
			<div class="left">
				<input type="button" id="submit" value="<? if(is_array($profile)){ ?>Изменить<? } else { ?>Создать<? } ?>" />
				<input
					type="button"
					value="Назад"
					onclick="window.location = '/adminka/user_manager'"
					/>
			</div>
			<div class="clear"></div>
		</div>
		
	</form>
	
</div>

<script type="text/javascript">
	
	$(document).ready(function(){
		
		var form = new Form("adminka-edit");
		
		form.onSuccess = function(data){
			if(data.success){
				window.location = "/adminka/user_manager";
			}
		}
		
		$("#adminka-edit #submit").click(function(){
			form.data["ident"] = "form";
			form.send();
		});
		
	});
	
</script>