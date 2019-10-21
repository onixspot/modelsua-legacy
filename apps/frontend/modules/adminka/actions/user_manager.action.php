<?php

load::app('modules/adminka/controller');
load::model('user/user_invitation_message');


class adminka_user_manager_action extends adminka_controller
{

    public function execute()
    {
        request::get_all();
        parent::execute();
        $this->set_template('index');
        $this->adminka['frame'] = 'user_manager';
        $this->adminka['act']   = request::get('act');
        $this->user_id          = request::get('user_id');

        $act = request::get_string('act');
        if (in_array($act, ['in_arhive', 'in_reserv', 'refuse', 'can_write'])) {
            $this->set_renderer('ajax');

            return $this->json['success'] = $this->$act();
        }

        if (!in_array($this->adminka['act'], ['', 'edit', 'invitation_messages'])) {
            $this->set_renderer('ajax');
            $this->json['success'] = true;
        }

        if ($this->adminka['act'] === 'statement') {
            return $this->json['success'] = $this->statement();
        }

        if ($this->adminka['act'] === 'modify') {
            $this->modify();
        } elseif ($this->adminka['act'] === 'add') {
            $this->add();
        } elseif ($this->adminka['act'] === 'change_place') {
            $this->change_place($this->user_id, request::get_int('direct'));
        } elseif ($this->adminka['act'] === 'change_rank') {
            $this->change_rank($this->user_id, request::get_int('rank'));
        } elseif ($this->adminka['act'] === 'archive') {
            $this->archive(time());
        } elseif ($this->adminka['act'] === 'restore') {
            $this->archive(0);
        } elseif ($this->adminka['act'] === 'remove') {
            $this->remove();
        } elseif ($this->adminka['act'] === 'approve') {
            $this->approve(1);
        } elseif ($this->adminka['act'] === 'send_invitation') {
            $this->send_invitation('invite_user');
        } elseif ($this->adminka['act'] === 'send_invitation_final') {
            $this->send_invitation('invite_user');
        } elseif ($this->adminka['act'] === 'invite_in_work_model') {
            $this->send_invitation('invite_in_work_model');
        } elseif ($this->adminka['act'] === 'change_limit') {
            $this->change_limit(request::get_int('limit'));
        } elseif ($this->adminka['act'] === 'approvefinal') {
            $this->approve(2);
            $this->send_invitation('registration_approve');
        } elseif ($this->adminka['act'] === 'status_change') {
            $this->change_status(request::get_int('status'));
        } elseif ($this->adminka['act'] === 'invitation_messages') {
            $modelId = request::get_int('model_id');
            $this->adminka = array_merge($this->adminka, [
                'model_id' => $modelId,
                'messages' => user_invitation_message_peer::instance()->get_list(['user_id' => $modelId]),
            ]);
        } else {
            $this->request = request::get_all();
            $this->list    = $this->get_list();
            $this->results = count($this->list);
            $this->pages   = ($this->results) ? (int) ceil($this->results / $this->get_limit()) : 0;
            $page          = request::get('page');
            $this->pager   = pager_helper::get_pager($this->list, $page, $this->get_limit());
            $this->list    = $this->pager->get_list();
        }
    }

    public function archive($val)
    {
        $user_auth = user_auth_peer::instance()->get_item($this->user_id);
        if ($user_auth) {
            $user_auth['del']    = $val;
            $user_auth['reserv'] = 0;
            user_auth_peer::instance()->update($user_auth);
            profile_peer::instance()->del_hist_push(
                [
                    'user_id' => $user_auth['id'],
                    'act'     => $val > 0 ? 'in_archive' : 'restore',
                ]
            );
            $this->json = ['success' => 1, 'user_id' => $this->user_id];
        } else {
            $this->json = ['success' => 0];
        }
    }

    public function change_limit($limit)
    {
        $key = 'users_manage_limit_'.session::get_user_id();
        db_key::i()->set($key, $limit);
        $this->json = ['success' => 1];

    }

    public function change_status($status)
    {

        $type = (int) floor($status / 10);

        $user_auth = user_auth_peer::instance()->get_item($this->user_id);
        $user_data = user_data_peer::instance()->get_item($this->user_id);

        $user_auth['type']   = $type;
        $user_data['status'] = $status;
        if ($user_data['rank'] == 0 && $status > 20 && $status < 30) {
            $max               = db::get_scalar('SELECT MAX(rank) FROM user_data');
            $user_data['rank'] = ($max + 1);
        } elseif (!$status) {
            $user_data['rank'] = 0;
        }

        if ($approve = request::get_int('application_approve')) {
            $this->send_invitation('application_approve');
            $user_data['application_approve'] = $approve;
        }

        user_auth_peer::instance()->update($user_auth);
        user_data_peer::instance()->update($user_data);

        $this->json = ['success' => 1, 'user_id' => $this->user_id, 'status' => $status];
    }

    public function refuse()
    {
        $user_data = user_data_peer::instance()->get_item($this->user_id);
        if ($user_data) {
            $user_data['application_approve'] = 2;
            user_data_peer::instance()->update($user_data);

            return true;
        } else {
            return false;
        }
    }

    public function approve($type = 0)
    {
        $uid = request::get_int('uid');

        $this->user_id = $uid;

        $user_auth = user_auth_peer::instance()->get_item($uid);
        if ($user_auth && $type) {
            $password = profile_peer::generate_password();

            $user_auth['approve']  = $type;
            $user_auth['type']     = 2;
            $user_auth['password'] = md5($password);
            if ($type == 2) {
                $user_auth['hidden'] = false;
            }

            if ($type == 1) {
                $tpl = email_templates_peer::get_email_template('in_work');

                $profile = profile_peer::instance()->get_item($uid);

                $replace = [
                    profile_peer::get_name($profile),
                    'регистрацию в каталоге моделей ModelsUA.org',
                    $user_auth['email'],
                    $password,
                ];

                $_body = str_replace(['{$name}', '{$statement}', '{$email}', '{$password}'], $replace, $tpl['body']);

                $email = new email($user_auth['email']);
                $email->isHTML();
                $email->setSender($tpl['sender_email'], $tpl['sender_name']);
                $email->setSubject($tpl['subject']);
                $email->setBody($_body);
                $email->send();

            }

            user_auth_peer::instance()->update($user_auth);
            if ($type == 2) {
                $status     = 21;
                $statements = unserialize($user_auth['statement_type']);
                if (!is_array($statements)) {
                    $statements = [];
                }

                foreach ($statements as $type) {
                    if ($type['key'] === 'association_member' && $type['status'] > 0) {
                        $status = 22;
                    }
                }
                //				$user_data = user_data_peer::instance()->get_list(array('user_id' => $this->user_id));
                user_data_peer::instance()->update([
                    'user_id' => $uid,
                    'status'  => $status,
                ]);
            }
            $this->json = ['success' => 1, 'user_id' => $uid, 'status' => $status, 'user_data' => $user_data];
        } else {
            $this->json = ['success' => 0, 'reason' => 'Input data error...'];
        }
    }

    public function change_rank($user_id = 0, $rank = 0)
    {

        $user_data = db::get_row('SELECT * FROM user_data WHERE user_id=:uid', ['uid' => $user_id]);

        if (!$user_data || !$rank) {
            $this->json = ['success' => 0, 'reason' => 'input data error', 'debug' => ['id' => $user_id, 'rank' => $rank]];

            return false;
        }

        $exists_rank = db::get_scalar('SELECT user_id FROM user_data WHERE rank=:rank', ['rank' => $rank]);

        if ($rank < $user_data['rank']) {
            $sql  = 'UPDATE user_data SET rank=rank+1 WHERE rank>=:rank AND rank<:rank2';
            $bind = ['rank' => $rank, 'rank2' => $user_data['rank']];
        } else {
            $sql  = 'UPDATE user_data SET rank=rank-1 WHERE rank>:rank AND rank<=:rank2';
            $bind = ['rank2' => $rank, 'rank' => $user_data['rank']];
        }

        if ($exists_rank) {
            db::exec($sql, $bind);
        }

        db::exec('UPDATE user_data SET rank=:rank WHERE user_id=:uid', ['rank' => $rank, 'uid' => $user_id]);
        $this->json = ['success' => 1];

    }

    public function change_place($id = 0, $direct = 0, $table_name = 'user_data', $pk = 'user_id', $field = 'rank')
    {

        $iname = $table_name.'_peer';
        $var   = user_data_peer::instance()->get_item($id);

        if (in_array($direct, ['1', '2']) && $var) {

            $sql = 'SELECT '.$pk.' FROM '.$table_name.' WHERE '.$field.' IN (SELECT '.(($direct == 1) ? 'MIN' : 'MAX').'('.$field.') FROM '
                .$table_name.' WHERE '.$field.(($direct == 1) ? '>' : '<').':'.$field.')';
            $uid = db::get_scalar($sql, [$field => $var[$field]]);

            if ($uid) {
                $user_change = user_data_peer::instance()->get_item($uid);

                $tmp                 = $user_change[$field];
                $user_change[$field] = $var[$field];
                $var[$field]         = $tmp;

                user_data_peer::instance()->update($var);
                user_data_peer::instance()->update($user_change);

                $this->json = ['success' => 1, 'data' => $var];
            } else {
                $this->json = ['success' => 0, 'reason' => 'Невірно вибраний напрямок'];
            }
        } else {
            $this->json = ['success' => 0, 'reason' => 'Не коректні вхідні данні'];
        }
    }

    public function get_list()
    {
        $filter = request::get('filter');
        $search = request::get_string('q');

        $where = '1=1';

        $this->filter = explode('-', $filter);

        if ($this->filter[0] === 'membership') {
            $where .= 'AND d.application>0';
        }

        if ($this->filter[0] === 'byadmin') {
            $where .= 'AND a.registrator > 0 ';
        }

        if ($this->filter[0] === 'byself') {
            $where .= ' AND a.registrator = 0 ';
        }

        if ($this->filter[0] === 'del') {
            $where .= ' AND a.del> 0 ';
        } else {
            $where .= ' AND a.del= 0 ';
        }

        if ($this->filter[0] === 'reserv') {
            $where .= ' AND a.reserv > 0 ';
        } else {
            $where .= ' AND a.reserv = 0 ';
        }

        if ($this->filter[1] === 'noemail') {
            $where .= "AND (a.email = '' OR a.email IS NULL)";
        } elseif ($this->filter[1] === 'new') {
            $where .= 'AND a.approve = 0';
        } elseif ($this->filter[1] === 'confirm') {
            $where .= 'AND a.approve = 1';
        } elseif ($this->filter[1] === 'confirmed') {
            $where .= 'AND a.approve = 2';
        } elseif ($this->filter[1] === 'invited') {
            $where .= 'AND a.last_invite > 0 AND a.active=false';
        } elseif ($this->filter[1] === 'applications') {
            $where .= ' AND d.application>0 AND d.application_approve=0';
        } elseif ($this->filter[1] === 'candidates') {
            $where .= ' AND d.application>0 AND d.application_approve=1 AND d.status=24';
        } elseif ($this->filter[1] === 'members') {
            $where .= ' AND d.application>0 AND d.application_approve=1 AND d.status=22';
        } elseif ($this->filter[1] === 'refuse') {
            $where .= ' AND d.application>0 AND d.application_approve=2';
        }

        $active = request::get_string('active');
        if (in_array($active, ['true', 'false'])) {
            $where .= ' AND a.active = '.$active;
        }

        $hidden = request::get_string('hidden');
        if (in_array($hidden, ['true', 'false'])) {
            $where .= ' AND a.hidden = '.$hidden;
        }

        if ($search) {
            $where .= " AND (d.first_name ILIKE '%".$search."%' OR d.last_name ILIKE '%".$search."%')";
        }

        $status = request::get_int('status');
        if ($status) {
            $where .= ' AND d.status='.$status;
        }

        $order = in_array($filter[0], ['byself', 'byadmin']) ? 'a.id DESC'
            : ((request::get('status') == 21 && request::get('active') === 'true') ? ' a.activated_ts DESC' : 'd.rank ASC');

        if (request::get('filter') === 'del') {
            $order = 'a.del DESC';
        }

        if (request::get('filter') === 'reserv') {
            $order = 'a.reserv DESC';
        }
        if ($filter[0] = 'membership') {
            $order = ' a.id DESC';
        }
        $sql = 'SELECT a.id FROM user_auth a JOIN user_data d ON a.id=d.user_id WHERE '.$where.' ORDER BY '.$order;
        //		var_dump($sql);
        //		exit;
        $list = db::get_cols($sql);

        return $list;
    }

    public function add()
    {
        $data = [
            'first_name'  => request::get('first_name'),
            'last_name'   => request::get('last_name'),
            'email'       => request::get('email'),
            'birthday'    => null,
            'hidden'      => request::get_bool('hidden') ? 1 : 0,
            'type'        => 2,
            'registrator' => session::get_user_id(),
        ];

        if (!$user_id = profile_peer::instance()->insert($data)) {
            $this->json['success'] = false;

            return false;
        }

        $this->json['user_id'] = $user_id;
    }

    public function modify()
    {
        $user_id = request::get_int('user_id');
        $data    = [];

        if (request::get('ident')) {
            $data = [
                'first_name' => request::get('first_name'),
                'last_name'  => request::get('last_name'),
                'auth.email' => request::get('email'),
                'hidden'     => request::get_bool('hidden') ? 1 : 0,
            ];
        }

        if (request::get_int('active', -1) != -1) {
            $data['active'] = request::get_bool('active') ? 1 : 0;
        }

        if (request::get_int('hidden', -1) != -1) {
            $data['hidden'] = request::get_bool('hidden') ? 1 : 0;
        }

        if (!$this->json['data'] = profile_peer::instance()->update($data, $user_id)) {
            $this->json['success'] = false;
        }
    }

    public function remove()
    {
        $this->json['user_id']  = request::get_int('user_id');
        $this->json['success']  = true;
        $this->json['redirect'] = '/';
        db::exec('DELETE FROM messages WHERE sender = '.request::get_int('user_id').' OR receiver = '.request::get_int('user_id'));
        db::exec('DELETE FROM user_additional WHERE user_id = '.request::get_int('user_id'));
        db::exec('DELETE FROM user_agency WHERE user_id = '.request::get_int('user_id'));
        db::exec('DELETE FROM user_contacts WHERE user_id = '.request::get_int('user_id'));
        db::exec('DELETE FROM user_data WHERE user_id = '.request::get_int('user_id'));
        db::exec('DELETE FROM user_auth WHERE id = '.request::get_int('user_id'));
        db::exec('DELETE FROM user_params WHERE user_id = '.request::get_int('user_id'));
        db::exec('DELETE FROM user_photos WHERE user_id = '.request::get_int('user_id'));
        db::exec('DELETE FROM voting WHERE user_id = '.request::get_int('user_id'));

    }

    public function send_invitation($type)
    {
        $this->json['success'] = true;

        $password = profile_peer::generate_password();

        $profile = user_auth_peer::instance()->get_item($this->user_id);

        $_email = $profile['email'];

        $profile['last_invite'] = time();
        $profile['password']    = md5($password);
        user_auth_peer::instance()->update($profile);

        $profile = profile_peer::instance()->get_item($this->user_id);

        switch ($type) {
            case 'application_approve':
                $key        = 'application_approve_'.$this->user_id;
                $email_data = email_templates_peer::get_email_template('app_approve');
                $_body      = $email_data['body'];
                break;
            case 'invite_user':
                $key        = 'invitations_byadmin_'.$this->user_id;
                $email_data = email_templates_peer::get_email_template('invite');
                $replace    = [
                    profile_peer::get_name($profile),
                    $_email,
                    $password,
                ];
                $_body      = str_replace(['{$name}', '{$email}', '{$password}'], $replace, $email_data['body']);
                break;
            case 'registration_approve':
                $key        = 'invitations_registred_'.$this->user_id;
                $email_data = email_templates_peer::get_email_template('activation');

                $replace = [
                    profile_peer::get_name($profile),
                    'регистрацию в каталоге моделей ModelsUA.org',
                    $_email,
                    $password,
                ];

                $_body = stripcslashes(str_replace(['{$name}', '{$statement}', '{$email}', '{$password}'], $replace, $email_data['body']));
                break;
            case 'invite_in_work_model':

                $key = 'invite_in_work_model_'.$this->user_id;
                $tpl = email_templates_peer::get_email_template('in_work');

                $profile = profile_peer::instance()->get_item($uid);

                $replace = [
                    profile_peer::get_name($profile),
                    'регистрацию в каталоге моделей ModelsUA.org',
                    $profile['email'],
                    $password,
                ];

                $_body = str_replace(['{$name}', '{$statement}', '{$email}', '{$password}'], $replace, $tpl['body']);

                break;
            default:
                return false;
                break;
        }

        $messageId = user_invitation_message_peer::instance()->insert([
            'user_id' => $this->user_id,
            'title'   => $email_data['subject'],
            'message' => $_body,
        ]);

        $email = new email($profile['email']);
        $email->isHTML();
        $email->setSender($email_data['sender_email'], $email_data['sender_name']);
        $email->setSubject($email_data['subject']);
        $email->setBody($_body);
        $email->send();

        user_invitation_message_peer::instance()->update([
            'id' => $messageId,
            'sent_at' => date('Y-m-d H:i:s')
        ]);

        if (db_key::i()->exists($key)) {
            $icount = (int) db_key::i()->get($key);
            db_key::i()->set($key, ($icount + 1));
        } else {
            $icount = 0;
            db_key::i()->set($key, ($icount + 1));
        }
        $this->json['inv_count'] = db::get_scalar('select count(id) from user_invitation_messages where user_id = :user_id', ['user_id' => $this->user_id]);
        $this->json['key']       = ($key);

    }

    private function can_write()
    {
        user_data_peer::instance()->update(['user_id' => $this->user_id, 'can_write' => request::get_int('can_write')]);

        return true;
    }

    private function get_limit()
    {
        return (db_key::i()->exists('users_manage_limit_'.session::get_user_id())) ? db_key::i()->get('users_manage_limit_'.session::get_user_id())
            : 25;
    }

    private function statement()
    {
        $uid = request::get_int('uid');
        if (!$user_auth = user_auth_peer::instance()->get_item($uid)) {
            return false;
        }

        $st = unserialize($user_auth['statement_type']);
        if (!is_array($st)) {
            return false;
        }

        $key = request::get_string('key');
        for ($i = 0; $i < count($st); $i++) {
            if ($st[$i]['key'] == $key) {
                $st[$i]['status'] = request::get_string('act_type') !== 'approve' ? -1 : 1;
            }
        }

        $data = [
            'id'             => $uid,
            'statement_type' => serialize($st),
        ];
        user_auth_peer::instance()->update($data);

        $this->json['act'] = request::get_string('act_type');

        return true;
    }

    private function in_arhive()
    {
        $uid = request::get_int('uid');

        $profile = profile_peer::instance()->get_item($uid);

        $statements = unserialize($profile['statement_type']);

        if (!is_array($statements)) {
            $statements = [];
        }

        foreach ($statements as $key => $statement) {
            $statements[$key]['status'] = -1;
        }

        $data = [
            'id'             => $uid,
            'del'            => time(),
            'statement_type' => serialize($statements),
        ];

        profile_peer::instance()->del_hist_push(
            [
                'user_id' => $uid,
                'act'     => 'in_archive',
            ]
        );

        user_auth_peer::instance()->update($data);

        $email_data = email_templates_peer::get_email_template('reject');

        $replace = [
            profile_peer::get_name($profile),
            'регистрацию '.profile_peer::get_statement('model'),
        ];

        $_body = str_replace(['{$name}', '{$statement}'], $replace, $email_data['body']);

        $email = new email($profile['email'], $email_data['subject'], $_body);
        $email->isHTML();
        $email->send();

        $this->json['act'] = 'in_archive';
        $this->json['uid'] = $uid;

        return true;
    }

    private function in_reserv()
    {
        $uid = request::get_int('uid');

        $profile = profile_peer::instance()->get_item($uid);

        $statements = unserialize($profile['statement_type']);
        foreach ($statements as $key => $statement) {
            $statements[$key]['status'] = -1;
        }

        $data = [
            'id'             => $uid,
            'reserv'         => time(),
            'statement_type' => serialize($statements),
        ];

        user_auth_peer::instance()->update($data);

        $email_data = email_templates_peer::get_email_template('reject');

        $replace = [
            profile_peer::get_name($profile),
            'регистрацию '.profile_peer::get_statement('model'),
        ];

        $_body = str_replace(['{$name}', '{$statement}'], $replace, $email_data['body']);

        $email = new email($profile['email'], $email_data['subject'], $_body);
        $email->isHTML();
        $email->send();

        $this->json['act'] = 'in_reserv';
        $this->json['uid'] = $uid;

        return true;
    }
}

?>
