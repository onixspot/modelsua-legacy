<div style="width: 1000px; margin: 0px auto; padding: 0px;" class="footer_box">
    <div class="footer_logo left">
        <img src="http://<?=conf::get("server")?>/<?=session::get('language','ru')?>/logo_f.png" class="left" />
    </div>
    <div class="links left">
			<? foreach($menu_items as $menuItem){ ?>
				<? if( ! $menuItem["hidden"]){ ?>
						<a 
							href="<?=$menuItem["href"]?>"
							<? if(0 && $menuItem["href"] == $_SERVER["REQUEST_URI"]){ ?>
								class="selected"
							<? } ?>
							>
							<?=$menuItem["html"]?>
						</a>
				<? } ?>
			<? } ?>
        <a href="/feedback"><?=t('Обратная связь')?></a>
    </div>
	<div class="clear"></div>
</div>