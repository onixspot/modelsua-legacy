<div class="mt15 mb5 fs10" style="width: 1000px;">
	<div class="left mr20 mt5" style="width: 262px;">
		<a href="/"><img src="/<?=session::get('language','ru')?>/logo.png" style="margin-bottom: 10px" /></a>
	</div>
	<!--<div class="left mt5" style="margin-top: -10px;">
		<a href="/people"><img src="/banners/<?=session::get('language','ru')?>/banner.gif" /></a>
	</div>-->
	<!--<div class="left mt5" style="margin-top: 3px; color: #000000; width: 520px; text-align: center; font-size: 21px; font-style: italic;">-->
	<!--	--><?//=t('Все об украинских моделях')?><!--<br />--><?//=t('и модельном бизнесе')?>
	<!--</div>-->
	<div class="right" style="width: 172px;<?=(session::is_authenticated()) ? ' padding-right: 25px;margin-top: 10px;' : 'margin-top: -22px;'?>">
	    <? $style =  (!session::is_authenticated()) ? 'margin-top: 5px;' : ''?>
	    <div class="languages_box bold aright mb5 fs11" style="<?=$style?>">
		
	    <?if(session::get('language')!='ru') {?>
		<a href="/sign/language?code=ru">рус</a> | en
	    <?} else {?>
		рус | <a href="/sign/language?code=en">en</a>
	    <? } ?>
	    </div>
		<? if( ! session::is_authenticated()){ ?>
			<form id="Authorization" name="Authorization" class="login_form" action="/sign/authorization" method="post">
				<div class="mb5">
					<input type="text" id="login" name="login" style="width: 100%;" value="" />
				</div>
				<div>
					<input type="password" id="password" name="password" style="width: 100%;" value="" />
				</div>
				<div>
					<div class="left pt5">
						<a href="javascript:;" onclick="$('#restore_form, .login_form').toggleClass('hide');" style="color: #B1B7C5;"><?=t('Забыли пароль')?>?</a>
					</div>
					<div class="right pt5">
						<input type="submit" id="submit" name="submit" value="Войти" class="hide" />
						<a href="javascript:;" id="submit-1" class="ucase bold" style="color: #000000">Войти</a>
					</div>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
			</form>
			<script type="text/javascript">
				$(document).ready(function(){
					var success = false;
					var form = new Form("Authorization");
					form.onSuccess = function(data){
						if( ! data.success){
							$("#login")
								.animate({"boxShadow": "0px 0px 8px #000000"}, 200, function(){
									setTimeout(function(){
										$("#login").animate({"boxShadow": "0px 0px 5px #fff"}, 1000);
									}, 1000);
								});
							$("#password")
								.animate({"boxShadow": "0px 0px 8px #000000"}, 200, function(){
									setTimeout(function(){
										$("#password").animate({"boxShadow": "0px 0px 5px #fff"}, 1000);
									}, 1000);
								});
							Popup.show();
							Popup.setTitle('<div class="fs14"><?=t('Ошибка авторизации')?></div>');
							Popup.setHtml('<div class="acenter fs12 tahoma"><?=t('Неверный логин или пароль')?></div><div class="acenter mt20"><input type="button" value="<?=t('Закрыть')?>" onClick="Popup.close()"/></div>');
							Popup.position();
							setTimeout("Popup.close()",5000);
							return success = false;
						}
						setTimeout(redirect(), 100);
						return success = true;
					}
					$("#login")
					    .keydown(function(e){
						if(e.keyCode == 13){
							form.send();
						}
					    }).blur(function() {
						if($('#login').val()=='') $('#login').val('E-mail');
					    }).focus(function() {
						if($('#login').val()=='E-mail') $('#login').val('');
					    });
					$("#password")
					    .keydown(function(e){
						    if(e.keyCode == 13){
							    form.send();
						    }
					    }).blur(function() {
						if($('#password').val()=='') $('#password').val('trololo');
					    }).focus(function() {
						if($('#password').val()=='trololo') $('#password').val('');
					    });
					$("#Authorization #submit").click(function(){
						form.send();
					});
					
					var redirect = function()
					{
						$('#Authorization')
							.attr({
								'action': '/sign/authorization?act=redirect',
								'onsubmit': 'return true;'
							});
						
						$('#Authorization #submit').click();
					}
					
					$('#Authorization #submit-1').click(function(){
						$('#Authorization #submit').click();
					});
				});
			</script>
		<? } else { ?>
			<? load::model("user/user_data"); ?>
			<? $user_data = profile_peer::instance()->get_item(session::get_user_id()); ?>
			<div class="fs12 login_form" style="text-align: right; width: 172px;">
				<div class="mb5">
					<a id="user-link" href="/profile?id=<?=$user_data["user_id"]?>" class="fs14" style="border-bottom: 1px dotted #000000;"><?=profile_peer::get_name($user_data);?></a>
				</div>
				<? 
				    $new_users = db::get_scalar("SELECT COUNT(a.id) FROM user_auth a JOIN user_data d ON a.id=d.user_id  WHERE a.approve=0 AND a.del=0 AND a.registrator=0 AND a.reserv=0");
				    $active = db::get_scalar("SELECT COUNT(a.id) FROM user_auth a JOIN user_data d ON a.id=d.user_id WHERE a.active=true AND d.status=21 AND a.del=0");
				?>	
				<? if(session::has_credential("admin")){ ?>
				    <a class="cgray" href="/adminka/user_manager?filter=byself-new"><?=t('Заявки')?></a>&nbsp;<b class="cgray">(<?=$new_users?>)</b> | 
				    <a  class="cgray" href="/adminka/user_manager&status=21&filter=all&active=true"><?=t('Активные')?></a>&nbsp;<b class="cgray">(<?=$active?>)</b><br/> 
				<? } ?>
			    
			</div>
			
			<style>
			    #profile-menu-tooltip-box {
				position: absolute; 
			    }
			    #profile-menu-tooltip {
				width: 120px; 
				font-size: 11px; 
				text-align: center;
				background: none repeat scroll 0% 0% #b0b7c5; 
				color: white; 
				font-size: 12px;
				box-shadow: 5px 5px 5px #AAAAAA;  
				z-index: 999;
			    }
			     #profile-menu-tooltip a {
				 color: white;
				 text-decoration: underline;
			     }
			     #profile-menu-tooltip a:hover {
				 text-decoration: none;
			     }
			</style>
			<div id="profile-menu-tooltip-box" class="hide">
			    <div style="background: url('/arrow_top.png') no-repeat center 0 scroll transparent; height: 4px;"></div>
			    <div class="p10" id="profile-menu-tooltip">

				<a href="/profile?id=<?=$user_data["user_id"]?>"><?=t('Профиль')?></a><br/>
				<?
				    load::action_helper('ac',false);
				    $not_viewed = ac_helper::get_not_viewed();
				?>
                                <? if(session::has_credential("admin") || $user_data['can_write']){ ?>
				<a href="/messages"><?=t('Сообщения')?></a>&nbsp;<?=($not_viewed['all'] ? "(<b>".$not_viewed['all']."</b>)" : '')?><br/>
				<? } ?>
				<? if(session::has_credential("admin")){ ?>
				    *<a href="/adminka/user_manager?filter=byself-new"><?=t('Админка')?></a> <br/> 
				    *<a href="/forum"><?=t('Форум')?></a><br/>
				<? } ?>
				
				<a href="javascript:;" id="logout"><?=t('Выйти')?></a><br/>

			    </div>
			</div>
			
			<script type="text/javascript">
			    var show=false;
			    var tooltip = $('#profile-menu-tooltip-box');
				$('#user-link').hover(
				    function() {
					tooltip
					    .removeClass('hide')
					    .css({
						'left': ($(this).position().left-15)+'px',
						'top': ($(this).position().top+$(this).innerHeight()+2)+'px'
						});
				    },
				    function(){
					setTimeout("{if(!show) tooltip.addClass('hide')}",100);
				    }
				);
				tooltip.hover(
				    function(){
					show=true;
				    },
				    function(){
					$(this).addClass('hide');
					show=false;
				    }
				);
				$(document).ready(function(){
					
					$("#logout").click(function(){
						$.post("/sign/logout", {}, function(data){
							if(data.success)
								location.reload();
						}, "json");
					});
				});
			</script>
		<? } ?>
		<form action="/sign/restorepasswd" class="restore_form hide" id="restore_form">
		    <div class="mb5">
			<input type="text" id="restore_email" name="restore_email" style="width: 100%; color: #B1B7C5;" value="<?=t('Введите е-mail')?>" />
		    </div>
		    <div class="left pt5">
			    <a href="javascript:;" onclick="$('#restore_form, .login_form').toggleClass('hide');" style="color: #B1B7C5;"><?=t('Вход')?></a>
		    </div>
		    <div class="right pt5">
			    <input type="submit" id="restore" name="restore" value="Войти" class="hide" />
			    <a href="javascript:;" id="restore-1" class="ucase bold" style="color: #000000">Отправить</a>
		    </div>
		</form>
		<script>
		    $(function(){
			$('#restore_email').focus(function(){
			    if($(this).val()=='Введите е-mail')
				$(this).val('');
			});
			var restore_form = new Form('restore_form');
			restore_form.onSuccess = function(resp) {
			    Popup.show();
			    Popup.setTitle('<div class="fs14 pl10 pr10"><?=t('Восстановление пароля')?></div>');
			    if(resp.success)
			    {
				Popup.setHtml('<div class="acenter fs12 tahoma"><?=t('Новый пароль выслан на указанный e-mail')?></div><div class="acenter mt20"><input type="button" value="<?=t('Закрыть')?>" onClick="Popup.close()"/></div>');
			    }
			    else 
			    {
				Popup.setHtml('<div class="acenter fs12 tahoma">'+resp.reason+'</div><div class="acenter mt20"><input type="button" value="<?=t('Закрыть')?>" onClick="Popup.close()"/></div>');
			    }
			    Popup.position();
			    setTimeout("Popup.close()",3500);
			}
			$('#restore-1').click(function(){
				restore_form.send();
			});
		    });
		</script>
	</div>
	<div class="clear"></div>
</div>
