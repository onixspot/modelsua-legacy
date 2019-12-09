<div class="pages_container" style="width: 708px;">
    <div class="page_content">
        <div class="inner_content_box m20">
			
			<div class="left" id="print_page_content">
                                <?if($crumbs  && 0) {
                                    foreach ($crumbs as $id => $item) {?>
                                        <a href="/page?link=<?=$item['link']?>"><?=$item['title']?></a>
                                        <?=(isset($crumbs[$id+1])) ? '&rarr;' : '';?>
                                <? } } ?>
				
                                    <h1 class="left" style="margin-top: <?=($crumbs) ? '0px' : '0px'?>;"><?=$content['title']?></h1>
                                    <?if(session::has_credential('admin')) {?>
                                    <div class="right" id="not_for_print">
                                            <a href="/adminka/pages?link=<?=$content['link']?>" class="fs12">
                                                    <img src="/ui/edit.png" class="mr5 "><?=t('редактировать')?>
                                            </a>
                                    </div>
                                    <? } ?>
                                    <div class="clear"></div>
                                    <?=str_replace('%children%', pages_peer::instance()->display($tree,session::get('language')), $content['content'])?>
                                
                        </div>
			<div class="clear"></div>
        </div>
		<div>
			<div class="left"></div>
			<?if($content['share']){ ?>
				<div class="social-networks left mb10">
					<div class="left" style="margin-right: 30px; width: 100px;">
						<script type="text/javascript"><!--
							document.write(VK.Share.button(false,{type: "round", text: '<?=t("Поделиться")?>'}));
						--></script>
					</div>
					<div class="left" style="margin-right: 30px;">
						<div id="fb-root"></div>
						<script>(function(d, s, id) {
						var js, fjs = d.getElementsByTagName(s)[0];
						if (d.getElementById(id)) return;
						js = d.createElement(s); js.id = id;
						js.src = "//connect.facebook.net/ru_RU/all.js#xfbml=1";
						fjs.parentNode.insertBefore(js, fjs);
						}(document, 'script', 'facebook-jssdk'));</script>
						<div class="fb-like" data-href="https://<?=$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"]?>" data-send="false" data-layout="button_count" data-width="140" data-show-faces="true"></div>
					</div>
					<div class="left" style="margin-right: 30px; width: 100px;">
						<a href="https://twitter.com/share" class="twitter-share-button" data-lang="ru"><?=t('Твитнуть')?></a>
					</div>
					<div class="left" style="margin-right: 30px; width: 50px;">
						<g:plusone size="medium"></g:plusone>
					</div>
					<div class="clear"></div>
					<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
				</div>
			<? } ?>
                        
			<?if($content['print']) {?>
				<div class="left print_link"  style="background: url('/ui/print_1.png') no-repeat 0 0 scroll transparent; padding-left: 20px;">
                                        <a href="javascript:;" onClick="App.printPage('print_page_content')" style="line-height: 16px;" class="cgray underline fs12"><?=t('печать')?></a>
                                </div>
			<? } ?>
                        <?if(session::has_credential('admin')) {?>
                            <div class="left fs12 cgray" style="margin-left: 30px; line-height: 20px;">
                                Просмотров:&nbsp;<?=content_views_peer::getContentViews(pages_peer::instance()->get_by_url(request::get('link')), content_views_peer::TYPE_PAGES);?>
                            </div>
                        <? } ?>
			<div class="clear"></div>
		</div>
    </div>
</div>
<style type="text/css">
    .print_link a:hover {text-decoration: none;}
</style>
<script>
    App.getClientInfo('<?=  content_views_peer::TYPE_PAGES?>','<?=pages_peer::instance()->get_by_url(request::get('link'))?>');
</script>
