<? $user_params = profile_peer::instance()->get_params($profile["user_id"]); ?>

<div id="profile-edit-frame-params">
	<form id="profile-edit-form-params" action="/profile/edit?id=<?=$profile["user_id"]?>&group=params">
		<div class="mt20 mb10">
			<div class="left pt5 mr5 aright" style="width: 200px"><?=t("Рост")?>: </div>
			<div class="left">
				<select id="growth">
					<option value="0" selected>&mdash;</option>
					<? for($i = 150; $i <= 210; $i++){ ?>
						<option value="<?=$i?>"><?=$i?></option>
					<? } ?>
				</select>
				<script type="text/javascript">
					$("#growth").val(<?=$user_params["growth"]?>);
				</script>
			</div>
			<div class="left pt5 ml5">см</div>
			<div class="clear"></div>
		</div>
		<div class="mb10">
			<div class="left pt5 mr5 aright" style="width: 200px"><?=t("Вес")?>: </div>
			<div class="left">
				<select id="weigth">
					<option value="0" selected>&mdash;</option>
					<? for($i = 35; $i <= 110; $i++){ ?>
						<option value="<?=$i?>"><?=$i?></option>
					<? } ?>
				</select>
				<script type="text/javascript">
					$("#weigth").val(<?=$user_params["weigth"]?>);
				</script>
			</div>
			<div class="left pt5 ml5">кг</div>
			<div class="clear"></div>
		</div>
		<div class="mb10">
			<div class="left pt5 mr5 aright" style="width: 200px"><?=t("Объемы")?>: </div>
			<div class="left pt5 mr5"><?=t("груди")?></div>
			<div class="left mr5">
				<input type="text" id="breast" value="<?=$user_params["breast"]?>" style="width: 32px;" />
			</div>
			<div class="left pt5 mr5"><?=t("см / талии")?></div>
			<div class="left mr5">
				<input type="text" id="waist" value="<?=$user_params["waist"]?>" style="width: 32px;" />
			</div>
			<div class="left pt5 mr5"><?=t("см / бедер")?></div>
			<div class="left mr5">
				<input type="text" id="hip" value="<?=$user_params["hip"]?>" style="width: 32px;" />
			</div>
			<div class="left pt5 mr5"><?=t("см")?></div>
			<div class="clear"></div>
		</div>
		<div class="mb10">
			<div class="left pt5 mr5 aright" style="width: 200px"><?=t("Цвет глаз")?>: </div>
			<div class="left">
				<select id="eye_color">
					<option value="0">&mdash;</option>
					<? foreach(profile_peer::$params["eye_color"] as $param_id => $param){ ?>
						<option value="<?=$param_id?>"><?=$param?></option>
					<? } ?>
				</select>
				<script type="text/javascript">
					$("#eye_color").val(<?=$user_params["eye_color"]?>);
				</script>
			</div>
			<div class="clear"></div>
		</div>
		<div class="mb10">
			<div class="left pt5 mr5 aright" style="width: 200px"><?=t("Цвет волос")?>: </div>
			<div class="left">
				<select id="hair_color">
					<option value="0" selected>&mdash;</option>
					<? foreach(profile_peer::$params["hair_color"] as $param_id => $param){ ?>
						<option value="<?=$param_id?>"><?=$param?></option>
					<? } ?>
				</select>
				<script type="text/javascript">
					$("#hair_color").val(<?=$user_params["hair_color"]?>);
				</script>
			</div>
			<div class="clear"></div>
		</div>
		<div class="mb10">
			<div class="left pt5 mr5 aright" style="width: 200px"><?=t("Длина волос")?>: </div>
			<div class="left">
				<select id="hair_length">
					<option value="0" selected>&mdash;</option>
					<? foreach(profile_peer::$params["hair_length"] as $param_id => $param){ ?>
						<option value="<?=$param_id?>"><?=$param?></option>
					<? } ?>
				</select>
				<script type="text/javascript">
					$("#hair_length").val(<?=$user_params["hair_length"]?>);
				</script>
			</div>
			<div class="clear"></div>
		</div>
		
		<div class="mt30">
			<div class="left pt5 mr5 aright" style="width: 200px">&nbsp;</div>
			<div class="left">
				<input type="button" id="submit" value="<?=t("Сохранить")?>" />
			</div>
			<div id="msg-success-params" class="left pt5 ml10 acenter hide" style="color: #090">
				<?=t("Данные сохранены успешно")?>
			</div>
			<div class="clear"></div>
		</div>
		
	</form>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		var form = new Form("profile-edit-form-params");
		form.onSuccess = function(data){
			if(data.success)
				$("#msg-success-params")
					.show()
					.css("opacity", "0")
					.animate({
						"opacity": "1"
					}, 256, function(){
						setTimeout(function(){
							$("#msg-success-params").animate({
								"opacity": "0"
							}, 256, function(){
								$(this).hide();
							})
						}, 1000);
					});
		}
		$("#profile-edit-form-params #submit").click(function(){
			form.send();
		});
	})
</script>