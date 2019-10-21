<div class="mt5 mb5 fs12 p10" style="border: 1px solid #b95383;">

    <? include 'admin_menu.php' ?>

    <div class="left" style="width: 758px">

        <?php if ($adminka['frame'] === 'agency_manager') { ?>
            <div class="p10 mb10 bold cwhite" style="background: #b95383; border-radius: 4px;">Менеджер агентств</div>
            <?php if ($adminka['act'] === 'edit') { ?>
                <?php include 'agency_manager/edit.php'; ?>
            <?php } else { ?>
                <div class="mb10">
                    <input type="button" value="Добавить"
                           onclick="window.location = '/adminka/agency_manager?act=edit'"/>
                    <input type="button" id="agency-button-remove" value="Удалить"/>
                </div>
            <?php } ?>
            <?php include 'agency_manager/list.php'; ?>
        <?php } ?>

        <?php if ($adminka['frame'] === 'user_manager') { ?>
            <div class="p10 mb10 bold cwhite" style="background: #b95383; border-radius: 4px;">Менеджер участников</div>
            <?php switch ($adminka['act']) {
                case 'invitation_messages':
                    include 'user_manager/invitation_messages.php';
                    break;

                case 'edit':
                    include 'user_manager/edit.php';
                    break;

                default:
                    include 'user_manager/list.php';
            }
        } ?>

        <?php if ($adminka['frame'] === 'email_templates') { ?>
            <div class="p10 mb10 bold cwhite" style="background: #b95383; border-radius: 4px;"><a href="/adminka/email_templates" class="mr10 cwhite">Шаблоны
                    сообщений</a><a href="/adminka/email_templates?act=edit" class="right mr10 cwhite">Добавить</a></div>
            <?php if ($adminka['act'] === 'edit') { ?>
                <?php include 'email_templates/edit.php'; ?>
            <?php } else { ?>
                <?php include 'email_templates/list.php'; ?>
            <?php } ?>
        <?php } ?>

        <?php if ($adminka['frame'] === 'list_management') { ?>
            <div class="p10 mb10 bold cwhite" style="background: #b95383; border-radius: 4px;">
                <div class="left"><a href="/adminka/list_management" class="mr10 cwhite">Управление списками</a></div>
                <div class="right"><a href="/adminka/list_management?act=edit" class=" mr10 cwhite">Добавить</a></div>
                <div class="clear"></div>
            </div>
            <?php if ($adminka['act'] === 'edit') { ?>
                <?php include 'list_management/form.php'; ?>
            <?php } else { ?>
                <?php include 'list_management/list.php'; ?>
            <?php } ?>
        <?php } ?>

        <?php if ($adminka['frame'] === 'journals_manager') { ?>
            <div class="p10 mb10 bold cwhite" style="background: #b95383; border-radius: 4px;">
                <div class="left"><a href="/adminka/journals_manager" class="mr10 cwhite">Управление журналами</a></div>
                <div class="right"><a href="/adminka/journals_manager?act=add" class=" mr10 cwhite">Добавить</a></div>
                <div class="clear"></div>
            </div>
            <?php if (in_array($adminka['act'], ['add', 'edit'])) { ?>
                <?php include 'journals_manager/form.php'; ?>
            <?php } else { ?>
                <?php include 'journals_manager/list.php'; ?>
            <?php } ?>
        <?php } ?>

    </div>

    <div class="clear"></div>

</div>
