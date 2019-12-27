<div class="mt30 fs12">
	
	<div id="window-foreign_work" class="pt10 pl10 pr10 fs12 hide" style="position: absolute; background: #fff; box-shadow: 0px 0px 5px black; width: 380px;">
		
		<form id="foreign_works" action="/profile?id=<?=$user_id?>" method="post">
			<div>
				<div class="left pt5 mr5 aright" style="width: 145px;"><?=t('Агентство')?>: </div>
				<div class="left">
					<input type="text" id="company_name" style="width: 230px;" />
				</div>
				<div class="clear"></div>
			</div>
			<div class="mt10">
				<div class="left pt5 mr5 aright" style="width: 145px"><?=t('Страна')?>: </div>
				<div class="left">
					<select id="country" style="width: 230px;">
						<option value="0">&mdash;</option>
					</select>
				</div>
				<div class="clear"></div>
			</div>
			<div id="region_block" class="mt10 hide">
				<div class="left pt5 mr5 aright" style="width: 145px"><?=t('Регион / Город')?>: </div>
				<div class="left">
					<select id="region" style="width: 230px;">
						<option value="0">&mdash;</option>
					</select>
				</div>
				<div class="clear"></div>
			</div>
			<div id="city_block" class="hide mt10">
				<div class="left pt5 mr5 aright" style="width: 145px"><?=t('Город')?>: </div>
				<div class="left">
					<select id="city" style="width: 230px;">
						<option value="0">&mdash;</option>
					</select>
					<input class="hide" type="text" id="another_city" value="<?=$profile["another_city"]?>" style="width: 230px;" />
				</div>
				<div class="clear"></div>
			</div>
			<div class="mt10">
				<div class="left pt5 mr5 aright" style="width: 145px"><?=t('Период')?>&nbsp;<?=t('c')?>: </div>
				
				<div class="left mr5">
					<? $mounth = ui_helper::get_mounth_list(); ?>
					<?=tag_helper::select('from_month', $mounth, array('id' => 'from_month', 'style' => 'width: 50px;')); ?>
				</div>
				<div class="left mr10">
					<input type="text" maxlength="4" id="from_year" value="<?=date('Y')?>" style="width: 40px;" />
				</div>
				<div class="left pt5 mr5">
					<?=t('по')?>:
				</div>
				<div class="left mr5">
					<? $mounth = ui_helper::get_mounth_list(); ?>
					<?=tag_helper::select('to_month', $mounth, array('id' => 'to_month', 'style' => 'width: 50px;')); ?>
				</div>
				<div class="left">
					<input type="text" maxlength="4" id="to_year" value="<?=date('Y')?>" style="width: 40px;" />
				</div>
				<div class="clear"></div>
			</div>
			<div class="mt10">
				<div class="left pt5 mr5 aright" style="width: 145px"><?=t('Описание')?>: </div>
				<div class="left">
					<textarea id="work_description" style="width: 230px; height: 64px;"></textarea>
				</div>
				<div class="clear"></div>
			</div>
			<div id="msg-success-foreign_works" class="mt10 acenter hide" style="color: #090;">
				<?=t('Данные сохранены успешно')?>
			</div>
			<div id="msg-error-foreign_works" class="mt10 acenter hide" style="color: #900;">
				<?=t('Ошибка: проверьте, все ли данные введены правильно')?>
			</div>
			<div class="mt10">
				<div class="left pt5 mr5 aright" style="width: 145px">&nbsp;</div>
				<div class="left mr10">
					<input type="button" id="submit" value="<?=t('Сохранить')?>" />
				</div>
				<div class="left">
					<input type="button" value="<?=t('Отмена')?>" onclick="$('#window-foreign_work').hide();" />
				</div>
				<div class="clear"></div>
			</div>
		</form>
		
	</div>
	
	<div>
		<div class="left square_p pl15 mb10 fs12 ucase bold">
			<a class="cblack" href='javascript:void(0);'><?=t('Работа за границей')?></a>
		</div>
		<? if(session::has_credential('admin') || session::get_user_id() == $user_id){ ?>
			<div class="right">
				<a
					class="underline cgray"
					href="javascript:void(0);"
					onclick="
						$('#window-foreign_work').show();
					"
					><?=t('Добавить работу')?></a>
			</div>
		<? } ?>
		<div class="clear"></div>
	</div>
	<? if(count($foreignWorks) > 0){ ?>
		<? $cnt = 0; ?>
		<? foreach($foreignWorks as $foreignWorkId){ ?>
			<? $foreignWork = user_foreign_works::instance()->get_item($foreignWorkId); ?>
			<div id="foreign_work-<?=$foreignWork['id']?>" class="pt10 pb5" style="<? if($cnt > 0){ ?>border-top: 1px solid #eee<? } ?>">
				<? if(session::has_credential('admin') || session::get_user_id() == $user_id){ ?>
					<div class="fs10 aright pb5">
						<a id="remove-foreign_work-<?=$foreignWork['id']?>" class="fs10" href="javascript:void(0);"><?=t('Удалить')?></a>
					</div>
				<? } ?>
				<div>
					<div class="left" style="width: 200px">
						<div><?=profile_peer::get_location($foreignWork)?> :: <?=$foreignWork['company_name']?></div>
					</div>
					<div class="right aright cgray" style="width: 200px;">
						<?=$foreignWork['from_month']?>, <?=$foreignWork['from_year']?> - <?=$foreignWork['to_month']?>, <?=$foreignWork['to_year']?>
					</div>
					<div class="clear"></div>
				</div>
			</div>
			<div class="fs11 cgray pb10"><?=$foreignWork['work_description']?></div>
			<? $cnt++ ?>
		<? } ?>
	<? } else { ?>
		<div>
			<div class="left acenter cgray" style="width: 400px; height: 57px; background: #eee; padding-top: 45px;">
				<?=t("Тут еще нет работ")?>
			</div>
		</div>
	<? } ?>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		
		$("a[id^='remove-foreign_work']").click(function(){
			if(confirm('<?=t('Вы действительно хотите удалить работу?')?>')){
				var id = $(this).attr('id').split('-')[2];
				$.post('/profile?id=<?=$user_id?>',{
					'act': 'remove_foreign_work',
					'ufwId': id
				}, function(resp){
					if(resp.success)
					{
						$('#foreign_work-'+resp.ufwId).remove();
					}
				}, 'json')
			}
		});
		
		var form = new Form('foreign_works');
		form.onSuccess = function(resp)
		{
			if(resp.success)
			{
				$("#msg-success-foreign_works")
					.show()
					.css("opacity", "0")
					.animate({
						"opacity": "1"
					}, 256, function(){
						setTimeout(function(){
							$("#msg-success-foreign_works").animate({
								"opacity": "0"
							}, 256, function(){
								$(this).hide();
								$('#window-foreign_work').hide();
								window.location = '<?=$_SERVER['REQUEST_URI']?>'
							})
						}, 1000);
					});
			} else {
				$("#msg-error-foreign_works")
					.show()
					.css("opacity", "0")
					.animate({
						"opacity": "1"
					}, 256, function(){
						setTimeout(function(){
							$("#msg-error-foreign_works").animate({
								"opacity": "0"
							}, 256, function(){
								$(this).hide();
							})
						}, 2000);
					});
			}
		}
		$('#foreign_works #submit').click(function(){
			form.data['act'] = 'foreign_works';
			form.send();
		});
		
		$("#window-foreign_work #country").change(function(){
			var country_id = $(this).val();
			$("#window-foreign_work #region > option").remove();
			var option = $('<option />');
			$(option)
				.val(0)
				.html('&mdash;');
			$('#window-foreign_work #region').append($(option));
			// 9908 - country_id Украины
			if(country_id != 9908){
				$('#window-foreign_work #region_block').hide();
				$("#window-foreign_work #region").change();
			} else {
				$('#window-foreign_work #region_block').show();
				$.post('/geo', {
					'act': 'get_regions',
					'country_id': country_id
				}, function(resp){
					$.each(resp.regions, function(){
						var option = $('<option />');
						$(option)
							.val(this.region_id)
							.html(this.name);
						$('#window-foreign_work #region').append($(option));
					});
					$("#window-foreign_work #region").val(0);
					$("#window-foreign_work #region").change();
				}, 'json');
			}
		});
		
		$("#window-foreign_work #region").change(function(){
			var country_id = $("#window-foreign_work #country").val();
			var region_id = $(this).val();
			$("#window-foreign_work #city > option").remove();
			var option = $('<option />');
			$(option)
				.val(0)
				.html('&mdash;');
			$('#window-foreign_work #city').append($(option));
			if(region_id != 0){
				$('#window-foreign_work #city_block').show();
				$.post('/geo', {
					'act': 'get_cities',
					'region_id': region_id
				}, function(resp){
					$.each(resp.cities, function(){
						var option = $('<option />');
						$(option)
							.val(this.city_id)
							.html(this.name);
						$('#window-foreign_work #city').append($(option));
					});
					$("#window-foreign_work #city").val(0);
					$("#window-foreign_work #city").change();
				}, 'json');
			} else if(country_id != 0 && country_id != 9908) {
				$('#window-foreign_work #city_block').show();
				$.post('/geo', {
					'act': 'get_cities',
					'country_id': country_id
				}, function(resp){
					for(var i = 0; i <= resp.cities.length; i++)
					{
						var option = $('<option />');
						if(typeof resp.cities[i] != 'undefined'){
							$(option)
								.val(resp.cities[i].city_id)
								.html(resp.cities[i].name);
						} else {
							$(option)
								.val(-1)
								.html('Другой');
						}
						$('#window-foreign_work #city').append($(option));
					}
					$("#window-foreign_work #city").val(0);
					$("#window-foreign_work #city").change();
				}, 'json')
			} else {
				$('#window-foreign_work #city_block').hide();
				$("#window-foreign_work #city").change();
			}
		});
		
		$("#window-foreign_work #city").change(function(){
			if($(this).val() == -1){
				$(this).hide();
				$("#window-foreign_work #another_city")
					.show()
					.focus();
			}
		});
		
		$("#window-foreign_work #another_city").blur(function(){
			if($(this).val() == ""){
				$(this)
					.val("")
					.hide();
				$("#window-foreign_work #city")
					.show()
					.val(0)
			}
		});
		
		$.post("/geo", {
			"act" : "get_countries"
		}, function(data){
			$.each(data.countries, function(){
				if(this.country_id != 9908)
				{
					var option = $("<option />");
					$(option)
						.attr("value", this.country_id)
						.html(this.name)
					$("#window-foreign_work #country").append($(option));
				}
			});
			$("#window-foreign_work #country").val(0);
			$("#window-foreign_work #country").change();
		}, "json");
	});
</script>