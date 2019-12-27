<?php
//load::app("modules/home/controller");

class search_index_action extends frontend_controller
{
    public function execute()
    {
        if (!session::has_credential('admin')) {
            $this->redirect('/');
        }
        load::model('agency');
        load::model('geo');
        load::model('voting');
        if (request::get('submit')) {

            $post_data = request::get_all();
//                echo "<pre>";
//                var_dump($post_data);
//                exit;
            /////////////////PREPARE FILTERS

            if (is_array($post_data['status_filter'])) {
                foreach ($post_data['status_filter'] as $status) {
                    $filters['user_data']['=:']['status'][] = $status;
                }
            }


            if ($post_data['email']) {
                $filters['user_auth']['=:']['email'] = $post_data['email'];
            }
            if ($post_data['active'] >= 0) {
                $filters['user_auth']['=:']['active'] = $post_data['active'];
            }
            if ($post_data['hidden'] >= 0) {
                $filters['user_auth']['=:']['hidden'] = $post_data['hidden'];
            }

            if ($post_data['archive'] > 0) {
                $filters['user_auth']['>:']['del'] = 0;
            } else {
                $filters['user_auth']['=:']['del'] = 0;
            }

            if ($post_data['reserv'] > 0) {
                $filters['user_auth']['>:']['reserv'] = 0;
            } else {
                $filters['user_auth']['=:']['reserv'] = 0;
            }

            if ($post_data['approve'] > 0) {
                $filters['user_auth']['=:']['approve'] = 1;
            } elseif ($post_data['approve'] == 0) {
                $filters['user_auth']['!=:']['approve'] = 1;
            }

//                if($post_data['reserv']>=0) $filters['user_auth']['!=:']['reserv'] = $post_data['reserv'];

            if ($post_data['first_name']) {
                $filters['user_data'][' ILIKE :']['first_name'] = '%'.$post_data['first_name'].'%';
            }
            if ($post_data['last_name']) {
                $filters['user_data'][' ILIKE :']['last_name'] = '%'.$post_data['last_name'].'%';
            }
            if ($post_data['country']) {
                $filters['user_data']['=:']['country'] = $post_data['country'];
            }
            if ($post_data['region']) {
                $filters['user_data']['=:']['region'] = $post_data['region'];
            }
            if ($post_data['city']) {
                $filters['user_data']['=:']['city'] = $post_data['city'];
            }
            if ($post_data['another_city'] && $post_data['city'] == '-1') {
                $filters['user_data'][' ILIKE :']['another_city'] = '%'.$post_data['another_city'].'%';
            }

            if ($post_data['smoking'] >= 0) {
                $filters['user_additional']['=:']['smoke'] = $post_data['smoking'];
            }
            if ($post_data['kids'] >= 0) {
                $filters['user_additional']['=:']['kids'] = $post_data['kids'];
            }
            if ($post_data['marital_status'] >= 0) {
                $filters['user_additional']['=:']['marital_status'] = $post_data['marital_status'];
            }

            if (request::get_int('birthday_from') > 0) {
                $filters['user_data'][' BETWEEN ']['birthday']['to'] = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y") - $post_data['birthday_from']));
            }
            if (request::get_int('birthday_to') > 0) {
                $filters['user_data'][' BETWEEN ']['birthday']['from'] = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y") - $post_data['birthday_to']));
            }

            if (request::get_int('growth_from')) {
                $filters['user_params'][' BETWEEN ']['growth']['from'] = $post_data['growth_from'];
            }
            if (request::get_int('growth_to')) {
                $filters['user_params'][' BETWEEN ']['growth']['to'] = $post_data['growth_to'];
            }

            if (request::get_int('weigth_from')) {
                $filters['user_params'][' BETWEEN ']['weigth']['from'] = $post_data['weigth_from'];
            }
            if (request::get_int('weigth_to')) {
                $filters['user_params'][' BETWEEN ']['weigth']['to'] = $post_data['weigth_to'];
            }

            if (request::get_int('breast_from')) {
                $filters['user_params'][' BETWEEN ']['breast']['from'] = $post_data['breast_from'];
            }
            if (request::get_int('breast_to')) {
                $filters['user_params'][' BETWEEN ']['breast']['to'] = $post_data['breast_to'];
            }

            if (request::get_int('waist_from')) {
                $filters['user_params'][' BETWEEN ']['waist']['from'] = $post_data['waist_from'];
            }
            if (request::get_int('waist_to')) {
                $filters['user_params'][' BETWEEN ']['waist']['to'] = $post_data['waist_to'];
            }

            if (request::get_int('hip_from')) {
                $filters['user_params'][' BETWEEN ']['hip']['from'] = $post_data['hip_from'];
            }
            if (request::get_int('hip_to')) {
                $filters['user_params'][' BETWEEN ']['hip']['to'] = $post_data['hip_to'];
            }


            if (is_array($post_data['eye_color'])) {
                foreach ($post_data['eye_color'] as $color) {
                    $filters['user_params']['=:']['eye_color'][] = $color;
                }
            }

            if (is_array($post_data['hair_color'])) {
                foreach ($post_data['hair_color'] as $color) {
                    $filters['user_params']['=:']['hair_color'][] = $color;
                }
            }

            if (is_array($post_data['hair_length'])) {
                foreach ($post_data['hair_length'] as $length) {
                    $filters['user_params']['=:']['hair_length'][] = $length;
                }
            }

            if ($post_data['foreign_agency'] >= 0) {
                $filters['user_agency']['=:']['foreign_agency'] = ($post_data['foreign_agency']) ? true : false;
            }

            if ($post_data['agency']) {
                $filters['user_agency']['=:']['agency_id'] = $post_data['agency'];
            }
            if ($post_data['another_agency'] && $post_data['agency'] == '-1') {
                $filters['user_agency'][' ILIKE :']['another_agency'] = '%'.$post_data['another_agency'].'%';
            }
            if ($post_data['contract'] >= 0) {
                $filters['user_agency']['=:']['contract'] = $post_data['contract'];
            }
            if ($post_data['contract_type'] >= 0) {
                $filters['user_agency']['=:']['contract_type'] = $post_data['contract_type'];
            }
            if ($post_data['agency_type'] >= 0) {
                $filters['user_agency']['=:']['type'] = $post_data['agency_type'];
            }

            if ($post_data['foreign_agency_name']) {
                $filters['user_agency'][' ILIKE :']['name'] = '%'.$post_data['foreign_agency_name'].'%';
            }
            if ($post_data['foreign_agency_city']) {
                $filters['user_agency'][' ILIKE :']['city'] = '%'.$post_data['foreign_agency_city'].'%';
            }
            if ($post_data['foreign_agency_type'] >= 0) {
                $filters['user_agency']['=:']['type'] = $post_data['foreign_agency_type'];
            }

            if ($post_data['work_experience']) {
                $filters['user_additional']['=:']['work_experience'] = $post_data['work_experience'];
            }
            if ($post_data['foreign_passport'] >= 0) {
                $filters['user_additional']['=:']['foreign_passport'] = $post_data['foreign_passport'];
            }
            if ($post_data['foreign_work_experience'] >= 0) {
                $filters['user_additional']['=:']['foreign_work_experience'] = $post_data['foreign_work_experience'];
            }

            ///Независимая модель ептвоюмать
            if ($post_data['independent_model'] > 0) {
                $results[] = db::get_cols("SELECT id FROM user_auth WHERE 1=1 AND del=:del AND reserv=:reserv AND id NOT IN (SELECT DISTINCT user_id FROM user_agency WHERE agency_id<>0)", ['del' => $filters['user_auth']['=:']['del'], 'reserv' => $filters['user_auth']['=:']['reserv']]);
            } elseif (!$post_data['independent_model']) {
                $results[] = db::get_cols("SELECT id FROM user_auth WHERE 1=1 AND del=:del AND reserv=:reserv AND id IN (SELECT DISTINCT user_id FROM user_agency WHERE agency_id<>0)", ['del' => $filters['user_auth']['=:']['del'], 'reserv' => $filters['user_auth']['=:']['reserv']]);
            } else {
                $results[] = db::get_cols("SELECT id FROM user_auth WHERE 1=1 AND del=:del AND reserv=:reserv", ['del' => $filters['user_auth']['=:']['del'], 'reserv' => $filters['user_auth']['=:']['reserv']]);
            }

            //Все кроме самозарегестрированных новых ептвоюмать
            $results[] = db::get_cols("SELECT id FROM user_auth WHERE 1=1 AND del=:del AND reserv=:reserv AND (registrator>0 OR approve>0)", ['del' => $filters['user_auth']['=:']['del'], 'reserv' => $filters['user_auth']['=:']['reserv']]);

            if (!empty($filters)) {
                if ($filters['user_params']) {
                    $results[] = $this->search_by_params($filters['user_params']);
                    unset($filters['user_params']);
                }

                if (!empty($filters)) {
                    foreach ($filters as $table_name => $op_data) {
                        $uid  = ($table_name === 'user_auth') ? 'id' : 'user_id';
                        $sql  = "SELECT DISTINCT ".$uid.' FROM '.$table_name." WHERE 1=1";
                        $bind = [];
                        foreach ($op_data as $operation => $rows_data) {
                            foreach ($rows_data as $row_name => $row_value) {
                                if (is_array($row_value) && $operation === ' BETWEEN ') {
                                    if (isset($row_value['from'])) {
                                        $sql                     .= ' AND '.$row_name.'>=:'.$row_name.'_from';
                                        $bind[$row_name.'_from'] = $row_value['from'];
                                    }
                                    if (isset($row_value['to'])) {
                                        $sql                   .= ' AND '.$row_name.'<=:'.$row_name.'_to';
                                        $bind[$row_name.'_to'] = $row_value['to'];
                                    }
                                } elseif (is_array($row_value)) {
                                    $tmp_sql = [];
                                    foreach ($row_value as $row_key => $row_var) {
                                        $tmp_sql[]                    = $row_name.$operation.$row_name.'_'.$row_key;
                                        $bind[$row_name.'_'.$row_key] = $row_var;
                                    }
                                    $sql .= ' AND ('.implode(' OR ', $tmp_sql).')';
                                } else {
                                    $sql             .= ' AND '.$row_name.$operation.$row_name;
                                    $bind[$row_name] = $row_value;
                                }
                            }
                        }
                        $results[] = db::get_cols($sql, $bind);

                    }
                }

            }

            if (is_array($results)) {
                $ret = $this->intersect_results($results);
            }

            if ($ret === null) {
                $ret = db::get_cols("SELECT id FROM user_auth");
            }
            load::action_helper('page', false);
            $this->count = count($ret);
            $this->users = $ret;
            $this->pages = pager_helper::get_pager($this->users, request::get_int('page'), 15);
            $this->users = $this->pages->get_list();
        }
    }


    private function search_by_params($data)
    {
        foreach ($data as $operation => $rows_data) {
            foreach ($rows_data as $row_name => $row_value) {
                $sql                    = "SELECT DISTINCT user_id FROM user_params WHERE 1=1";
                $bind                   = [];
                $sql                    .= ' AND key=:'.$row_name.'_key';
                $bind[$row_name."_key"] = $row_name;
                if (is_array($row_value) && $operation === ' BETWEEN ') {
                    if (isset($row_value['from'])) {
                        $sql                         .= ' AND value>=:'.$row_name.'_key_from';
                        $bind[$row_name.'_key_from'] = $row_value['from'];
                    }
                    if (isset($row_value['to'])) {
                        $sql                       .= ' AND value<=:'.$row_name.'_key_to';
                        $bind[$row_name.'_key_to'] = $row_value['to'];
                    }
                } elseif (is_array($row_value)) {
                    $tmp_sql = [];
                    foreach ($row_value as $row_key => $row_var) {
                        $tmp_sql[]                    = ' value '.$operation.$row_name.'_'.$row_key;
                        $bind[$row_name.'_'.$row_key] = $row_var;
                    }
                    $sql .= ' AND ('.implode(' OR ', $tmp_sql).')';
                } else {
                    $sql                      .= ' AND value'.$operation.$row_name.'_value';
                    $bind[$row_name.'_value'] = $row_value;
                }
                $results[] = db::get_cols($sql, $bind);
            }
        }

        return $this->intersect_results($results);
    }

    private function intersect_results($results)
    {
        $ret = $results[0];
        foreach ($results as $k => $v) {
            if (isset($results[$k])) {
                $ret = array_intersect($ret, $results[$k]);
            }
        }

        return $ret;
    }

}

?>
