<?php

/** @var array $adminka */
$messages = $adminka['messages'];

?>

<?php include 'partials/menu.php'; ?>

<style type="text/css">
    table.invitation-messages-table {
        border: 1px solid #ccc;
    }

    table.invitation-messages-table > thead > tr > th {
        background: #CCCCCC;
    }

    table.invitation-messages-table > tbody > tr:nth-child(odd) > td {
        background: #eee;
    }
</style>

<div>
    <table cellspacing="2" cellpadding="5" width="100%" class="invitation-messages-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Subject</th>
            <th>Body</th>
            <th>Sent At</th>
        </tr>
        </thead>
        <tbody>
        <?php if (count($messages) > 0) { ?>
            <?php foreach ($messages as $messageId) { ?>
                <?php $message = user_invitation_message_peer::instance()->get_item($messageId); ?>
                <tr>

                    <td><?= $message['id'] ?></td>
                    <td><?= $message['title'] ?></td>
                    <td><?= $message['message'] ?></td>
                    <td><?= $message['sent_at'] ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="4">Нет приглашений</td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
