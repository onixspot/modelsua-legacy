<div class="mt30 fs12">

    <div class="row">
        <div class="col square_p">
            <a class="cblack text-uppercase bold" href='javascript:void(0);'><?= t('Работы') ?></a>
        </div>
        <?php if (session::get_user_id() === $user_id || session::has_credential('admin')) { ?>
            <div class="col text-right">
                <a class="underline cgray"
                   href="javascript:void(0);"
                   onclick="
							if($('#window-categories_h').is(':visible'))
								$('#window-categories_h').animate({
									'opacity': '0'
								}, 256, function(){
									$(this).hide();
								});
							else
								$('#window-categories_h')
									.show()
									.css('opacity', '0')
									.animate({
										'opacity': '1'
									}, 256);
						" onmouseout="$(this).css('color', 'gray')"
                   onmouseover="$(this).css('color', 'black')"><?= t('Добавить работу') ?></a>
            </div>
        <?php } ?>
    </div>

    <?php $flag = false; ?>
    <?php $cnt = 0; ?>
    <?php foreach ($hronologies as $hronology) { ?>
        <?php if ($hronology['name'] === '') { ?>
            <?php continue; ?>
        <?php } ?>
        <?php $flag = true; ?>
        <div class="pt10 pb10" style="<?php if ($cnt > 0) { ?>border-top: 1px solid #eee<?php } ?>">
            <div class="left" style="width: 300px">
                <div><?= t($hronology['category']) ?> :: <a href='<?= $hronology['link'] ?>'><?= $hronology['name'] ?></a></div>
                <!--<div class="fs10 cgray">Австрия</div>-->
            </div>
            <div class="right cgray">
                <?php if ($hronology['month'] > 0 && $hronology['year'] > 0) { ?>
                    <?= (ui_helper::get_mounth_list($hronology['month'])) ?>, <?= $hronology['year'] ?>
                <?php } else { ?>
                    &mdash;
                <?php } ?>
            </div>
            <div class="clear"></div>
        </div>
        <?php $cnt++ ?>
    <?php } ?>
    <?php if (!$flag) { ?>
        <div class="row text-center cgray mt-2 mb-3 p-3" style="background: #eee;">
            <?= t('Тут еще нет работ') ?>
        </div>
    <?php } ?>
</div>
