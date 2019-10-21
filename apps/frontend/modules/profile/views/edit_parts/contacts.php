<? $profile_contacts = profile_peer::instance()->get_contacts($user_id); ?>

<div id="profile-edit-frame-contacts">
	
	<div class="cgray mt20" style="margin-left: 73px; font-weight: bold;"><?=t('Ваши контакты будут видны только администрации сайта')?></div>
	
	<form id="profile-edit-form-contacts" action="/profile/edit?id=<?=$profile["user_id"]?>&group=contacts">
		<div class="mt20 mb10">
			<div class="left pt5 mr5 aright" style="width: 200px">Email: </div>
			<div class="left">
				<input type="text" id="email" value="<?=$profile_contacts["email"]?>" />
			</div>
			<div class="left mt5 ml5">
				<input type="checkbox" id="email-access" <? if($profile_contacts['_email']['access'] == 1){ ?>checked<? } ?> />
				<label for="email-access"><?=t('Просмотр доступен для всех')?></label>
			</div>
			<div class="clear"></div>
			<div id="msg-error-email" class="ml5 mt5 hide" style="color: red; padding-left: 205px;"><?=t('Ошибка')?>. <?=t('Такой email уже сущуствует')?></div>
		</div>
		<div class="mb10">
			<div class="left pt5 mr5 aright" style="width: 200px"><?=t("Телефон")?>: </div>
			<div class="left">
				<input type="text" id="phone" value="<?=$profile_contacts["phone"]?>" />
			</div>
			<div class="left mt5 ml5">
				<input type="checkbox" id="phone-access" <? if($profile_contacts['_phone']['access'] == 1){ ?>checked<? } ?> />
				<label for="phone-access"><?=t('Просмотр доступен для всех')?></label>
			</div>
			<div class="clear"></div>
		</div>
		<div class="mb10">
			<div class="left pt5 mr5 aright" style="width: 200px"><?=t("Веб сайт")?>: </div>
			<div class="left">
				<input type="text" id="website" value="<?=$profile_contacts["website"]?>" />
			</div>
			<div class="left mt5 ml5">
				<input type="checkbox" id="website-access" <? if($profile_contacts['_website']['access'] == 1){ ?>checked<? } ?> />
				<label for="website-access"><?=t('Просмотр доступен для всех')?></label>
			</div>
			<div class="clear"></div>
		</div>
		<div class="mb10">
			<div class="left pt5 mr5 aright" style="width: 200px">Skype: </div>
			<div class="left">
				<input type="text" id="skype" value="<?=$profile_contacts["skype"]?>" />
			</div>
			<div class="left mt5 ml5">
				<input type="checkbox" id="skype-access" <? if($profile_contacts['_skype']['access'] == 1){ ?>checked<? } ?> />
				<label for="skype-access"><?=t('Просмотр доступен для всех')?></label>
			</div>
			<div class="clear"></div>
		</div>
		<div class="mb10">
			<div class="left pt5 mr5 aright" style="width: 200px">ICQ: </div>
			<div class="left">
				<input type="text" id="icq" value="<?=$profile_contacts["icq"]?>" />
			</div>
			<div class="left mt5 ml5">
				<input type="checkbox" id="icq-access" <? if($profile_contacts['_icq']['access'] == 1){ ?>checked<? } ?> />
				<label for="icq-access"><?=t('Просмотр доступен для всех')?></label>
			</div>
			<div class="clear"></div>
		</div>
		<div class="mb10">
			<div class="left pt5 mr5 aright" style="width: 200px">Facebook.com: </div>
			<div class="left">
				<input type="text" id="facebook" value="<?=$profile_contacts["facebook"]?>" />
			</div>
			<div class="left mt5 ml5">
				<input type="checkbox" id="facebook-access" <? if($profile_contacts['_facebook']['access'] == 1){ ?>checked<? } ?> />
				<label for="facebook-access"><?=t('Просмотр доступен для всех')?></label>
			</div>
			<div class="clear"></div>
		</div>
		<div class="mb10">
			<div class="left pt5 mr5 aright" style="width: 200px">Napodiume.ru: </div>
			<div class="left">
				<input type="text" id="napodiume" value="<?=$profile_contacts["napodiume"]?>" />
			</div>
			<div class="left mt5 ml5">
				<input type="checkbox" id="napodiume-access" <? if($profile_contacts['_napodiume']['access'] == 1){ ?>checked<? } ?> />
				<label for="napodiume-access"><?=t('Просмотр доступен для всех')?></label>
			</div>
			<div class="clear"></div>
		</div>
		<div class="mb10">
			<div class="left pt5 mr5 aright" style="width: 200px">Vkontakte.ru: </div>
			<div class="left">
				<input type="text" id="vkontakte" value="<?=$profile_contacts["vkontakte"]?>" />
			</div>
			<div class="left mt5 ml5">
				<input type="checkbox" id="vkontakte-access" <? if($profile_contacts['_vkontakte']['access'] == 1){ ?>checked<? } ?> />
				<label for="vkontakte-access"><?=t('Просмотр доступен для всех')?></label>
			</div>
			<div class="clear"></div>
		</div>
		
		<? if(session::has_credential('admin')){ ?>
			<? $hidden_data = unserialize($profile['hidden_data']) ?>
			<div class="mb10 mt30">
				<div class="left pt5 mr5 aright cgray" style="width: 200px">* Email: </div>
				<div class="left">
					<input type="text" id="hd_email" value="<?=$hidden_data['email']?>" />
				</div>
				<div class="clear"></div>
			</div>

			<div class="mb10">
				<div class="left pt5 mr5 aright cgray" style="width: 200px">* Моб. телефон: </div>
				<div class="left">
					<input type="text" id="hd_phone" value="<?=$hidden_data['phone']?>" />
				</div>
				<div class="clear"></div>
			</div>

			<div class="mb10">
				<div class="left pt5 mr5 aright cgray" style="width: 200px">* Альтернативный телефон: </div>
				<div class="left">
					<input type="text" id="hd_alt_tel" value="<?=$hidden_data['alt_tel']?>" />
				</div>
				<div class="clear"></div>
			</div>
		<? } ?>
		
		<div class="mt30">
			<div class="left pt5 mr5 aright" style="width: 200px">&nbsp;</div>
			<div class="left">
				<input type="button" id="submit" value="<?=t("Сохранить")?>" />
			</div>
			<div id="msg-success-contacts" class="left pt5 ml10 acenter hide" style="color: #090">
				Данные сохранены успешно
			</div>
			<div class="clear"></div>
		</div>
		
		
		
	</form>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		var form = new Form("profile-edit-form-contacts");
		form.onSuccess = function(data){
			$("div[id^='msg-error']").hide();
			if(data.success)
				$("#msg-success-contacts")
					.show()
					.css("opacity", "0")
					.animate({
						"opacity": "1"
					}, 256, function(){
						setTimeout(function(){
							$("#msg-success-contacts").animate({
								"opacity": "0"
							}, 256, function(){
								$(this).hide();
							})
						}, 1000);
					});
			else
			{
				$('#msg-error-'+data.error).show();
			}
		}
		$("#profile-edit-form-contacts #submit").click(function(){
			form.send();
		});
	})
</script>