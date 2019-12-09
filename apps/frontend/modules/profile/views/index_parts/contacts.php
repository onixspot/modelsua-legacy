<? // if(
//		session::get_user_id() == $user_id || 
//		session::has_credential("admin")
//	){ ?>
	<div class="mt30 mb30 fs12">
		<div class="square_p pl15 mb10 fs12 ucase bold left">
			<a id='profile-contacts-shower' class="cblack" href='javascript:;'><?=t("Контакты")?></a>
		</div>
		<!--<span style="" class=" cgray">&nbsp;(<?=t('видны только администрации сайта')?>)</span>-->
		<div class="clear"></div>
		<div id="profile-contacts" class="pt10" style="">
			<? $user_contacts = profile_peer::instance()->get_contacts($profile["user_id"]); ?>
			<? $hidden_data = unserialize($profile['hidden_data']) ?>
			<? if($profile['subdomain']){ ?>
				<div class="pt5 pl25" style="background: url('/icon_m.png') no-repeat; height: 19px;">
					<a href='https://<?=$profile['subdomain']?>.<?=conf::get('server')?>/'>https://<?=$profile['subdomain']?>.<?=conf::get('server')?></a>
				</div>
			<? } ?>
			<? if(($user_contacts['_email']['access'] == 1 || session::has_credential('admin')) && ($user_contacts['email'] || $profile['email'])){ ?>
				<? if(session::has_credential('admin') && $user_contacts['_email']['access'] != 1){ $class = "cgray"; } else { $class = ""; } ?>
				<div class="pt5 pl25 <?=$class?>" style="background: url('/contacts_mail.png') no-repeat; height: 19px;">
					<a class="<?=$class?>" href='mailto:<?=($user_contacts['email']) ? $user_contacts['email'] : $profile['email']?>'><?=($user_contacts['email']) ? $user_contacts['email'] : $profile['email']?></a><? if($hidden_data['email'] != '' && session::has_credential('admin')){ ?><span class="cgray"> (<?=$hidden_data['email']?>)</span><? } ?>
				</div>
			<? } ?>
			<? if(($user_contacts['_phone']['access'] == 1 || session::has_credential('admin')) && $user_contacts['phone']){ ?>
				<? if(session::has_credential('admin') && $user_contacts['_phone']['access'] != 1){ $class = "cgray"; } else { $class = ""; } ?>
				<div class="pt5 pl25 <?=$class?>" style="background: url('/contacts_tel.png') no-repeat; height: 19px;">
					<?=$user_contacts['phone']?><? if($hidden_data['phone'] != '' && session::has_credential('admin')){ ?><span class="cgray"> (<?=$hidden_data['phone']?><? if($hidden_data['alt_tel'] != ''){ ?>, <?=$hidden_data['alt_tel']?><? } ?>)</span><? } ?>
				</div>
			<? } ?>
			<? if(($user_contacts['_website']['access'] == 1 || session::has_credential('admin')) && $user_contacts['website']){ ?>
				<? if(session::has_credential('admin') && $user_contacts['_website']['access'] != 1){ $class = "cgray"; } else { $class = ""; } ?>
				<div class="pt5 pl25 <?=$class?>" style="background: url('/contacts_site.png') no-repeat; height: 19px;">
					<a class="<?=$class?>" href='<?=$user_contacts['website']?>'><?=$user_contacts['website']?></a>
				</div>
			<? } ?>
			<? if(($user_contacts['_skype']['access'] == 1 || session::has_credential('admin')) && $user_contacts['skype']){ ?>
				<? if(session::has_credential('admin') && $user_contacts['_skype']['access'] != 1){ $class = "cgray"; } else { $class = ""; } ?>
				<div class="pt5 pl25 <?=$class?>" style="background: url('/contacts_skype.png') no-repeat; height: 19px;">
					<a class="<?=$class?>" href='skype:<?=$user_contacts['skype']?>?call'><?=$user_contacts['skype']?></a>
				</div>
			<? } ?>
			<? if(($user_contacts['_icq']['access'] == 1 || session::has_credential('admin')) && $user_contacts['icq']){ ?>
				<? if(session::has_credential('admin') && $user_contacts['_icq']['access'] != 1){ $class = "cgray"; } else { $class = ""; } ?>
				<div class="pt5 pl25 <?=$class?>" style="background: url('/contacts_icq.png') no-repeat; height: 19px;">
					<?=$user_contacts['icq']?>
				</div>
			<? } ?>
			<? if(
					$user_contacts['facebook'] ||
					$user_contacts['napodiume'] ||
					$user_contacts['vkontakte']
				){ ?>
				<div>
					<div class="left"></div>
					<? if(($user_contacts['_facebook']['access'] == 1 || session::has_credential('admin')) && $user_contacts['facebook']){ ?>
						<div class="left mr5">
							<? if(strpos($user_contacts['facebook'], 'facebook.com') === false){ ?>
								<? $user_contacts['facebook'] = 'facebook.com/'.$user_contacts['facebook']; ?>
							<? } ?>
							<? if(strpos($user_contacts['facebook'], 'http') === false){ ?>
								<? $user_contacts['facebook'] = 'https://'.$user_contacts['facebook']; ?>
							<? } ?>
							<a target="_blank" href="<?=$user_contacts['facebook']?>"><img src="/contacts_facebook.png" /></a>
						</div>
					<? } ?>
					<? if(($user_contacts['_napodiume']['access'] == 1 || session::has_credential('admin')) && $user_contacts['napodiume']){ ?>
						<div class="left mr5">
							<? if(strpos($user_contacts['napodiume'], 'napodiume.ru') === false){ ?>
								<? $user_contacts['napodiume'] = 'napodiume.ru/'.$user_contacts['napodiume']; ?>
							<? } ?>
							<? if(strpos($user_contacts['napodiume'], 'http') === false){ ?>
								<? $user_contacts['napodiume'] = 'https://'.$user_contacts['napodiume']; ?>
							<? } ?>
							<a target="_blank" href="<?=$user_contacts['napodiume']?>"><img src="/contacts_butterfly.png" /></a>
						</div>
					<? } ?>
					<? if(($user_contacts['_vkontakte']['access'] == 1 || session::has_credential('admin')) && $user_contacts['vkontakte']){ ?>
						<div class="left mr5">
							<? if(
								strpos($user_contacts['vkontakte'], 'vkontakte.ru') === false &&
								strpos($user_contacts['vkontakte'], 'vk.com') === false
							){ ?>
								<? $user_contacts['vkontakte'] = 'vk.com/'.$user_contacts['vkontakte']; ?>
							<? } ?>
							<? if(strpos($user_contacts['vkontakte'], 'http') === false){ ?>
								<? $user_contacts['vkontakte'] = 'https://'.$user_contacts['vkontakte']; ?>
							<? } ?>
							<a target="_blank" href="<?=$user_contacts['vkontakte']?>"><img src="/contacts_vk.png" /></a>
						</div>
					<? } ?>
					<div class="clear"></div>
				</div>
			<? } ?>
		</div>
	</div>
<? // } ?>
<script type="text/javascript">
	$(document).ready(function(){
		var contacts = new Object({
			height: $('#profile-contacts').height()
		});
		
		$('#profile-contacts-shower').click(function(){
			if($('#profile-contacts').is(':visible'))
			{
				$('#profile-contacts').animate({
					opacity: 0,
					height: 0
				}, 256, function(){
					$(this).hide();
				});
			}
			else
			{
				$('#profile-contacts')
					.show()
					.animate({
						opacity: 1,
						height: contacts.height
					}, 256);
			}
		});
		
		<? if( ! session::has_credential('admin')){ ?>
			$('#profile-contacts-shower').click();
		<? } ?>
			
		if($('#profile-contacts > div').length < 2)
			$('#profile-contacts').parent().hide();
	});
</script>
