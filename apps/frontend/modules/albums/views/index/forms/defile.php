<div>
	<div class="left aright mr10 mt5" style="width: 144px"><?=t('Название мероприятия')?>:</div>
	<div class="left">
		<input type="text" id="event_title" style="width: 164px" />
	</div>
	<div class="clear"></div>
</div>

<div class="mt10">
	<div class="left aright mr10 mt5" style="width: 144px"><?=t('Страна')?>: </div>
	<div class="left">
		<select id="country" style="width: 164px;">
			<option value="0">&mdash;</option>
		</select>
	</div>
	<div class="clear"></div>
</div>
<div id="region_block" class="mt10 hide">
	<div class="left aright mr10 mt5" style="width: 144px"><?=t('Регион / Город')?>: </div>
	<div class="left">
		<select id="region" style="width: 164px;">
			<option value="0">&mdash;</option>
		</select>
	</div>
	<div class="clear"></div>
</div>
<div id="city_block" class="mt10">
	<div class="left aright mr10 mt5" style="width: 144px"><?=t('Город')?>: </div>
	<div class="left">
		<select id="city" class="hide" val="-1" style="width: 164px;">
			<option value="0">&mdash;</option>
			<option value="-1" selected><?=t('Другой')?></option>
		</select>
		<input type="text" id="another_city" value="" style="width: 164px;" />
	</div>
	<div class="clear"></div>
</div>

<div class="mt10">
	<div class="left aright mr10 mt5" style="width: 144px"><?=t('Период')?>:</div>
	<div class="left mr5">
		<?$mounth = ui_helper::get_mounth_list();?>
		<?=tag_helper::select('period_month', $mounth,array('id'=>'period_month'));?>
	</div>
	<div class="left">
		<select id="period_year">
			<option value="0">&mdash;</option>
			<? for($i = 0; $i < 30; $i++){ ?>
				<option value="<?=(date('Y')-$i)?>">
					<?=(date('Y')-$i)?>
				</option>
			<? } ?>
		</select>
	</div>
	<div class="clear"></div>
</div>
<div class="mt10">
	<div class="left aright mr10" style="width: 144px"><?=t('Показ')?>:</div>
	<div class="left">
		<div>
			<input type="checkbox" id="open_show" />
			<label for="open_show"><?=t('открывала')?></label>
		</div>
		<div>
			<input type="checkbox" id="close_show" />
			<label for="close_show"><?=t('закрывала')?></label>
		</div>
	</div>
	<div class="clear"></div>
</div>
<div class="mt10">
	<div class="left aright mr10 mt5" style="width: 144px"><?=t('Визажист')?>:</div>
	<div class="left">
		<input type="text" id="visagist" style="width: 164px" />
	</div>
	<div class="clear"></div>
</div>
<div class="mt10">
	<div class="left aright mr10 mt5" style="width: 144px"><?=t('Стилист')?>:</div>
	<div class="left">
		<input type="text" id="stylist" style="width: 164px" />
	</div>
	<div class="clear"></div>
</div>
<div class="mt10">
	<div class="left aright mr10 mt5" style="width: 144px"><?=t('Дизайнер')?>:</div>
	<div class="left">
		<input type="text" id="designer" style="width: 164px" />
	</div>
	<div class="clear"></div>
</div>
<div class="mt10">
	<div class="left aright mr10 mt5" style="width: 144px"><?=t('Фото дефиле в интернете')?>:</div>
	<div class="left">
		<div>
			<input type="text" id="link" style="width: 164px" />
		</div>
		<div class="fs10 cgray">
			<?=t('Вставте ссылку. Пример')?>: https://www.story.com.ua
		</div>
	</div>
	<div class="clear"></div>
</div>
<script type="text/javascript">
	var load_form;
	
	$(document).ready(function(){
		
		var location = {
			country: 0,
			region: 0,
			city: 0,
			another_city: ''
		}
		
		load_form = function(){
			if(typeof album.additional != 'undefined')
			{
				$('#event_title').val(album.additional.event_title);
				
				location.country = album.additional.country;
				location.region = album.additional.region;
				location.city = album.additional.city;
				location.another_city = album.additional.another_city;
				
				$('#another_city').val(album.additional.another_city);
				$('#period_month').val(album.additional.period_month);
				$('#period_year').val(album.additional.period_year);
				$('#visagist').val(album.additional.visagist);
				$('#stylist').val(album.additional.stylist);
				$('#designer').val(album.additional.designer);
				$('#link').val(album.additional.link);
				$('#open_show').attr('checked', album.additional.open_show ? true : false);
				$('#close_show').attr('checked', album.additional.close_show ? true : false);
				album = new Object();
				
				$("#country").val(location.country);
//				$("#country").change();
			}
		}
		
//		$("#country").change(function(){
//			var country_id = $(this).val();
//			$("#region > option").remove();
//			var option = $('<option />');
//			$(option)
//				.val(0)
//				.html('&mdash;');
//			$('#region').append($(option));
//			// 9908 - country_id Украины
//			if(country_id != 9908){
//				$('#region_block').hide();
//				$("#region").change();
//			} else {
//				$('#region_block').show();
//				$.post('/geo', {
//					'act': 'get_regions',
//					'country_id': country_id
//				}, function(resp){
//					$.each(resp.regions, function(){
//						var option = $('<option />');
//						$(option)
//							.val(this.region_id)
//							.html(this.name);
//						$('#region').append($(option));
//					});
//					$("#region").val(location.region);
//					$("#region").change();
//				}, 'json');
//			}
//		});
//		
//		$("#region").change(function(){
//			var country_id = $("#country").val();
//			var region_id = $(this).val();
//			$("#city > option").remove();
//			var option = $('<option />');
//			$(option)
//				.val(0)
//				.html('&mdash;');
//			$('#city').append($(option));
//			if(region_id != 0){
//				$('#city_block').show();
//				$.post('/geo', {
//					'act': 'get_cities',
//					'region_id': region_id
//				}, function(resp){
//					$.each(resp.cities, function(){
//						var option = $('<option />');
//						$(option)
//							.val(this.city_id)
//							.html(this.name);
//						$('#city').append($(option));
//					});
//					$("#city").val(location.city);
//					$("#city").change();
//				}, 'json');
//			} else if(country_id != 0 && country_id != 9908) {
//				$('#city_block').show();
//				$.post('/geo', {
//					'act': 'get_cities',
//					'country_id': country_id
//				}, function(resp){
//					for(var i = 0; i <= resp.cities.length; i++)
//					{
//						var option = $('<option />');
//						if(typeof resp.cities[i] != 'undefined'){
//							$(option)
//								.val(resp.cities[i].city_id)
//								.html(resp.cities[i].name);
//						} else {
//							$(option)
//								.val(-1)
//								.html('Другой');
//						}
//						$('#city').append($(option));
//					}
//					$("#city").val(location.city);
//					$("#city").change();
//				}, 'json')
//			} else {
//				$('#city_block').hide();
//				$("#city").change();
//			}
//		});
//		
//		$("#city").change(function(){
//			if($(this).val() == -1){
//				$(this).hide();
//				$("#another_city")
//					.val(location.another_city)
//					.show()
//					.focus();
//			}
//		});
//		
//		$("#another_city").blur(function(){
//			if($(this).val() == ""){
//				$(this)
//					.val("")
//					.hide();
//				$("#city")
//					.show()
//					.val(0)
//			}
//		});
		
		$.post("/geo", {
			"act" : "get_countries"
		}, function(data){
			$.each(data.countries, function(){
				var option = $("<option />");
				$(option)
					.attr("value", this.country_id)
					.html(this.name)
				$("#country").append($(option));
			});
			$("#country").val(0);
			$("#country").change();
		}, "json");
		
	});
</script>