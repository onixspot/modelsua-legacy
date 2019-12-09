<div class="error mt10 left">
    <div class="left" style="width:710px">
        <h2 class="error mt10"><?='404 '.t('Страничка')?> <?=$error_message ? ': ' . $error_message : ''?></h2>
    </div>
</div>
<br />
<? if ( !$error_message ) { ?>
	<p><?=t('Воспользуйтесь поиском по сайту или перейдите на <a href="https://ukrmodels.org">главую страничку</a>')?>.</p>
<? } ?>
<br /><br />