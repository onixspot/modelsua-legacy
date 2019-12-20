<?php
/**
 * @param array $profile
 *
 * @return string
 */
return static function ($profile) {
    $t = 't';

    return <<<HTML
        <div class="container p-0">
            <div class="row">
                <div class="col">
                    <a class="block-title text-uppercase font-weight-bold"
                       href="javascript:void"
                       id="profile-contacts-shower">
                        {$t('Контакты')}
                    </a>
                </div>
            </div>
            
            <div class="row pt-2" id="profile-contacts">
                <div>
                    <i></i>
                    <span></span>
</div>
            </div>
        </div>
HTML;
};

?>
<div class="container p-0">
    <div class="row">
        <div class="col">
            <a href="javascript:void"
               class="block-title text-uppercase font-weight-bold"
               id="profile-contacts-shower">
                <?= t('Контакты') ?>
            </a>
        </div>
    </div>

    <div id="profile-contacts" class="pt-2">
        <?php $user_contacts = profile_peer::instance()->get_contacts($profile['user_id']); ?>
        <?php $hidden_data = unserialize($profile['hidden_data']) ?>
        <?php if ($profile['subdomain']) { ?>
            <div class="pt5 pl25" style="background: url('/icon_m.png') no-repeat; height: 19px;">
                <a href='https://<?= $profile['subdomain'] ?>.<?= conf::get('server') ?>/'>https://<?= $profile['subdomain'] ?>.<?= conf::get('server') ?></a>
            </div>
        <?php } ?>
        <?php if (($user_contacts['_email']['access'] == 1 || session::has_credential('admin')) && ($user_contacts['email'] || $profile['email'])) { ?>
            <?php if (session::has_credential('admin') && $user_contacts['_email']['access'] != 1) {
                $class = 'cgray';
            } else {
                $class = '';
            } ?>
            <div class="pt5 pl25 <?= $class ?>" style="background: url('/contacts_mail.png') no-repeat; height: 19px;">
                <a class="<?= $class ?>" href='mailto:<?= ($user_contacts['email']) ? $user_contacts['email'] : $profile['email'] ?>'><?= ($user_contacts['email']) ? $user_contacts['email'] : $profile['email'] ?></a><?php if ($hidden_data['email'] != '' && session::has_credential('admin')) { ?>
                    <span class="cgray"> (<?= $hidden_data['email'] ?>)</span><?php } ?>
            </div>
        <?php } ?>
        <?php if (($user_contacts['_phone']['access'] == 1 || session::has_credential('admin')) && $user_contacts['phone']) { ?>
            <?php if (session::has_credential('admin') && $user_contacts['_phone']['access'] != 1) {
                $class = 'cgray';
            } else {
                $class = '';
            } ?>
            <div class="pt5 pl25 <?= $class ?>" style="background: url('/contacts_tel.png') no-repeat; height: 19px;">
                <?= $user_contacts['phone'] ?><?php if ($hidden_data['phone'] != '' && session::has_credential('admin')) { ?><span class="cgray"> (<?= $hidden_data['phone'] ?><?php if ($hidden_data['alt_tel'] != '') { ?>, <?= $hidden_data['alt_tel'] ?><?php } ?>)</span><?php } ?>
            </div>
        <?php } ?>
        <?php if (($user_contacts['_website']['access'] == 1 || session::has_credential('admin')) && $user_contacts['website']) { ?>
            <?php if (session::has_credential('admin') && $user_contacts['_website']['access'] != 1) {
                $class = 'cgray';
            } else {
                $class = '';
            } ?>
            <div class="pt5 pl25 <?= $class ?>" style="background: url('/contacts_site.png') no-repeat; height: 19px;">
                <a class="<?= $class ?>" href='<?= $user_contacts['website'] ?>'><?= $user_contacts['website'] ?></a>
            </div>
        <?php } ?>
        <?php if (($user_contacts['_skype']['access'] == 1 || session::has_credential('admin')) && $user_contacts['skype']) { ?>
            <?php if (session::has_credential('admin') && $user_contacts['_skype']['access'] != 1) {
                $class = 'cgray';
            } else {
                $class = '';
            } ?>
            <div class="pt5 pl25 <?= $class ?>" style="background: url('/contacts_skype.png') no-repeat; height: 19px;">
                <a class="<?= $class ?>" href='skype:<?= $user_contacts['skype'] ?>?call'><?= $user_contacts['skype'] ?></a>
            </div>
        <?php } ?>
        <?php if (($user_contacts['_icq']['access'] == 1 || session::has_credential('admin')) && $user_contacts['icq']) { ?>
            <?php if (session::has_credential('admin') && $user_contacts['_icq']['access'] != 1) {
                $class = 'cgray';
            } else {
                $class = '';
            } ?>
            <div class="pt5 pl25 <?= $class ?>" style="background: url('/contacts_icq.png') no-repeat; height: 19px;">
                <?= $user_contacts['icq'] ?>
            </div>
        <?php } ?>
        <?php if (
            $user_contacts['facebook'] ||
            $user_contacts['napodiume'] ||
            $user_contacts['vkontakte']
        ) { ?>
            <div>
                <div class="left"></div>
                <?php if (($user_contacts['_facebook']['access'] == 1 || session::has_credential('admin')) && $user_contacts['facebook']) { ?>
                    <div class="left mr5">
                        <?php if (strpos($user_contacts['facebook'], 'facebook.com') === false) { ?>
                            <?php $user_contacts['facebook'] = 'facebook.com/'.$user_contacts['facebook']; ?>
                        <?php } ?>
                        <?php if (strpos($user_contacts['facebook'], 'http') === false) { ?>
                            <?php $user_contacts['facebook'] = 'https://'.$user_contacts['facebook']; ?>
                        <?php } ?>
                        <a target="_blank" href="<?= $user_contacts['facebook'] ?>"><img src="/contacts_facebook.png"/></a>
                    </div>
                <?php } ?>
                <?php if (($user_contacts['_napodiume']['access'] == 1 || session::has_credential('admin')) && $user_contacts['napodiume']) { ?>
                    <div class="left mr5">
                        <?php if (strpos($user_contacts['napodiume'], 'napodiume.ru') === false) { ?>
                            <?php $user_contacts['napodiume'] = 'napodiume.ru/'.$user_contacts['napodiume']; ?>
                        <?php } ?>
                        <?php if (strpos($user_contacts['napodiume'], 'http') === false) { ?>
                            <?php $user_contacts['napodiume'] = 'https://'.$user_contacts['napodiume']; ?>
                        <?php } ?>
                        <a target="_blank" href="<?= $user_contacts['napodiume'] ?>"><img src="/contacts_butterfly.png"/></a>
                    </div>
                <?php } ?>
                <?php if (($user_contacts['_vkontakte']['access'] == 1 || session::has_credential('admin')) && $user_contacts['vkontakte']) { ?>
                    <div class="left mr5">
                        <?php if (
                            strpos($user_contacts['vkontakte'], 'vkontakte.ru') === false &&
                            strpos($user_contacts['vkontakte'], 'vk.com') === false
                        ) { ?>
                            <?php $user_contacts['vkontakte'] = 'vk.com/'.$user_contacts['vkontakte']; ?>
                        <?php } ?>
                        <?php if (strpos($user_contacts['vkontakte'], 'http') === false) { ?>
                            <?php $user_contacts['vkontakte'] = 'https://'.$user_contacts['vkontakte']; ?>
                        <?php } ?>
                        <a target="_blank" href="<?= $user_contacts['vkontakte'] ?>"><img src="/contacts_vk.png"/></a>
                    </div>
                <?php } ?>
                <div class="clear"></div>
            </div>
        <?php } ?>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var contacts = new Object({
            height: $('#profile-contacts').height()
        });

        $('#profile-contacts-shower').click(function () {
            if ($('#profile-contacts').is(':visible')) {
                $('#profile-contacts').animate({
                    opacity: 0,
                    height: 0
                }, 256, function () {
                    $(this).hide();
                });
            } else {
                $('#profile-contacts')
                    .show()
                    .animate({
                        opacity: 1,
                        height: contacts.height
                    }, 256);
            }
        });

        <?php if( !session::has_credential('admin')){ ?>
        $('#profile-contacts-shower').click();
        <?php } ?>

        if ($('#profile-contacts > div').length < 2)
            $('#profile-contacts').parent().hide();
    });
</script>
