<?$filters = mailing_peer::parse_filters($post['filters']);?>
<script type="text/javascript">
$(function(){
	
});
$().ready(function() {
    
	$("select").removeAttr('selected');
	$("select").not('#country,#region,#city').multiselect();
	
	$('[name^="filter_"]').change(function(){
	    $('#'+$(this).attr('id')+'_box').toggleClass('hide');
	});
	
	var form = new Form("mailer-form");
	
	form.onSuccess = function(data){
	    if(data.success == false){
		$('.errors').html(data.reason);
		$('.errors').fadeIn(300,function(){$(this).fadeOut(3000)});
	    } else {
		$('.success').fadeIn(300, function() { $(this).fadeOut(2000); });
	    }
	}
	
	$('input[id="add-mail-list"]').click(function(){
	    form.data['maillist_type']=$('input[name="maillist_type[]"]:checked').val();
	    form.data['body'] = parseInt($('input[name="maillist_type[]"]:checked').val()) ? $('#body').val() : $('#body').elrte('val');
	    form.send();
	});
	
	$('input[id="save-template"]').click(function(){
	    form.data['maillist_type'] = 2;
	    form.data['mail_id'] = parseInt('<?=request::get_int('id')?>');
	    form.data['body'] = parseInt($('input[name="maillist_type[]"]:checked').val()) ? $('#body').val() : $('#body').elrte('val');
	    form.send();
	});
	
	
	
        var opts = {
                cssClass : 'el-rte',
                lang     : 'ru',
                height   : 450,
                toolbar  : 'complete',
                cssfiles : ['https://css.<?=conf::get('server')?>/elrte.css','https://css.<?=conf::get('server')?>/elfinder.css'],
                fmOpen : function(callback) {
                    $('#finder').elfinder({
                            url : '/connectors/elfinder',
                            lang : 'en',
                            dialog : { modal : true, title : 'elFinder - file manager for web' },
                            closeOnEditorCallback : true,
                            editorCallback : callback
                    })
                }
        }
	var html = eval(<?=json_encode(array(preg_replace("#\"#", "'", stripslashes($post['body']))))?>);
	
	$('#body').elrte(opts);
	$('#body').elrte('val',html[0]);
	
	$("#country").change(function(){
		var country_id = $(this).val();
		$("#region > option").remove();
		var option = $('<option />');
		$(option)
			.val(0)
			.html('&mdash;');
		$('#region').append($(option));
		// 9908 - country_id Украины
		if(country_id != 9908){
			$('#region_block').hide();
			$("#region").change();
		} else {
			$('#region_block').show();
			$.post('/geo', {
				'act': 'get_regions',
				'country_id': country_id
			}, function(resp){
				$.each(resp.regions, function(){
					var option = $('<option />');
					$(option)
						.val(this.region_id)
						.html(this.name);
					$('#region').append($(option));
				});
				//$('#region').multiselect();
			}, 'json');
		}
	});

	$("#region").change(function(){
		var country_id = $("#country").val();
		var region_id = $(this).val();
		$("#city > option").remove();
		var option = $('<option />');
		$(option)
			.val(0)
			.html('&mdash;');
		$('#city').append($(option));
		if(region_id != 0){
			$('#city_block').show();
			$.post('/geo', {
				'act': 'get_cities',
				'region_id': region_id
			}, function(resp){
				$.each(resp.cities, function(){
					var option = $('<option />');
					$(option)
						.val(this.city_id)
						.html(this.name);
					$('#city').append($(option));
				});
			}, 'json');
		} else if(country_id != 0 && country_id != 9908) {
			$('#city_block').show();
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
					$('#city').append($(option));
				}
			}, 'json')
		} else {
			$('#city_block').hide();
			$("#city").change();
		}
	});

	$("#city").change(function(){
		if($(this).val() == -1){
			$(this).hide();
			$("#another_city")
				.show()
				.focus();
		}
	});

	$("#another_city").blur(function(){
		if($(this).val() == ""){
			$(this)
				.val("")
				.hide();
			$("#city")
				.show()
				.val(0)
		}
	});

	$.post("/geo", {
		"act" : "get_countries"
	}, function(data){
		$.each(data.countries, function(){
			var option = $("<option />");
			$(option)
				.attr("value", this.country_id)
				.html(this.name)
				console.log()
			$("#country").append($(option));
		});
		$("#country").val(<?=$post["country"]?>);
		$("#country").change();
	}, "json");
	
	$('input[name="maillist_type[]"]').change(function(){
	    
	    if(parseInt($(this).val())) {
		$('#body').elrte('destroy');
		$('td#editor_col').append('<textarea style="width: 700px; height: 150px;" id="body"></textarea>');
		
	    }
	    else 
		$('#body').elrte(opts);
	    
	});
	
});
</script>
<form id="mailer-form">
	<input type="hidden" name="act" value="send"/>
    <table>
	<tr>
	    <td>
		Тип
	    </td>
	    <td>
		<input type="radio" name="maillist_type[]" checked value="0"/><label>E-mail</label>
		<input type="radio" name="maillist_type[]" value="1"/><label>Внутреняя</label>
	    </td>
	</tr>
	<tr>
	    <td>
		<?=t("Фильтры")?>
	    </td>
	    <td>
		
		<?foreach(mailing_peer::get_filter() as $key=>$name) {
		    ?>
		    <div class="">
			<input type="checkbox" <?=(in_array($key, array_keys($filters))) ? ' checked ' : ''?> name="filter_<?=$key?>" id="<?=$key?>"/><label><?=$name?></label>
		    </div>
		
		    <div class="mt10 mb10 <?=(in_array($key, array_keys($filters))) ? '' : ' hide'?>" style="margin-left: 25px;" id="<?=$key?>_box">
		    <?
//		    var_dump(array_keys($filters[$key]['values']));
		    switch($key) {
			case 'status':
			    $statuses = profile_peer::get_types_list();
			    echo '<select name="status-id"  multiple="multiple"  size="5">';
			    foreach ($statuses as $key => $value) {
			    echo '<optgroup label="'.$value['type'].'" value="'.$key.'">';
				if(is_array($value['status']))
				    foreach ($value['status'] as $k => $v) {
					    if($filters['status']) 
						$select = (in_array($k,array_keys($filters['status']['values']))) ? ' selected ' : '';
					    echo '<option '.$select.' value="'.$k.'">'.($v ? $v : $value['type']).'</option>';
				    }
				echo '</optgroup>';

			    }
			    echo '</select>';
			    break;
			case 'agency':
			    load::model('agency');
			    $list = agency_peer::instance()->get_list();
			    foreach ($list as $aid) 
				$agency[$aid] = db::get_scalar("SELECT name FROM agency WHERE id=:aid",array('aid'=>$aid));
			    
			    echo '<select name="agency-id" multiple="multiple"  size="5>';
			    foreach ($agency as $key => $name) {
				if($filters['agency']) 
					$select = (in_array($key,array_keys($filters['agency']['values']))) ? ' selected ' : '';
				echo '<option '.$select.' value="'.$key.'">'.$name.'</option>';
			    }
			    echo '</select>';
			    break;
			case 'location':
			    echo	'<select id="country" name="location-country" style="width: 300px;" class="ui-multiselect ui-widget ui-state-default ui-corner-all">
					    <option value="0">&mdash;</option>
					 </select>
					 <div  id="region_block"  class="hide">
					 <select id="region" name="location-region" style="width: 300px;" class="ui-multiselect ui-widget ui-state-default ui-corner-all">
						<option value="0">&mdash;</option>
					 </select>
					 </div>
					 <div  id="city_block" class="hide">
					 <select id="city" name="location-city" class=" ui-multiselect ui-widget ui-state-default ui-corner-all" style="width: 300px;">
						<option value="0">&mdash;</option>
					</select>
					<input class="hide" type="text" id="another_city" value="" style="width: 300px;" />
					</div>';
			    break;
			case 'extended':
			    echo '<div style="">';
			    foreach (mailing_peer::get_extended_filter() as $k => $v) {
				if($filters['extended']) 
					$select = (in_array($k,array_keys($filters['extended']['values']))) ? ' checked ' : '';
				echo   '<div class="">
					    <input type="checkbox" '.$select.' name="extended-'.$k.'" value=""/><label>'.$v.'</label>
					</div>';	
			    }
			    echo '</div>';
			    break;
		    }
		    ?>
		    </div>
		    <div class="clear"></div>
		<? } ?>
	    </td>
	</tr>
	
	<tr>
	    <td style="width: 150px;">
		<?=t("Тема")?>
	    </td>
	    <td>
		<input style="width: 550px;" id="subject" name="subject" type="text" value="<?=$post['subject']?>"/>
	    </td>
	</tr>
	<tr>
	    <td>
		<?=t("Имя отправителя")?>
	    </td>
	    <td>
		<input style="width: 550px;" id="sender_name" name="sender_name" type="text" value="<?=$post['sender_name']?>"/>
	    </td>
	</tr>
	<tr>
	    <td>
		<?=t("E-mail отправителя")?>
	    </td>
	    <td>
		<input style="width: 550px;" id="sender_email" name="sender_email" type="text" value="<?=$post['sender_email']?>"/>
	    </td>
	</tr>
	<tr>
	    <td colspan="2" id="editor_col">
		<textarea id="body" name="body"></textarea>
	    </td>
	</tr>
	<tr>
	    <td colspan="2">
		<input type="submit" class="left" id="add-mail-list" value="<?=t('Отправить')?>"/>	    
		<input type="button" class="left ml5" id="save-template" value="<?=t('Сохранить шаблон')?>"/>	    
		<input type="button" class="left ml5" id="test-send" value="<?=t('Тестовая отправка')?>"/>	    
	    </td>
	</tr>
    </table>
</form>
<div class="clear"></div>
<div class="errors hide"></div>
<div class="success hide p20 acenter fs16">
    Зміни збережені
</div>
<script>
    $('input[id="test-send"]').click(function(){
	if($('input[name="maillist_type[]"]:checked').val()==0) {
	    Popup.show();
	    Popup.setTitle('<div class="fs14"><?=t('Тестовая отправка')?></div>');
	    Popup.setHtml('<div class="acenter fs12 tahoma"><input type="text" id="receivers"/></div><div class="acenter mt20"><input type="button" value="<?=t('Отправить')?>" onclick="test_send($(\'#receivers\').val())"/></div>');
	    Popup.position();
	}
    });
    
    var test_send = function(receivers) {
    
	var form = new Form("mailer-form");
	
	form.data['body'] = $('#body').elrte('val');
	form.data['act'] = "test_send";
	form.data['test_receivers'] = receivers;
	form.send();
	
	form.onSuccess = function(data){
	    if(data.success == true)
		Popup.close();
	}
    }
    
</script>
