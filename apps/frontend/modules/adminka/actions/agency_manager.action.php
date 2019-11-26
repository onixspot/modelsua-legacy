<?php

load::app('modules/adminka/controller');
load::model('agency');

class adminka_agency_manager_action extends adminka_controller
{

    public function execute()
    {
        parent::execute();

        $this->set_template('index');
        $this->adminka['frame'] = 'agency_manager';
        $this->adminka['act']   = request::get('act');
        $this->adminka['id']    = request::get_int('id');

        if (in_array($this->adminka['act'], ['add', 'edit_json', 'publicate', 'remove', 'remove_selected', 'create_page'])) {
            $this->set_renderer('ajax');
            $this->json['success'] = true;

            $this->adminka['agency_id'] = request::get('agency_id');

            $method = $this->adminka['act'];
            $this->$method();
        } else {
            $this->list = agency_peer::instance()->get_list([], [], ['name ASC']);
        }
    }

    public function add()
    {
        $rq = request::get_all();
        for ($i = 0; $i < 999; $i++) {
            $key = 'agency-text-name-'.$i;
            if (array_key_exists($key, $rq) && $rq[$key] != '') {
                if (!agency_peer::instance()->is_exists($rq[$key])) {
                    $data['name']               = $rq[$key];
                    $this->json['agency'][$key] = (boolean) agency_peer::instance()->insert($data);
                } else {
                    $this->json['agency'][$key] = false;
                }
            }
        }
    }

    public function edit_json()
    {
        $rq = request::get_all();
        for ($i = 0; $i < 999; $i++) {
            $key = 'agency-text-name-'.$i;
            if (array_key_exists($key, $rq) && $rq[$key] != '') {
                $data['id']   = request::get_int('id');
                $data['name'] = $rq[$key];
                agency_peer::instance()->update($data);
                $this->json['agency'][$key] = true;
                //				if( ! agency_peer::instance()->is_exists($rq[$key]))
                //				{
                //					$data["name"] = $rq[$key];
                //					$this->json["agency"][$key] = (boolean) agency_peer::instance()->insert($data);
                //				}
                //				else
                //					$this->json["agency"][$key] = false;
            }
        }
    }

    public function publicate()
    {
        $data = [
            'id'     => $this->adminka['agency_id'],
            'public' => request::get_int('public'),
        ];
        agency_peer::instance()->update($data);
    }

    public function remove()
    {
        $this->json['agency_id'] = $this->adminka['agency_id'];
        agency_peer::instance()->delete_item($this->adminka['agency_id']);
    }

    public function remove_selected()
    {
        $this->json['agency_id'] = $this->adminka['agency_id'];
        foreach ($this->adminka['agency_id'] as $agency_id) {
            agency_peer::instance()->delete_item($agency_id);
        }
    }

    public function create_page()
    {
        $id = request::get_int('id');
        if (!$item = agency_peer::instance()->get_item($id)) {
            return $this->json['success'] = false;
        }

        $data = [
            'id'          => $id,
            'page_active' => true,
        ];

        agency_peer::instance()->update($data);

        return $this->json['success'];
    }
}

?>
