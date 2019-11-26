<?php

load::app('modules/adminka/controller');
load::model('user/profile');
load::model('user/xprofile');
load::model('agency');

class adminka_umanager_action extends adminka_controller
{
    public function execute()
    {
        parent::execute();

        $act = request::get_string('act');
        if (in_array($act, ['add', 'remove', 'move_to_archive', 'move_to_reserv', 'recove', 'send_mail', 'set_agency'])) {
            $this->set_renderer('ajax');

            return $this->json['success'] = $this->$act();
        }

        $frame = request::get_string('frame');

        $this->frame = 'list';
        if (in_array($frame, ['form'])) {
            $this->frame = $frame;

            return;
        }

        $this->filter = request::get_string('filter');

        $cond = ' AND del = 0 AND reserv = 0';
        if ($this->filter === 'archive') {
            $cond = ' AND del > 0';
        }

        if ($this->filter === 'reserv') {
            $cond = ' AND reserv > 0';
        }

        $this->list = db::get_cols('SELECT id FROM user_auth WHERE type <> 2 '.$cond.' ORDER BY id DESC, created_ts DESC;');

        $this->countAllItems       = db::get_scalar('SELECT COUNT(id) FROM user_auth WHERE type <> 2 AND del = 0 AND reserv = 0;');
        $this->countInArchiveItems = db::get_scalar('SELECT COUNT(id) FROM user_auth WHERE type <> 2 AND del > 0;');
        $this->countInReservItems  = db::get_scalar('SELECT COUNT(id) FROM user_auth WHERE type <> 2 AND reserv > 0;');
    }

    public function set_agency()
    {
        $uid = request::get_int('user_id');
        $aid = request::get_int('agency_id');

        if (!user_data_peer::instance()->get_item($uid)) {
            return false;
        }

        $data = [
            'user_id'           => $uid,
            'manager_agency_id' => $aid,
        ];

        user_data_peer::instance()->update($data);

        return true;
    }

    private function add()
    {
        $user_auth_fields = [
            'email'       => request::get_string('email'),
            'type'        => request::get_int('group'),
            //			'credentials' => serialize(array()),
            'registrator' => session::get_user_id(),
            'security'    => profile_peer::generate_password(16),
            'created_ts'  => time(),
        ];

        $user_data_fields = [
            'first_name' => request::get_string('first_name'),
            'status'     => request::get_int('status'),
        ];

        $msgid = [];
        foreach ($user_data_fields as $key => $val) {
            if ($user_data_fields[$key] === '') {
                $msgid[] = $key;
            }
        }

        $user_data_fields['last_name'] = request::get_string('last_name');

        if ($user_auth_fields['email'] != '' && count(user_auth_peer::instance()->get_list(['email' => $user_auth_fields['email']])) > 0) {
            $msgid[] = 'email';
        }

        if ($user_auth_fields['type'] == 0) {
            $msgid[] = 'group';
        }

        if ($user_data_fields['status'] == 0) {
            $msgid[] = 'status';
        }

        if (count($msgid) > 0) {
            $this->json['msgid'] = implode('&', $msgid);

            return false;
        }

        $credentials = [];
        if (in_array($user_auth_fields['type'], [5, 6])) {
            array_push($credentials, 'admin', 'selfmoderator', 'moderator');
        }

        if ($user_auth_fields['type'] == 6) {
            array_push($credentials, 'superadmin');
        }

        $user_auth_fields['credentials'] = serialize($credentials);

        if (!$uid = user_auth_peer::instance()->insert($user_auth_fields)) {
            $this->json['msgid'] = 'systemerror';

            return false;
        }

        $user_data_fields['user_id']           = $uid;
        $user_data_fields['manager_agency_id'] = request::get_int('agency');

        if (!user_data_peer::instance()->insert($user_data_fields)) {
            $this->json['msgid'] = 'systemerror';

            return false;
        }

        profile_peer::instance()->set_contacts(['email' => $user_auth_fields['email']], $uid);

        return true;
    }

    private function remove()
    {
        $uid = request::get_int('uid');

        if (!user_auth_peer::instance()->get_item($uid)) {
            return false;
        }

        user_auth_peer::instance()->delete_item($uid);
        user_data_peer::instance()->delete_item($uid);

        return true;
    }

    private function move_to_archive()
    {
        $uid = request::get_int('uid');

        if (!user_auth_peer::instance()->get_item($uid)) {
            return false;
        }

        $data = [
            'id'  => $uid,
            'del' => time(),
        ];

        user_auth_peer::instance()->update($data);

        return true;
    }

    private function move_to_reserv()
    {
        $uid = request::get_int('uid');

        if (!user_auth_peer::instance()->get_item($uid)) {
            return false;
        }

        $data = [
            'id'     => $uid,
            'reserv' => time(),
        ];

        user_auth_peer::instance()->update($data);

        return true;
    }

    private function recove()
    {
        $uid = request::get_int('uid');

        if (!user_auth_peer::instance()->get_item($uid)) {
            return false;
        }

        $data = [
            'id'     => $uid,
            'del'    => 0,
            'reserv' => 0,
        ];

        user_auth_peer::instance()->update($data);

        return true;
    }

    private function send_mail()
    {
        $alias = request::get_string('alias');
        $uid   = request::get_int('uid');

        if (!in_array($alias, ['invite_nomodels'])) {
            $this->json['error'] = ['description' => 'Unknown alias'];

            return false;
        }

        if (!$profile = profile_peer::instance()->get_item($uid)) {
            $this->json['error'] = ['description' => 'Profile does not exists'];

            return false;
        }


        $tpl = email_templates_peer::get_email_template($alias);

        $email = new email($profile['email']);
        $email->isHTML();
        $email->setSender($tpl['sender_email'], $tpl['sender_name']);
        $email->setSubject($tpl['subject']);
        $email->setBody($tpl['body']);

        $email->send();

        $key = $alias.'_'.$uid;
        db_key::i()->set($key,
            $icount = (db_key::i()->exists($key)
                ? ((int) db_key::i()->get($key) + 1)
                : 1)
        );

        $this->json['invcount'] = ($icount + 1);

        return true;
    }

}

?>
