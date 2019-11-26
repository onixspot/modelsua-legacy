<div class="profile mt20 mb10">
	<div>
		<div class="left" style="width: 400px;">
			<!-- START NAME AND SURNAME -->
			<div class="mb10 fs30" style="color: #000000">
				<div class="left">
					<span><?=(profile_peer::get_name($profile, '&fn ')!=' ') ? profile_peer::get_name($profile, '&fn ') : profile_peer::get_name($profile, '&fn ', 'ru')?></span> 
					<span class="ucase"><?=(profile_peer::get_name($profile, '&ln ')!=' ') ? profile_peer::get_name($profile, '&ln ') : profile_peer::get_name($profile, '&ln ', 'ru')?></span><br/>
                                        <?if(session::get('language','ru')=='en' && $profile['active'] && (!$profile['first_name_en'] || !$profile['last_name_en'])) {?>
                                            <a href="/profile/edit?id=<?=$profile['user_id']?>" class="fs12 cgray underline" onmouseover="$(this).removeClass('underline')" onmouseout="$(this).addClass('underline')">Заполните свои данные на английском языке</a>
                                        <? } ?>
				</div>
				<div class="clear"></div>
			</div>
			<? if($profile['reserv'] > 0 && session::has_credential('admin')){ ?>
				<div class="p5 mb10 fs12" style="color: #999; background: #eee; border-top: 1px solid #ccc; border-bottom: 1px solid #ccc;">
					<?=t('В резерве');?> :: <a id="profile-from_reserv" href="javascript:;">Восстановить</a>
				</div>
				<script type="text/javascript">
					$(document).ready(function(){
						$('#profile-from_reserv').click(function(){
							if(confirm('Вы действительно хотите восстановить этот профиль?')){
								$.post('/profile', {
									'act': 'from_reserv',
									'id': '<?=$user_id?>'
								}, function(response){
									if(response.success){
										window.location = '/profile?id=<?=$user_id?>'
									}
								}, 'json');
							}
						});
					});
				</script>
			<? } ?>
			<!-- END NAME AND SURNAME -->
			
			<? if($profile["del"] > 0 && session::has_credential('admin')){ ?>
				<div class="mb10 bold">
					<? $del_hist = profile_peer::instance()->get_last_del_hist($profile["user_id"]); ?>
					<? $remover = profile_peer::instance()->get_item($del_hist['operator']); ?>
					<div class="p10 cwhite" style="background: #c00;">
						<span>Удалена <?=date('Y-m-d', $del_hist['time'])?></span><br />
						<span  class="fs10">Удалил:</span> <a class="fs10 cgray" href="/profile/?id=<?=$remover["user_id"]?>">
							<?=profile_peer::get_name($remover)?>
						</a><br />
					</div>
				</div>
			<? } ?>
			
			<? $attr_key_width = 200 ?>
			<? $attr_val_width = 200 ?>
			
			<div>
			
				<div class="left">
					<!-- START STATUS -->
					<div class="fs12 mb10 bold">
						<? if($profile["show_on_main"] >= user_auth_peer::new_faces && $profile["show_on_main"] < user_auth_peer::perspective){ ?>
							<? $status = t('Новое лицо'); ?>
						<? } elseif($profile["show_on_main"] >= user_auth_peer::perspective){ ?>
							<? $status = t('Перспективная модель'); ?>
						<? } elseif($profile["show_on_main"] > user_auth_peer::successful && $profile["show_on_main"] < user_auth_peer::new_faces){ ?>
							<? $status = t('Успешная модель'); ?>
						<? } ?>
						<? if($profile["status"] == 22){ ?>
							<? $status .= ', '.t>('Член Ассоциации'); ?>
						<? } elseif($profile["status"] == 24){ ?>
							<? $status .= ', '.t('Кандидат в Члены Ассоциации'); ?>
						<? } ?>
						<? if($profile["show_on_main"] == 0 ){ ?>
							<? $status = profile_peer::get_status($profile["type"], $profile["status"]); ?>
						<? } ?>
						<div>
							<?=$status?>
						</div>
					    <div class="clear"></div>
					    
					</div>
					<!-- END STATUS -->
					
					<? if($profile['type'] != 4){ ?>
					<!-- START BIRTHDAY AND BIRTHPLACE -->
					<? if(intval(profile_peer::get_age($profile["birthday"])) > 1){ // && session::has_credential('admin') ?>
						<div class="fs12">
							<div class="left aright mr5 cgray"></div>
							<div class="left">
								<span class="bold"><?=profile_peer::get_age($profile["birthday"])?></span> 
								<span class="cgray">(<?=profile_peer::get_birthday($profile["birthday"])?>)</span>
							</div>
							<div class="clear"></div>
						</div>
					<? } ?>
					<? } ?>
					
					<? $user_params = profile_peer::instance()->get_params($profile["user_id"]); ?>
					<? if(
							$user_params["growth"] ||
							$user_params["weigth"] ||
							(
									$user_params["breast"] &&
									$user_params["waist"] &&
									$user_params["hip"]
							)
					){ ?>
						<div class="mt10">
							<!--<div class="left cgray" style="width: <?=$attr_key_width?>px;">
								<? if($user_params["growth"]){ ?>Рост<? } ?><? if($user_params["weigth"]){ ?><? if($user_params["growth"]){?> / <? } ?>Вес<? } ?><? if($user_params["breast"] && $user_params["waist"] && $user_params["hip"]){ ?><? if($user_params["growth"] || $user_params["weigth"]){?> / <? } ?>Объемы<? } ?>:
							</div>-->
							<div class="left fs11" style="width: <?=$attr_val_width?>px;">
								<? if($user_params["growth"]){ ?><?=$user_params["growth"].' '.t('см')?><? } ?><? if($user_params["weigth"]){ ?><? if($user_params["growth"]){?> / <? } ?><?=$user_params["weigth"].' '.t('кг') ?><? } ?><? if($user_params["breast"] && $user_params["waist"] && $user_params["hip"]){ ?><? if($user_params["growth"] || $user_params["weigth"]){?> / <? } ?><?=$user_params["breast"]?>-<?=$user_params["waist"]?>-<?=$user_params["hip"]?><? } ?>
							</div>
							<div class="clear"></div>
						</div>
					<? } ?>
					
					<? if($profile['type'] != 4){ ?>
						<? include 'index_parts/location.php' ?>
					<? } else { ?>
						<? $user_additional_id = user_additional_peer::instance()->get_list(array("user_id" => $profile["user_id"])); ?>
						<? $user_additional = user_additional_peer::instance()->get_item($user_additional_id[0]); ?>
						<? if($profile['manager_agency_id'] > 0){ ?>
							<? $agency = agency_peer::instance()->get_item($profile['manager_agency_id']); ?>
							<div class="fs12 mt10" style="width: 289px">
								<div>
									<!--<span class="cgray"><?=t("Место работы")?>:</span>-->
									<span><a class="bold fs15" href="/agency/?id=<?=$agency['id']?>"><?=$agency['name']?></a><? if($user_additional["current_work_place_appointment"] != ''){ ?><br /><?=$user_additional["current_work_place_appointment"]?><? } ?></span>
								</div>
							</div>
						<? } ?>
					<? } ?>
					
					<!-- START STATUS -->
				</div>
				<? if(session::has_credential('admin') || session::get_user_id() == $user_id){ ?>
					<div class="right aright fs10">
						<? if($profile['type'] == 2){ ?>
							<? if($profile['registrator']>0 && !$profile['del'] && !$profile['active'] && filter_var($profile['email'],FILTER_VALIDATE_EMAIL)) { ?>
								<div>
									<a class="cgray" href="javascript:;" onclick="$.post('/adminka/user_manager',{'act':'send_invitation', 'user_id': <?=$profile['user_id']?>}, function(resp){if(resp.success) $('#invcount').html('('+resp.inv_count+')') },'json');"><?=t("Пригласить")?></a>
									<span id="invcount" class="cgray">
										<?= (db_key::i()->exists('invitations_byadmin_'.$profile['user_id'])) ? "(".db_key::i()->get('invitations_byadmin_'.$profile['user_id']).")" : "(0)" ?>
									</span>
								</div>
							<? } ?>
							<? if(!$profile['del'] && !$profile['active'] && filter_var($profile['email'],FILTER_VALIDATE_EMAIL) && $profile['approve']==2) { ?>
								<div>
									<a class="cgray" href="javascript:;" onclick="$.post('/adminka/user_manager',{'act':'send_invitation_final', 'user_id': <?=$profile['user_id']?>}, function(resp){if(resp.success) $('#invcount').html('('+resp.inv_count+')') },'json');"><?=t("Пригласить")?></a>
									<span id="invcount" class="cgray">
										<?= (db_key::i()->exists('invitations_registred_'.$profile['user_id'])) ? "(".db_key::i()->get('invitations_registred_'.$profile['user_id']).")" : "(0)" ?>
									</span>
								</div>
							<? } ?>
							<? if(!$profile['del'] && !$profile['active'] && filter_var($profile['email'],FILTER_VALIDATE_EMAIL) && $profile['approve']==1) { ?>
								<div>
									<a class="cgray" href="javascript:;" onclick="$.post('/adminka/user_manager',{'act':'invite_in_work_model', 'user_id': <?=$profile['user_id']?>}, function(resp){if(resp.success) $('#invcount').html('('+resp.inv_count+')') },'json');"><?=t("Пригласить")?></a>
									<span id="invcount" class="cgray">
										<?= (db_key::i()->exists('invite_in_work_model_'.$profile['user_id'])) ? "(".db_key::i()->get('invite_in_work_model_'.$profile['user_id']).")" : "(0)" ?>
									</span>
								</div>
							<? } ?>
						<? } else { ?>
							<? if($profile['registrator'] > 0 && ! $profile['del'] && ! $profile['active'] && filter_var($profile['email'], FILTER_VALIDATE_EMAIL)){ ?>
								<div>
									<a class="cgray" href="javascript:;" onclick="$.post('/adminka/umanager', {'act': 'send_mail', 'alias': 'invite_nomodels', 'uid': <?=$profile['user_id']?>}, function(response){ if(response.success){ $('#invcount').html('('+response.invcount+')') } }, 'json');">Пригласить</a>
									<span id="invcount" class="cgray">
										(<?= (db_key::i()->exists('invite_nomodels_'.$profile['user_id'])) ? db_key::i()->get('invite_nomodels_'.$profile['user_id']) : 0 ?>)
									</span>
								</div>
							<? } ?>
						<? } ?>
						<div>
							<a class="cgray fs12" href="/profile/edit?id=<?=$profile["user_id"]?>"><?=t("Редактировать")?></a><br/>
							<?if((session::has_credential('amu') || session::has_credential('superadmin')) || $profile['can_write']) {?>
							    <a class="cgray fs12" href="/messages?receiver=<?=$profile['user_id']?>"><?=t("Написать")?></a>
							<? } ?>
						</div>
						<? if(session::has_credential('admin')){ ?>
							<div>
								<a class="cgray hide" href="/polls/history?user_id=<?=$user_id?>">*<?=t("История голосования")?></a>
							</div>
						<? } ?>
						<? if(session::has_credential('admin')){ ?>
							   <?if($profile['del']) {?>
								<a class="cgray" id="adminka-remove-remove-item-<?=$profile['user_id']?>" href="javascript:;">*<?=t("Удалить безвозвратно")?></a><br/>
								<a class="cgray" id="adminka-remove-restore-item-<?=$profile['user_id']?>" href="javascript:;">*<?=t("Восстановить")?></a>
							    <? } else {?>
								<a class="cgray" id="adminka-remove-archive-item-<?=$profile['user_id']?>" href="javascript:;">*<?=t("В архив")?></a><br/>
								<a class="cgray" id="adminka-reserv" href="javascript:;">*<?=t("В резерв")?></a>
                                                                <span>  <? if($profile['active']){ ?><br/><b>Активная</b><? } ?>
                                                                        <? if($profile['activated_ts'] > 0){ ?><?=date("d.m.Y", $profile['activated_ts'])?><? } ?>
                                                                </span><br/>
							    <? } ?>
								
								<script>
									$('#adminka-reserv').click(function(){
										if(confirm('Вы действительно хотите отправить профиль в резерв?')){
											$.post('/profile', {
												'id': <?=$user_id?>,
												'act': 'to_reserv'
											}, function(response){
												if(response.success)
													window.location = '/profile?id=<?=$user_id?>'
											}, 'json');
										}
									});
									
								    $("a[id*='adminka-remove-']").click(function(){
									    var id = $(this).attr("id").split("-")[4];
									    var act = $(this).attr("id").split("-")[2];
									    if(confirm("<?=t('Вы уверены')?>?")){
										    $.post("/adminka/user_manager?act=remove", {
											    "act": act,
											    "user_id": id
										    }, function(data){
											    if(data.redirect) window.location = data.redirect;
											    else
												if(data.success) window.location=window.location;
												else console.log(data);
										    }, "json");
									    }
								    });
								</script>
						<? } ?>
					</div>
				<? } ?>
				<div class="clear"></div>
			</div>
			
			<? if($profile['type'] != 4){ ?>
				<!-- START RATING -->
				<? include "index_parts/rating.php" ?>
				<!-- END RATING -->
			<? } ?>
			
			<?// if($profile['type'] != 4){ ?>
				<!-- START SHORT INFORMATION -->
				<? include "index_parts/info.php" ?>
				<!-- END SHORT INFORMATION -->
			<?// } ?>
			
			<!-- START BLOG -->
			<!--<div class="mt20 blog fs12 cblack">
				<div class="title">
					<div class="left image">
						<img src="/square_b.png" />
					</div>
					<div class="left ml5 bold ucase">
						Блог
					</div>
					<div class="clear"></div>
				</div>
				<//!--START BLOG ITEMS --//>
				<div class="item mt5">
					<div class="fs20">
						<a class="title" href="/profile">Lorem ipsum dolor sit</a>
					</div>
					<div class="italic fs11 lcase" style="color: #aeb7c9">19 января</div>
					<div>Consectetur adipiscing elit. Donec tincidunt risus aliquet nibh dictum mol...</div>
				</div>
				<div class="mt5">
					<div class="fs20">
						<a href="/profile" style="color: #5c7fc7">Lorem ipsum dolor sit</a>
					</div>
					<div class="italic fs11 lcase" style="color: #aeb7c9">19 января</div>
					<div>Consectetur adipiscing elit. Donec tincidunt risus aliquet nibh dictum mol...</div>
				</div>
				<//!-- END BLOG ITEMS --//>
				<div class="mt10 all">
					<a href="/profile">Все 40 записей</a>
				</div>
			</div>-->
			<!-- END BLOG -->
			
			<!-- START MESSAGES -->
			
			<?if($profile['can_write'] && $profile['user_id']==session::get_user_id()) {?>
			    <? include 'index_parts/messages.php'; ?>
			<? } ?>
			<!-- END MESSAGES -->
			
			<!-- START CONTACTS -->
			<? include 'index_parts/contacts.php'; ?>
			<!-- END CONTACTS -->
			
			<!-- START CARD -->
			<?if(session::has_credential('admin')) { //  || session::get_user_id()==$profile['user_id'] ?>
				<? if(profile_peer::get_type_by_user($profile['user_id']) == 2){ ?>
					<?// include 'index_parts/card.php'; ?>
				<? } ?>
			<? } ?>
			<!-- END CARD -->
                        
			<!-- ADMIN BLOCK -->
			<? include 'index_parts/admin_block.php'; ?>
			<!-- END CONTACTS -->
			
			<? if(profile_peer::get_type_by_user($profile['user_id']) == 2){ ?>
				<!-- START HRONOLOGY -->
				<? include 'index_parts/hronology.php'; ?>
				<!-- END HRONOLOGY -->
			<? } ?>
			
			<? if(profile_peer::get_type_by_user($profile['user_id']) == 2){ ?>
				<!-- START FOREIGN WORKS -->
				<? include 'index_parts/foreign_works.php'; ?>
				<!-- END FOREIGN WORKS -->
			<? } ?>

            <? if(profile_peer::get_type_by_user($profile['user_id']) == 2){ ?>
                <!-- START SMI -->
                <? include "index_parts/smi.php" ?>
                <!-- END SMI -->
            <? } ?>
		</div>

		<div class="left ml20 fs11" style="width: 580px;">
			<!-- START PHOTO -->
			<? include "index_parts/photo.php"; ?>
			<!-- END PHOTO -->
			
			<? if(profile_peer::get_type_by_user($profile['user_id']) == 2){ ?>
				<div class="mt10">
					<div class="aleft square_p pl15 mb10">
						<div class="left ucase bold">
							<a class="cblack" href="/albums/album?aid=<?=$albums['portfolio'][0]['id']?>&uid=<?=$user_id?>"><?=t("Портфолио")?></a>
						</div>
						<div class="right">
							<a class="underline cgray" onmouseover="$(this).css('color', 'black')" onmouseout="$(this).css('color', 'gray')" href="/albums/album?aid=<?=$albums['portfolio'][0]['id']?>&uid=<?=$user_id?>"><?=t("Смотреть все")?></a>
						</div>
						<? if(session::get_user_id() == $user_id || session::has_credential('admin')){ ?>
							<div class="right mr10">
								<a class="underline cgray" onmouseover="$(this).css('color', 'black')" onmouseout="$(this).css('color', 'gray')" href="/albums/album?aid=<?=$albums['portfolio'][0]['id']?>&uid=<?=$user_id?>&show=add_photo"><?=t("Добавить фото")?></a>
							</div>
						<? } ?>
						<div class="clear"></div>
					</div>
					<div>
						<div id="profile-portfolio-<?=$profile["pid"]?>" class="left p5" style="width: 62px">
							<div style="height: 100px; background: url('/imgserve?pid=<?=$profile["pid"]?>&h=100') no-repeat center"></div>
						</div>
						<? if(count($albums['portfolio'][0]['images']) > 0){ ?>
							<? $counter = 0; ?>
							<? foreach($albums['portfolio'][0]['images'] as $image){ ?>
								<? if($counter >= 7){ ?>
									<? break; ?>
								<? } ?>
								<div id="profile-portfolio-<?=$image?>" class="left p5" style="width: 62px">
									<div style="height: 100px; background: url('/imgserve?pid=<?=$image?>&h=100') no-repeat center"></div>
								</div>
								<? $counter++; ?>
							<? } ?>
						<? } ?>
						<div class="clear"></div>
					</div>
				</div>
				<script type="text/javascript">
					$(document).ready(function(){
						$("div[id^='profile-portfolio']").click(function(){
							var id = $(this).attr('id').split('-')[2];
							$("div[id^='profile-portfolio']").css('background', 'none');
							$(this).css('background', '#eee');
							$('#profile-photo')
								.css({
									'opacity': '.25',
									'background': "url('/imgserve?pid="+id+"&h=600') no-repeat center"
								})
								.animate({
									'opacity': '1'
								}, 1024);
						});

						$("div[id^='profile-portfolio'] > div").mouseover(function(){
							$(this).parent()
								.css('opacity', '.25')
								.animate({
									'opacity': '1'
								}, 512);
						});

						$('#profile-portfolio-<?=$profile["pid"]?>').click();
					});
				</script>
			<? } ?>
			
			<? if(profile_peer::get_type_by_user($profile['user_id']) == 2){ ?>
				<!-- START WORKS -->
				<div class="mt20">
					<div class="square_p pl15 mb10 fs11">
						<div class="left ucase bold">
							<a class="cblack" href="/albums/works?uid=<?=$user_id?>"><?=t("Работы ")?></a>
						</div>
						<div class="right">
							<a class="underline cgray" onmouseover="$(this).css('color', 'black')" onmouseout="$(this).css('color', 'gray')" href="/albums/works?uid=<?=$user_id?>"><?=t("Смотреть все")?></a>
						</div>
						<? if(session::get_user_id() == $user_id || session::has_credential('admin')){ ?>
							<div class="right mr10">
								<div>
									<a
										class="underline cgray"
										href="javascript:;"
										onmouseover="$(this).css('color', 'black')" onmouseout="$(this).css('color', 'gray')"
										onclick="
											if($('#window-categories').is(':visible'))
												$('#window-categories').animate({
													'opacity': '0'
												}, 256, function(){
													$(this).hide();
												});
											else
												$('#window-categories')
													.show()
													.css('opacity', '0')
													.animate({
														'opacity': '1'
													}, 256);
										"><?=t("Добавить работу")?></a>
								</div>
								<div id="window-categories" class="pb10 pl5 pr5 mt5 hide" style="position: absolute; border: 1px solid gray; background: #fff; box-shadow: 0px 0px 5px #aaa">
									<? foreach($works as $category_key => $work){ ?>
										<? if(in_array($category_key, array('portfolio'))){ ?>
											<? continue; ?>
										<? } ?>
										<div class="pt5">
											<? if(in_array($category_key, array('covers'))){ ?>
												<a class="underline cgray" onmouseover="$(this).css('color', 'black')" onmouseout="$(this).css('color', 'gray')" href='/albums/album?aid=<?=$albums[$category_key][0]['id']?>&uid=<?=$user_id?>&show=add_photo'><?=$work?></a>
											<? } else { ?>
												<a class="underline cgray" onmouseover="$(this).css('color', 'black')" onmouseout="$(this).css('color', 'gray')" href='/albums?filter=<?=$category_key?>&uid=<?=$user_id?>&show=add_album'><?=$work?></a>
											<? } ?>
										</div>
									<? } ?>
								</div>
							</div>
						<? } ?>
						<div class="clear"></div>
					</div>
					<?// if((session::has_credential('admin') || session::get_user_id() == $user_id) && count($works) > 0){ ?>
					<div class="fs12">
						<? $flag = false; ?>
						<? foreach($works as $category_key => $work){ ?>
							<? if(in_array($category_key, array('portfolio', 'contest'))){ ?>
								<? continue; ?>
							<? } ?>
							<? if(count($albums[$category_key][0]['images'][0]) > 0){ ?>
								<? $flag = true; ?>
								<div class="left mr10" style="width: 100px;">
									<div class="acenter" style="height: 32px;">
										<? if(in_array($category_key, array('covers'))){ ?>
											<?// $albums_id = user_albums_peer::instance()->get_list(array('user_id' => $user_id, 'category' => $category_key)); ?>
											<a class="underline" href="/albums/album?aid=<?=$albums[$category_key][0]['id']?>&uid=<?=$user_id?>"><?=$work?>&nbsp;(<?=count($albums[$category_key][0]['images'])?>)</a>
										<? } else { ?>
											<a class="underline" href="/albums?filter=<?=$category_key?>&uid=<?=$user_id?>"><?=$work?>&nbsp;(<?=count($albums[$category_key])?>)</a>
										<? } ?>
									</div>
									<div
										<? if(in_array($category_key, array('covers'))){ ?>
											onclick="window.location = '/albums/album?aid=<?=$albums[$category_key][0]['id']?>&uid=<?=$user_id?>';"
										<? } else { ?>
											onclick="window.location = '/albums?filter=<?=$category_key?>&uid=<?=$user_id?>';"
										<? } ?>
										style="cursor: pointer; height: 80px; background: url('/imgserve?pid=<?=$albums[$category_key][0]['images'][0]?>&h=80') no-repeat center"
									></div>
									<div class="acenter">
										<? $name = $albums[$category_key][0]['name'] ?>
										<? if(in_array($category_key, array('covers'))){ ?>
											<? $photo = user_photos_peer::instance()->get_item($albums[$category_key][0]['images'][0]); ?>
											<? $photo['additional'] = unserialize($photo['additional']); ?>
											<? $name = $photo['additional']['journal_name']." :: ".$photo['additional']['journal_number'].', '.$photo['additional']['journal_year']; ?>
										<? } ?>
										<!--<a class="underline" href='/albums/album?aid=<?=$albums[$category_key][0]['id']?>&uid=<?=$user_id?>'><?=$name?></a>-->
									</div>
								</div>
							<? } ?>
						<? } ?>
						<? if( ! $flag){ ?>
							<div class="left acenter cgray" style="width: 580px; height: 57px; background: #eee; padding-top: 45px;">
								<?=t("Тут еще нет работ")?>
							</div>
						<? } ?>
						<div class="clear"></div>
					</div>
					<?// } ?>
				</div>
				<!-- END WORKS -->
			<? } ?>
			
			<? if(profile_peer::get_type_by_user($profile['user_id']) == 2){ ?>
				<!-- START CONTEST -->
				<? if(session::get_user_id() == $user_id || count($albums['contest']) > 0){ ?>
					<div class="mt20">
						<div class="square_p pl15 mb10">
							<div class="left bold ucase">
								<a class="cblack" href="/albums?uid=<?=$user_id?>&filter=contest"><?=t("Конкурсы")?></a>
							</div>
							<div class="right">
								<a class="underline cgray" onmouseover="$(this).css('color', 'black')" onmouseout="$(this).css('color', 'gray')" href="/albums?uid=<?=$user_id?>&filter=contest"><?=t("Смотреть все")?></a>
							</div>
							<? if(session::get_user_id() == $user_id || session::has_credential('admin')){ ?>
								<div class="right mr10">
									<a class="underline cgray" onmouseover="$(this).css('color', 'black')" onmouseout="$(this).css('color', 'gray')" href="/albums?uid=<?=$user_id?>&filter=contest&show=add_album"><?=t("Добавить альбом")?></a>
								</div>
							<? } ?>
							<div class="clear"></div>
						</div>
						<? if(count($albums['contest']) > 0){ ?>
							<div>
								<? foreach($albums['contest'] as $album){ ?>
									<div class="left mr10" style="width: 100px;">
										<div
											onclick="window.location = '/albums/album?aid=<?=$album['id']?>&uid=<?=$user_id?>';"
											style="cursor: pointer; height: 80px; background: url('/imgserve?pid=<?=$album['images'][0]?>&h=80') no-repeat center"
										></div>
										<!--<div class="acenter">
											<a class="underline" href='/albums/album?aid=<?=$album['id']?>&uid=<?=$user_id?>'><?=$album['name']?></a>
										</div>-->
									</div>
								<? } ?>
								<div class="clear"></div>
							</div>
						<? } else { ?>
							<div class="acenter cgray" style="width: 580px; height: 57px; background: #eee; padding-top: 45px;">
								<?=t("Тут еще нет фотографий")?>
							</div>
						<? } ?>
					</div>
				<? } ?>
				<!-- END CONTEST -->
			<? } ?>
			
			<? if(profile_peer::get_type_by_user($profile['user_id']) == 2){ ?>
				<!-- START PHOTO ALBUMS -->
				<? if(session::get_user_id() == $user_id || count($albums['photos']) > 0){ ?>
					<div class="mt20">
						<div class="square_p pl15 mb10">
							<div class="left bold ucase">
								<a class="cblack" href="/albums?uid=<?=$user_id?>"><?=t("Фотографии")?></a>
							</div>
							<div class="right">
								<a class="underline cgray" onmouseover="$(this).css('color', 'black')" onmouseout="$(this).css('color', 'gray')" href="/albums?uid=<?=$user_id?>"><?=t("Смотреть все")?></a>
							</div>
							<? if(session::get_user_id() == $user_id || session::has_credential('admin')){ ?>
								<div class="right mr10">
									<a class="underline cgray" onmouseover="$(this).css('color', 'black')" onmouseout="$(this).css('color', 'gray')" href="/albums?uid=<?=$user_id?>&show=add_album"><?=t("Добавить альбом")?></a>
								</div>
							<? } ?>
							<div class="clear"></div>
						</div>
						<? if(count($albums['photos']) > 0){ ?>
							<div>
								<? foreach($albums['photos'] as $album){ ?>
									<div class="left mr10" style="width: 100px;">
										<div
											onclick="window.location = '/albums/album?aid=<?=$album['id']?>&uid=<?=$user_id?>';"
											style="cursor: pointer; height: 80px; background: url('/imgserve?pid=<?=$album['images'][0]?>&h=80') no-repeat center"
										></div>
										<!--<div class="acenter">
											<a class="underline" href='/albums/album?aid=<?=$album['id']?>&uid=<?=$user_id?>'><?=$album['name']?></a>
										</div>-->
									</div>
								<? } ?>
								<div class="clear"></div>
							</div>
						<? } else { ?>
							<div class="acenter cgray" style="width: 580px; height: 57px; background: #eee; padding-top: 45px;">
								<?=t("Тут еще нет фотографий")?>
							</div>
						<? } ?>
					</div>
				<? } ?>
				<!-- END PHOTO ALBUMS -->
			<? } ?>
			
			<? if(session::has_credential('admin')){ ?>
				<div class="mt20">
					<div class="square_p pl15 mb10">
						<div class="left bold ucase">
							<a class="cblack" href="/albums/album?uid=<?=$user_id?>&filter=deleted"><?=t("Админский альбом")?></a>
						</div>
						<div class="right">
							<a class="underline cgray" onmouseover="$(this).css('color', 'black')" onmouseout="$(this).css('color', 'gray')" href="/albums/album?uid=<?=$user_id?>&filter=deleted"><?=t("Смотреть все")?></a>
						</div>
						<div class="right mr10">
							<a class="underline cgray" onmouseover="$(this).css('color', 'black')" onmouseout="$(this).css('color', 'gray')" href="/albums/album?uid=<?=$user_id?>&filter=deleted&show=add_photo"><?=t("Добавить фото")?></a>
						</div>
						<div class="clear"></div>
					</div>
					<? if(count($albums['admin']) > 0){ ?>
						<div>
							<? foreach($albums['admin'] as $pid){ ?>
								<div class="left mr10" style="width: 100px;">
									<div
										onclick="window.location = '/albums/album?uid=<?=$user_id?>&filter=deleted';"
										style="cursor: pointer; height: 80px; background: url('/imgserve?pid=<?=$pid?>&h=80') no-repeat center"
									></div>
								</div>
							<? } ?>
							<div class="clear"></div>
						</div>
					<? } else { ?>
						<div class="acenter cgray" style="width: 580px; height: 57px; background: #eee; padding-top: 45px;">
							<?=t("Тут еще нет фотографий")?>
						</div>
					<? } ?>
				</div>
			<? } ?>
			<!-- START VIDEOS -->
			<!--<div class="videos">
				<div class="head">
					<div class="img"></div>
					<div class="txt">Видео</div>
					<div class="clear"></div>
				</div>
				<div class="items">
					<div class="item">
						<div class="img">
							Image
						</div>
						<div class="title">
							<a class="" href="/">Lorem ipsum dolor sit amet</a>
						</div>
						<div class="desc">
							Consectetur adipiscing elit. Donec tincidunt risus aliquet nibh dictum mol...
						</div>
					</div>
					<div class="item">
						<div class="img">
							Image
						</div>
						<div class="title">
							<a class="" href="/">Lorem ipsum dolor sit amet</a>
						</div>
						<div class="desc">
							Consectetur adipiscing elit. Donec tincidunt risus aliquet nibh dictum mol...
						</div>
					</div>
					<div class="item">
						<div class="img">
							Image
						</div>
						<div class="title">
							<a class="" href="/">Lorem ipsum dolor sit amet</a>
						</div>
						<div class="desc">
							Consectetur adipiscing elit. Donec tincidunt risus aliquet nibh dictum mol...
						</div>
					</div>
					<div class="clear"></div>
				</div>
				<div style="width: 1px; height: 20px;"></div>
			</div>-->
			<!-- END VIDEOS -->
		</div>
		<div class="clear"></div>
		
	</div>
</div>
<?if($by_code) {
    include 'index_parts/login_by_code_form.php';
} ?>


