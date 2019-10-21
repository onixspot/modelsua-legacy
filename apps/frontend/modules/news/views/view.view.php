<div class="news-data-container mt20">
	<div id="news-container" style="border-bottom: 1px solid #E5E8EE; width: 708px;">
		<!-- <img class="left mr10 mb5" style="width: 295px;" src="http://img.<?=conf::get('server')?>/np/<?=$news['salt']?>.jpg" /> -->
		<div>
			<div class="left" style="width: 604px;">
				<a href="/news/view?id=<?=$news['id']?>" class="fs20 bold cpurple" style="color: #B95383;">
					<?=$news['title']?>
				</a>
			</div>
			<? if(session::has_credential('admin')){ ?>
				<div class="right">
					<a href="/adminka/news?edit_news=<?=$news['id']?>" class="fs12"><img class="mr5" src="/ui/edit.png"/><?=t('Редактировать')?></a>
				</div>
			<? } ?>
			<div class="clear"></div>
		</div>
		
		<? if($news['author']){ ?>
			<div class="fs14 cblack bold italic">
				<?=$news['author']?>
			</div>
		<? } ?>
		<div class="fs10 italic" style="color: #939AAB">
			<?=ui_helper::display_date($news['created_ts'])?> г.
		</div>
		<? if(count($news["models"]) > 0){ ?>
		<div class="mt10 mb10 pt10 pb10" style="border-top: 1px solid #E5E8EE; border-bottom: 1px solid #E5E8EE;">
			<? foreach($news["models"] as $id){ ?>
				<? $user_data = user_data_peer::instance()->get_item($id); ?>
				<div class="left mr20">
					<a href="/profile?id=<?=$id?>"><?=profile_peer::get_name($user_data)?></a>
				</div>
			<? } ?>
			<div class="clear"></div>
		</div>
		<? } ?>
		<p class="fs13" style="margin-bottom: 0px;">
			<?=$news['body']?>
		</p>
	</div>
	
	<div class="mb10 pt10 pb10" style="border-bottom: 1px solid #E5E8EE; width: 708px">
		<a href="/news?type=2">Все публикации</a>
	</div>
	
    <div class="social-networks mt10 mb10" style="width: 708px">
        <div class="left mr30">
            <script type="text/javascript"><!--
                document.write(VK.Share.button(false,{type: "round", text: "Поділитися"}));
            --></script>
        </div>
        <div class="left mr30">
			<div id="fb-root"></div>
			<script>(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/ru_RU/all.js#xfbml=1";
			fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));</script>
			<div class="fb-like" data-href="http://<?=$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"]?>" data-send="false" data-layout="button_count" data-width="140" data-show-faces="true"></div>
        </div>
        <div class="left mr20">
            <g:plusone size="medium" width="80"></g:plusone>
        </div>
        <div class="left mr20">
            <a href="https://twitter.com/share" class="twitter-share-button" data-lang="ru">Твитнуть</a>
        </div>
        <div class="left print_link"  style="background: url('/ui/print_1.png') no-repeat 0 0 scroll transparent; padding-left: 20px;">
			<a href="javascript:;" onClick="App.printPage('news-container')" style="line-height: 16px;" class="cgray underline fs12"><?=t('печать')?></a>
        </div>
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		<?if(session::has_credential('admin')) {?>
			<div class="left fs12 cgray" style="margin-left: 30px; line-height: 20px;">
				<?=content_views_peer::getContentViews(request::get_int('id',0), content_views_peer::TYPE_NEWS);?>
			</div>
		<? } ?>
		<div class="clear"></div>
    </div>
</div>
<script>App.getClientInfo('<?=content_views_peer::TYPE_NEWS?>','<?=request::get_int('id')?>');</script>