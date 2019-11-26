<div class="fs12 mb10 mt10">
	
	<div class="p5 bold fs12 cwhite" style="background: #000000;">
		<?=t('Редактирование')?> :: <a class="cwhite underline" href="/profile?id=<?= $profile['user_id']?>"><?=profile_peer::get_name($profile, '&fn &ln')?></a>
	</div>
	
	<div class="p10" style="border: 1px solid #000000;">
		
		<div id="profile-edit-tabbar" class="mb10 pt5 pl5 pr5" style="border-radius: 5px; background: #000000;">
			<div id="profile-edit-tab-general" class="left p5">
				<?=t('Общая информация')?>
			</div>
			<!--<div id="profile-edit-tab-locality" class="left p5">
				Текущее место проживания
			</div>-->
			<? if(profile_peer::get_type_by_user($profile['user_id']) == 2 || session::get_user_id()==1){ ?>
				<div id="profile-edit-tab-params" class="left p5">
					<?=t('Параметры')?>
				</div>
			<? } ?>
			<? if(profile_peer::get_type_by_user($profile['user_id']) == 2 || session::get_user_id()==1){ ?>
				<div id="profile-edit-tab-agency" class="left p5">
					<?=t('Агентство')?>
				</div>
			<? } ?>
			<div id="profile-edit-tab-additional" class="left p5">
				<?=t('Дополнительная информация')?>
			</div>
			<div id="profile-edit-tab-contacts" class="left p5">
				<?=t('Контакты')?>
			</div>
			<div id="profile-edit-tab-photo" class="left p5">
				<?=t('Фотография')?>
			</div>
			<? if(profile_peer::get_type_by_user($profile['user_id']) == 2 || session::get_user_id()==1){ ?>
				<div id="profile-edit-tab-card" class="left p5">
					<?=t('Визитка')?>
				</div>
			<? } ?>
			<div id="profile-edit-tab-options" class="left p5">
				<?=t('Настройки')?>
			</div>
			<div class="clear"></div>
		</div>
		
		<div id="profile-edit-frames">
			<? include 'edit_parts/general.php' ?>
			<?// include "edit_parts/locality.php" ?>
			<? if(profile_peer::get_type_by_user($profile['user_id']) == 2 || session::get_user_id()==1){ ?>
				<? include 'edit_parts/params.php' ?>
			<? } ?>
			<? if(profile_peer::get_type_by_user($profile['user_id']) == 2 || session::get_user_id()==1){ ?>
				<? include 'edit_parts/agency.php' ?>
			<? } ?>
			<? include 'edit_parts/additional.php' ?>
			<? include 'edit_parts/contacts.php' ?>
			<? include 'edit_parts/photo.php' ?>
			<? if(profile_peer::get_type_by_user($profile['user_id']) == 2 || session::get_user_id()==1){ ?>
				<? include 'edit_parts/card.php' ?>
			<? } ?>
			<? include 'edit_parts/options.php' ?>
		</div>
		
	</div>
	
</div>

<script type="text/javascript">
        

        
	$(document).ready(function(){
            
                var card_avatar = new Image();
                var _card_selection;
                card_avatar.src = $('#card_avatar').attr('src');
                var card_percent = $('#card_avatar').width() * 100 / card_avatar.width;
                
                $('#profile-card-save').click(function(){
                    
                        if(Math.round(_card_selection.width*100/card_percent))
                            $.post('/profile/card', {
                                    'crop': 1,
                                    'pid': $('#pid').val(),
                                    'x': Math.round(_card_selection.x1*100/card_percent),
                                    'y': Math.round(_card_selection.y1*100/card_percent),
                                    'w': Math.round(_card_selection.width*100/card_percent),
                                    'h': Math.round(_card_selection.height*100/card_percent)
                            }, function(resp){
                                    if(resp.success)
                                    {
                                            $("#msg-success-photo")
                                                    .show()
                                                    .css("opacity", "0")
                                                    .animate({
                                                            "opacity": "1"
                                                    }, 256, function(){
                                                            setTimeout(function(){
                                                                    $("#msg-success-photo").animate({
                                                                            "opacity": "0"
                                                                    }, 256, function(){
                                                                            $(this).hide();

                                                                            $('#pid').val(resp.pid);
                                                                            $('#photo_span > img').attr('src', '/imgserve?pid='+resp.pid+'&x='+resp.crop['x']+'&y='+resp.crop['y']+'&w='+resp.crop['w']+'&h='+resp.crop['h']+"&z=crop");
                                                                    })
                                                            }, 1);
                                                    });
                                    }
                            }, 'json');
                            else {
                                console.log('error...');
                            }
                });
            
                var photo_avatar = new Image();
                var _selection;
                photo_avatar.src = $('#photo_avatar').attr('src');

                $('#profile-photo-save').click(function(){
                        var percent = $('#photo_avatar').width() * 100 / photo_avatar.width;

                        $.post('/profile/edit?group=ph_crop', {
                                'uid': '<?=$user_id?>',
                                'x': Math.round(_selection.x1*100/percent),
                                'y': Math.round(_selection.y1*100/percent),
                                'w': Math.round(_selection.width*100/percent),
                                'h': Math.round(_selection.height*100/percent)
                        }, function(resp){
                                if(resp.success)
                                {
                                        $("#msg-success-photo")
                                                .show()
                                                .css("opacity", "0")
                                                .animate({
                                                        "opacity": "1"
                                                }, 256, function(){
                                                    //window.location = '/profile?id=<?=$user_id?>';
                                                });
                                }
                        }, 'json');
                });
                var card_avatar = function(img, selection)
                {
                        var scaleX = 100 / (selection.width);
                        var scaleY = 138 / (selection.height);

                        $('#card_avatar_small > img').css({
                                        width: Math.round(scaleX * $('#card_avatar').width()) + 'px',
                                        height: Math.round(scaleY * $('#card_avatar').height()) + 'px',
                                        marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px',
                                        marginTop: '-' + Math.round(scaleY * selection.y1) + 'px'
                        });

                        _card_selection = selection;
                        $('#profile-card-save').click();
                }
                var card_opt = {
                        aspectRatio: '1:1.38', 
                        onInit: <?=($preview['crop']) ? 'card_avatar' : 'function() {}'?>,
                        onSelectEnd: card_avatar,
                        x1: 0,
                        y1: 0,
                        x2: 100,
                        y2: 138
                }
            
		var preview = function(img, selection)
                {
                        var scaleX = 100 / (selection.width || 1);
                        var scaleY = 100 / (selection.height || 1);

                        $('#photo_avatar_preview > img').css({
                                        width: Math.round(scaleX * $('#photo_avatar').width()) + 'px',
                                        height: Math.round(scaleY * $('#photo_avatar').height()) + 'px',
                                        marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px',
                                        marginTop: '-' + Math.round(scaleY * selection.y1) + 'px'
                        });

                        _selection = selection;
                }
                
                var photo_opt = {
                        aspectRatio: '1:1', 
                        onInit: preview,
                        onSelectEnd: preview,
                        x1: 0,
                        y1: 0,
                        x2: 100,
                        y2: 100
                };
                
		var initMenu = function(){
			refreshMenu();
			$.each($("#profile-edit-tabbar div[id^='profile-edit-tab']"), function(){
				$(this)
					.click(function(){
						refreshMenu();
						var frame = $(this).attr("id").split("-")[3];
						$("#profile-edit-frame-"+frame).show();
                                                
                                                var imgAreas = new Array('photo','card');
                                                var pos = $.inArray(frame, imgAreas);
                                                 
                                                for(var key in imgAreas) 
                                                    if(pos!==-1) 
                                                        if(key==pos) $("#"+imgAreas[key]+"_avatar").imgAreaSelect(eval("("+imgAreas[key]+'_opt'+")"));
                                                        else $("#"+imgAreas[key]+"_avatar").imgAreaSelect({remove: 1});
                                                    else 
                                                        $("#"+imgAreas[key]+"_avatar").imgAreaSelect({remove: 1});
                                                    
						$(this).css({
							"background": "#fff",
							"color": "#444"
						});
					});
			});
		}
		
		var refreshMenu = function()
		{
			$.each($("#profile-edit-tabbar div[id^='profile-edit-tab']"), function(){
				$("#profile-edit-frame-"+$(this).attr("id").split("-")[3]).hide();
				$(this)
					.css({
						"border-radius": "5px 5px 0px 0px",
						"margin-right": "1px",
						"cursor": "pointer",
						"border": "1px solid #ccc",
						"border-bottom": "none",
						"background": "#ddd",
						"color": "#999"
					})
			});
		}
		
		initMenu();
		<? if(in_array(request::get('frame'), array('options', 'photo', 'additional', 'contacts', 'card', 'agency'))){ ?>
			$("#profile-edit-tab-<?=request::get('frame')?>").click();
		<? } else { ?>
			$("#profile-edit-tab-general").click();
		<? } ?>
	});
</script>
