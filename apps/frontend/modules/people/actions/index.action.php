<?php

load::app('modules/people/controller');

class people_index_action extends people_controller
{

    public function execute()
    {
        parent::execute();

        $act = request::get('act');
        if (in_array($act, ['set_limit', 'set_rank'])) {
            $this->set_renderer('ajax');

            return $this->json['success'] = $this->$act();
        }

        $this->status = request::get('status');
        switch ($this->status) {
            case 'new-face':
                $sqladd = sprintf(' AND show_on_main >= %s AND show_on_main < %s', user_auth_peer::NEW_FACES, user_auth_peer::PERSPECTIVE);
                break;

            case 'perspective':
                $sqladd = sprintf(' AND show_on_main >= %s AND show_on_main < %s', user_auth_peer::PERSPECTIVE, user_auth_peer::LEGENDARY);
                break;

            case 'successful':
                $sqladd = sprintf(' AND show_on_main > %s AND show_on_main < %s', user_auth_peer::SUCCESSFUL, user_auth_peer::NEW_FACES);
                break;

            case 'legendary':
                $sqladd = sprintf(' AND show_on_main >= %s', user_auth_peer::LEGENDARY);
                break;

            default:
                $sqladd = '';
                break;
        }


        $this->filter = request::get('filter');
        if (!$this->filter) {
            $this->filter = 'model';
        }

        $this->type_key = profile_peer::get_type_key($this->filter);
        $sql            = 'SELECT id FROM user_auth WHERE type=:type AND hidden=:hidden AND del=:del AND reserv=:reserv';
        $coditional     = [
            'type'   => $this->type_key,
            //				'active' => true,
            'hidden' => false,
            'del'    => 0,
            'reserv' => 0,
        ];

        if (session::has_credential('admin')) {
            $sql        = 'SELECT id FROM user_auth WHERE type=:type AND del=:del AND hidden=:hidden AND reserv=:reserv';
            $coditional = [
                'type'   => $this->type_key,
                'hidden' => 0,
                'del'    => 0,
                'reserv' => 0,
            ];
        }

        if ($this->status === 'modelscom') {
            $sql        = <<<HEREDOC
select ua.id from user_auth as ua
    left join user_contacts as uc on uc.user_id = ua.id
where uc.key = 'modelscom'
  and uc.value != ''
  and ua.type = :type
  and ua.del = :del
  and ua.hidden = :hidden
  and ua.reserv = :reserv;
HEREDOC;
            $coditional = [
                'type'   => $this->type_key,
                'hidden' => 0,
                'del'    => 0,
                'reserv' => 0,
            ];

        }

        $byMilestone = $this->getByMilestone(request::get_int('milestone', null));

        if ($byMilestone !== null) {
            $ua_list = $byMilestone;
        } else {
            $ua_list = db::get_cols($sql.$sqladd, $coditional);
        }

        $ud_list = user_data_peer::instance()->get_list([], [], ['rank ASC']);

        $hold = session::get('hold_people', []);

        $this->hold_people = $hold;

        $this->list = [];
        foreach ($ud_list as $ud_item) {
            if (in_array($ud_item, $ua_list) && !in_array($ud_item, $hold)) {
                $this->list[] = $ud_item;
            }
        }

        $page        = request::get('page');
        $this->limit = 24;

        $this->paginator = PaginatorFactory::create($this->list);

        $this->pager         = pager_helper::get_pager($this->list, $page, $this->limit);
        $this->count_members = $this->pager->get_total();
        $this->count_pages   = $this->pager->get_pages();
        $this->list          = $this->pager->get_list();

    }

    private function getByMilestone($milestone = null)
    {
        $sql = <<<SQL
select ua.id
from user_auth as ua
where ua.del = :del
  and ua.hidden = :hidden
  and ua.reserv = :reserv
  and ua.milestone = :milestone;
SQL;

        if ($milestone !== null) {
            return db::get_cols($sql, [
                'del'       => 0,
                'hidden'    => false,
                'reserv'    => 0,
                'milestone' => $milestone,
            ]);
        }

        return null;
    }

    private function set_limit()
    {
        $limit = request::get_int('limit');
        session::set('people.limit', $limit);

        return $limit;
    }

    private function set_rank()
    {
        if (!session::has_credential('admin')) {
            return false;
        }

        $data = request::get_array('data');
        $hold = request::get_array('hold');

        session::set('hold_people', $hold);

        foreach ($data as $_data) {
            user_data_peer::instance()->update($_data);
            //			$this->json['data'][] = $_data;
        }

        return true;
    }
}

?>
