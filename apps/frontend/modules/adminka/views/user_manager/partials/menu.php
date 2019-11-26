<? //ппц
$all              = db::get_scalar('SELECT COUNT(a.id) FROM user_auth a JOIN user_data d ON a.id=d.user_id WHERE 1=1 AND a.del= 0 AND a.reserv = 0');
$all_no_email     = db::get_scalar("SELECT COUNT(a.id) FROM user_auth a JOIN user_data d ON a.id=d.user_id WHERE 1=1 AND a.del= 0 AND a.reserv = 0 AND (a.email = '' OR a.email IS NULL)");
$all_invited      = db::get_scalar('SELECT COUNT(a.id) FROM user_auth a JOIN user_data d ON a.id=d.user_id WHERE 1=1 AND a.del= 0 AND a.reserv = 0 AND a.last_invite > 0  AND a.active=false');
$byadin           = db::get_scalar('SELECT COUNT(a.id) FROM user_auth a JOIN user_data d ON a.id=d.user_id WHERE 1=1 AND a.del= 0 AND a.reserv = 0 AND a.registrator>0');
$byadmin_no_email = db::get_scalar("SELECT COUNT(a.id) FROM user_auth a JOIN user_data d ON a.id=d.user_id WHERE 1=1 AND a.del= 0 AND a.reserv = 0 AND a.registrator>0 AND (a.email = '' OR a.email IS NULL)");
$byadmin_invited  = db::get_scalar('SELECT COUNT(a.id) FROM user_auth a JOIN user_data d ON a.id=d.user_id WHERE 1=1 AND a.del= 0 AND a.reserv = 0 AND a.last_invite > 0 AND a.registrator>0  AND a.active=false');
$catalog          = db::get_scalar('SELECT COUNT(a.id) FROM user_auth a JOIN user_data d ON a.id=d.user_id WHERE 1=1 AND a.del= 0 AND a.reserv = 0 AND a.hidden = false AND d.status=21');
$selfreg          = db::get_scalar('SELECT COUNT(a.id) FROM user_auth a JOIN user_data d ON a.id=d.user_id WHERE 1=1 AND a.del= 0 AND a.reserv = 0 AND a.registrator=0');
$rezerv           = db::get_scalar('SELECT COUNT(a.id) FROM user_auth a JOIN user_data d ON a.id=d.user_id WHERE 1=1 AND a.reserv > 0');
$archive          = db::get_scalar('SELECT COUNT(a.id) FROM user_auth a JOIN user_data d ON a.id=d.user_id WHERE 1=1 AND a.del > 0');
$applications     = db::get_scalar('SELECT COUNT(a.id) FROM user_auth a JOIN user_data d ON a.id=d.user_id WHERE 1=1 AND d.application>0 AND d.application_approve=0 AND del=0 AND reserv=0');
$candidates       = db::get_scalar('SELECT COUNT(a.id) FROM user_auth a JOIN user_data d ON a.id=d.user_id WHERE 1=1 AND d.application>0 AND d.application_approve=1 AND d.status=24 AND del=0 AND reserv=0');
$members          = db::get_scalar('SELECT COUNT(a.id) FROM user_auth a JOIN user_data d ON a.id=d.user_id WHERE 1=1 AND d.application>0 AND d.application_approve=1 AND d.status=22 AND del=0 AND reserv=0');
$refuse           = db::get_scalar('SELECT COUNT(a.id) FROM user_auth a JOIN user_data d ON a.id=d.user_id WHERE 1=1 AND d.application>0 AND d.application_approve=2 AND del=0 AND reserv=0');
$membership       = $applications + $members + $refuse + $candidates;
?>
<? $show = (request::get('filter') != '') ? false : true; ?>
<?

$catalog_filter = (request::get('filter') == 'all' && request::get('status') == 21 && request::get('hidden') == 'false') ? true : false;

function build_url($exclude = [])
{
    $params  = [];
    $baseUrl = '/adminka/user_manager';
    $keys    = array_diff(['q', 'status', 'filter', 'active', 'hidden'], $exclude);

    array_walk($keys, static function ($key) use (&$params) {
        if (request::get($key)) {
            $params[$key] = request::get($key);
        }
    });

    return sprintf('%s?%s', $baseUrl, http_build_query($params));
}

?>
<div class="mb10">
    <div class="left">
        <input
                type="button"
                value="Создать участника"
                onclick="window.location = '/adminka/umanager?frame=form'"
        />
    </div>
    <div class="right pt5">
        <a href="/search">Расширенный поиск</a>
    </div>
    <div class="right mr10">
        <input id='search_form' value="<?= ($q = request::get('q')) ?>"/>
        <? $url = build_url(['q']); ?>
        <input
                type="button"
                value="Поиск"
                onclick="window.location = '<?= $url ?>&q='+$('#search_form').val()" ;
        />
    </div>
    <div class="clear"></div>
</div>

<div class="main_user_menu">
    <div class="left mr5 ml5 p5 <? if ($filter[0] == 'all' && request::get('hidden') != 'false'
        && request::get('status') != 21) { ?>  selected <? } ?>">
        <a href="/adminka/user_manager?filter=all">Все&nbsp;(<?= $all ?>)</a>
    </div>
    <div class="left mr5 p5 <? if ($filter[0] == 'byadmin') { ?> selected <? } ?>">
        <a href="/adminka/user_manager?filter=byadmin">Созданные&nbsp;(<?= $byadin ?>)</a>
    </div>
    <div class="left mr5 p5 <? if ($filter[0] == 'byself') { ?> selected <? } ?>">
        <a href="/adminka/user_manager?filter=byself-new">Зарегестрированные&nbsp;(<?= $selfreg ?>)</a>
    </div>
    <div class="left mr5 p5 <? if ($filter[0] == 'membership') { ?> selected <? } ?>">
        <a href="/adminka/user_manager?filter=membership-applications">Членство&nbsp;(<?= $membership ?>)</a>
    </div>
    <div class="left mr5 p5 <? if ($catalog_filter) { ?> selected <? } ?>">
        <a href="user_manager&status=21&filter=all&hidden=false">Каталог&nbsp;(<?= $catalog ?>)</a>
    </div>
    <div class="left mr5 p5 <? if ($filter[0] == 'reserv') { ?> selected <? } ?>">
        <a href="/adminka/user_manager?filter=reserv">Резерв&nbsp;(<?= $rezerv ?>)</a>
    </div>
    <div class="left p5 <? if ($filter[0] == 'del') { ?> selected <? } ?>">
        <a href="/adminka/user_manager?filter=del">Архив&nbsp;(<?= $archive ?>)</a>
    </div>
    <div class="clear"></div>
</div>

<? if ((!in_array(request::get('filter'), ['del', 'reserv']) && request::get('filter')) && !$catalog_filter) { ?>
    <div
            class="mb10 p5 submenu"
            style="border-radius: 4px; background: #000000;"
    >
        <? if (in_array($filter[0], ['all', 'byadmin'])) { ?>
            <div class="left mr10">
                <a <? if ($filter[1] == 'noemail') { ?> class=" selected" <? } ?> href="/adminka/user_manager?filter=<?= $filter[0] ?>-noemail">Без
                    e-mail&nbsp;(<? $var = $filter[0].'_no_email'; ?><?= $$var ?>)</a>
            </div>
            <div class="left mr10">
                <a <? if ($filter[1] == 'invited') { ?> class=" selected" <? } ?> href="/adminka/user_manager?filter=<?= $filter[0] ?>-invited">Все
                    приглашенные&nbsp;(<? $var = $filter[0].'_invited'; ?><?= $$var ?>)</a>
            </div>
        <? } elseif (in_array($filter[0], ['byself'])) { ?>
            <div class="left mr10">
                <a <? if ($filter[1] == 'new') { ?> class=" selected" <? } ?> href="/adminka/user_manager?filter=<?= $filter[0] ?>-new">Новые
                    (<?= db::get_scalar('SELECT COUNT(a.id) FROM user_auth a JOIN user_data d ON a.id=d.user_id  WHERE a.approve=0 AND a.del=0 AND a.registrator=0 AND a.reserv=0') ?>
                    )</a>
            </div>
            <div class="left mr10">
                <a <? if ($filter[1] == 'confirm') { ?> class=" selected" <? } ?> href="/adminka/user_manager?filter=<?= $filter[0] ?>-confirm">В
                    работе
                    (<?= db::get_scalar('SELECT COUNT(a.id) FROM user_auth a JOIN user_data d ON a.id=d.user_id  WHERE a.approve=1 AND a.del=0 AND a.registrator=0 AND a.reserv=0') ?>
                    )</a>
            </div>
            <div class="left mr10">
                <a <? if ($filter[1] == 'confirmed') { ?> class=" selected" <? } ?> href="/adminka/user_manager?filter=<?= $filter[0] ?>-confirmed">Подтвержденные
                    (<?= db::get_scalar('SELECT COUNT(a.id) FROM user_auth a JOIN user_data d ON a.id=d.user_id  WHERE a.approve=2 AND a.del=0 AND a.registrator=0 AND a.reserv=0') ?>
                    )</a>
            </div>
        <? } elseif (in_array($filter[0], ['membership'])) { ?>
            <div class="left mr10">
                <a <? if ($filter[1] == 'applications') { ?> class=" selected" <? } ?>
                        href="/adminka/user_manager?filter=<?= $filter[0] ?>-applications">Заявки&nbsp;(<?= $applications ?>)</a>
            </div>
            <div class="left mr10">
                <a <? if ($filter[1] == 'candidates') { ?> class=" selected" <? } ?> href="/adminka/user_manager?filter=<?= $filter[0] ?>-candidates">Кандидаты&nbsp;(<?= $candidates ?>
                    )</a>
            </div>
            <div class="left mr10">
                <a <? if ($filter[1] == 'members') { ?> class=" selected" <? } ?> href="/adminka/user_manager?filter=<?= $filter[0] ?>-members">Члены&nbsp;(<?= $members ?>
                    )</a>
            </div>
            <div class="left mr10">
                <a <? if ($filter[1] == 'refuse') { ?> class=" selected" <? } ?> href="/adminka/user_manager?filter=<?= $filter[0] ?>-refuse">Отказ&nbsp;(<?= $refuse ?>
                    )</a>
            </div>
        <? } ?>
        <div class="clear"></div>
    </div>
<? } ?>
