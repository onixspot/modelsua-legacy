<div class="news-list-container" style="width: 708px;">
    <div class="small-title square_p pl10 mb15 mt15">
        <a href="/news?type=1"><?=t($title)?></a>
    </div>
    <div class="clear"></div>
    <? $was = false; ?>
    <? if($list){ ?>
		<? foreach($list as $id){ ?>
            <? $news = news_peer::instance()->get_content($id) ?>
			<? if( ! $news['hidden'] || session::has_credential('admin')){ ?>    
				<? if($news['end_ts'] < time() && ! $was && $news['type'] == news_peer::ANONS_TYPE){ ?>
					<? $was = true;?>
					<div class="small-title square_p pl10 mb15 mt15">
						<a href="/news?type=1"><?=t('Прошлые события')?></a>
					</div>
				<? } ?>
				<div class="one-news-box">
					<div class="right-box left">
						<a href="/news/view?id=<?=$id?>">
							<img style="width: 100px" src="https://img.<?=conf::get('server')?>/pp/<?=$news['salt']?>">
						</a>
					</div>
					<div class="left-box left ml15">
						<div>
							<a class="cpurple fs17 bold" href="/news/view?id=<?=$news['id']?>"><?=$news['title']?></a>
						</div>
						<? if($news['author']){ ?>
							<div class="fs14 mt5 cblack bold italic"><?=$news['author']?></div>
						<? } ?>
						<div>
							<div class="content_date left fs12 italic">
								<i><?=ui_helper::display_date($news['created_ts'])?><?=t('г.')?></i>
							</div>
							<?if(session::has_credential('admin')) {?>
								<div class="mt5 content_date right fs12">
									Просмотров:&nbsp;<?=content_views_peer::getContentViews($news['id'], content_views_peer::TYPE_NEWS)?>
								</div>
							<? } ?>
							<div class="clear"></div>
						</div>
						<div class="news-description cblack fs12 mt5">
							<?=(strlen($news['anons'])>3) ? mb_substr($news['anons'],0,200).'...<span class="pl10 pr5 arrow_p"></span>' : '';?>
						</div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="news-line-bottom"></div>
			<? } ?>
		<? } ?>
	<? } ?>
</div>
<style type="text/css">
    div.one-news-box {
        margin-bottom: 10px;
    }
    div.right-box {
        width: 100px;
    }
    div.left-box {
        width: 500px;
        
    }
    div.date-info {
        
    }
    div.news-title {
        font-size: 17px;
        font-weight: bold;
    }
    div.news-description {
        
    }
    div.news-line-bottom {
        height: 1px;
        background: #E5E8EE;
        margin-top: 10px;
        margin-bottom: 10px;
    }
</style>

