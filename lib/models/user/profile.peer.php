<?php

load::model('user/user_auth');
load::model('user/user_data');
load::model('user/user_contacts');
load::model('user/user_agency');
load::model('user/user_params');
load::model('user/user_additional');
load::model('geo');

class profile_peer extends db_peer_postgre
{
    const MODEL_OR_CANDIDATE_TYPE = 1;

    const SUCCESSFUL  = 0;
    const NEW_FACES   = 1000;
    const PERSPECTIVE = 2000;
    const LEGENDARY   = 3000;

    const SUCCESSFUL_KEY  = 'successful';
    const NEW_FACES_KEY   = 'new_faces';
    const PERSPECTIVE_KEY = 'perspective';
    const LEGENDARY_KEY   = 'legendary';

    const STATUS_TYPES = [
        self::SUCCESSFUL  => self::SUCCESSFUL_KEY,
        self::NEW_FACES   => self::NEW_FACES_KEY,
        self::PERSPECTIVE => self::PERSPECTIVE_KEY,
        self::LEGENDARY   => self::LEGENDARY_KEY,
    ];

    public static  $params;
    public static  $additional;
    public static  $eng_knowledge;
    private static $sex;
    private static $status_values;
    private static $statements = [
        'model'              => 'в каталоге моделей ModelsUA.org',
        'association_member' => 'в каталоге моделей ModelsUA.org и вступление в Ассоциацию моделей Украины',
    ];
    /** @var array */
    private $context;

    /** @var array */
    private $statusTypeCaptionMap;

    /**
     * @param string $peer
     *
     * @return \db_peer|object|\profile_peer
     */
    public static function instance($peer = 'profile_peer')
    {
        self::set_static_params();

        return parent::instance($peer);
    }

    public static function set_static_params()
    {
        self::$eng_knowledge = [
            1 => t('вообще не знаю'),
            2 => t('читаю, понимаю, но плохо говорю'),
            3 => t('хорошо понимаю и говорю на бытовом уровне'),
            4 => t('свободно владею'),
        ];
        self::$sex           = [
            0 => t('Мужской'),
            1 => t('Женский'),
        ];
        self::$params        = [
            'eye_color'   => [
                1  => t('Карие'),
                2  => t('Голубые'),
                3  => t('Зеленые'),
                4  => t('Серо-голубые'),
                5  => t('Каре-зеленые'),
                6  => t('Серые'),
                7  => t('Серо-зеленые'),
                8  => t('Темно-карие'),
                9  => t('Зелено-голубые'),
                10 => t('Черный'),
                11 => t('Зелено-карие'),
            ],
            'hair_color'  => [
                1  => t('Светлые'),
                2  => t('Светло-русые'),
                3  => t('Русые'),
                4  => t('Темно-русые'),
                5  => t('Светло-каштановые'),
                6  => t('Каштановые'),
                7  => t('Темно-каштановые'),
                8  => t('Черные'),
                9  => t('Рыжие'),
                10 => t('Без волос'),
            ],
            'hair_length' => [
                1 => t('Очень короткие'),
                2 => t('Короткие'),
                3 => t('Средние'),
                4 => t('Длинные'),
                5 => t('Очень длинные'),
                6 => t('Без волос'),
            ],
        ];
        self::$additional    = [
            'work_experience' => [
                1  => t('Менее 1 года'),
                2  => '1 '.t('год'),
                3  => '2 '.t('года'),
                4  => '3 '.t('года'),
                5  => '4 '.t('года'),
                6  => '5 '.t('лет'),
                7  => '6 '.t('лет'),
                8  => '7 '.t('лет'),
                9  => '8 '.t('лет'),
                10 => '9 '.t('лет'),
                11 => '10 '.t('лет'),
                12 => t('Более 10 лет'),
            ],
        ];

        self::$status_values = [
//			1 => array(
//					"id" => "new_face",
//					"type" => "Новое лицо",
//					"status" => array(
//							11 => null,
//					)
//			),
            2 => [
                'id'     => 'model',
                'type'   => t('Модель'),
                'status' => [
                    21 => t('Модель'),
                    22 => t('Модель, Член Ассоциации'),
                    //							23 => "Новое лицо",
                    24 => t('Модель, Кандидат в Члены Ассоциации'),
                ],
            ],
            3 => [
                'id'     => 'professional',
                'type'   => t('Профессионал'),
                'status' => [
                    31 => t('Визажист'),
                    32 => t('Стилист'),
                    33 => t('Фотограф'),
                    34 => t('Видеооператор'),
                    35 => t('Модельер'),
                    36 => t('Постановщик'),
                    37 => t('Журналист'),
                ],
            ],
            4 => [
                'id'     => 'representative',
                'type'   => t('Представитель'),
                'status' => [
                    41 => t('Представитель бренда'),
                    42 => t('Представитель модельного агентства'),
                    43 => t('Представитель журнала'),
                ],
            ],
            5 => [
                'id'     => 'admin',
                'type'   => t('Администратор'),
                'status' => [
                    51 => t('Администратор'),
                ],
            ],
            6 => [
                'id'     => 'superadmin',
                'type'   => t('Супер администратор'),
                'status' => [
                    61 => t('Супер администратор'),
                ],
            ],
            7 => [
                'id'     => 'exmodel',
                'type'   => t('Экс-модель'),
                'status' => [
                    71 => t('Экс-модель'),
                ],
            ],
        ];

    }

    public static function get_by_security($code)
    {
        return db::get_scalar('SELECT id FROM user_auth WHERE security=:code', ['code' => $code]);
    }

    public static function get_credentials($user_data)
    {
        return [];
    }

    public static function get_name($user_data, $tpl = '&fn &ln', $lang = null)
    {
        $lang      = ($lang) ? $lang : session::get('language', 'ru');
        $condition = [
            'fn' => $user_data['first_name'.($lang == 'en' ? '_en' : '')] ? $user_data['first_name'
            .($lang == 'en' ? '_en' : '')] : $user_data['first_name'],
            'mn' => $user_data['middle_name'],
            'ln' => $user_data['last_name'.($lang == 'en' ? '_en' : '')] ? $user_data['last_name'.($lang == 'en' ? '_en' : '')] : $user_data['last_name'],
        ];

        $_data = $tpl;
        foreach ($condition as $key => $val) {
            $_data = str_replace('&'.$key, $val, $_data);
        }

        return $_data;
    }

    public static function get_location($user_data, $splitter = ' / ')
    {
        $location = '';

        if ($user_data['country'] > 0) {
            $location = geo_peer::instance()->get_country($user_data['country']);
        } else {
            return $location;
        }

        if ($user_data['region'] > 0) {
            $location .= $splitter.geo_peer::instance()->get_region($user_data['region']);
        }

        if ($user_data['city'] > 0) {
            $location .= $splitter.geo_peer::instance()->get_city($user_data['city']);
        } elseif ($user_data['city'] == -1 && $user_data['another_city'] != '') {
            $location .= $splitter.$user_data['another_city'];
        }

        return $location;
    }

    public static function get_birthday($user_birthday, $format = 'd.m.Y')
    {
        if (is_null($user_birthday)) {
            return false;
        }

        $time = strtotime($user_birthday);

        return date($format, $time);
    }

    public static function get_age($user_birthday)
    {

        $age_words = [t('лет'), t('год'), t('года')];

        if (is_null($user_birthday)) {
            return false;
        }

        list($day_now, $month_now, $year_now) = explode('.', date('d.m.Y'));
        list($day, $month, $year) = explode('.', date('d.m.Y', strtotime($user_birthday)));

        if ($month_now >= $month && $day_now >= $day) {
            $age = $year_now - $year;
        } else {
            $age = $year_now - $year - 1;
        }

        if ($age > 9) {
            $mod = fmod($age, 10);
        } else {
            $mod = $age;
        }

        $w_index = 0;
        if ($mod > 1 && $mod < 5) {
            $w_index = 2;
        } elseif ($mod == 1) {
            $w_index = 1;
        }

        if ($age > 9 && $age < 20) {
            $w_index = 0;
        }

        return $age.' '.$age_words[$w_index];
    }

    public static function get_types_list()
    {
        return self::$status_values;
    }

    public static function get_type_key($type_id)
    {
        foreach (self::$status_values as $key => $status) {
            if ($status['id'] == $type_id) {
                return $key;
            }
        }
    }

    public static function get_admin_status($type, $status)
    {
        $_type   = self::get_type($type);
        $_status = self::get_status($type, $status);

        if ($_status != '') {
            $_type .= ' / '.$_status;
        }

        return $_type;
    }

    public static function get_type($type)
    {
        return self::$status_values[$type]['type'];
    }

    public static function get_status($type, $status)
    {
        return self::$status_values[$type]['status'][$status];
    }

    public static function get_status_by_user($uid)
    {
        return db::get_scalar('SELECT status FROM user_data WHERE user_id=:uid', ['uid' => $uid]);
    }

    public static function get_type_by_user($uid)
    {
        return db::get_scalar('SELECT type FROM user_auth WHERE id=:uid', ['uid' => $uid]);
    }

    public static function get_statement($statement_key)
    {
        return self::$statements[$statement_key];
    }

    public function useContext($context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * @param $map
     *
     * @return \profile_peer
     */
    public function useStatusTypeCaptionMap($map)
    {
        $this->statusTypeCaptionMap = $map;

        return $this;
    }

    public function getStatusTypeCaption()
    {
        $type = $this->getStatusType();
        $map  = $this->statusTypeCaptionMap;

        if (!isset($map[$type])) {
            return null;
        }

        return $map[$type];
    }

    public function getStatusType()
    {
        $type = $this->context['show_on_main'];

        switch (true) {
            case $type > self::SUCCESSFUL && $type < self::NEW_FACES:
                return self::STATUS_TYPES[self::SUCCESSFUL];
                break;

            case $type >= self::NEW_FACES && $type < self::PERSPECTIVE:
                return self::STATUS_TYPES[self::NEW_FACES];
                break;

            case $type >= self::PERSPECTIVE && $type < self::LEGENDARY:
                return self::STATUS_TYPES[self::PERSPECTIVE];
                break;

            case $type >= self::LEGENDARY:
                return self::STATUS_TYPES[self::LEGENDARY];
                break;
        }
    }

    /*
     * Usage:
     *		profile_peer::instance()->is_exists(array('email' => $value))
     * or
     *		profile_peer::instance()->is_exists(array('user_id' => $value))
     *
     * return true if exists and false if not exists
     */

    public function insert($data)
    {
        if (!array_key_exists('password', $data)) {
            $data['password'] = self::generate_password();
        }

        $data['security'] = self::generate_password(16);

        $user_auth = $this->verify_structure($data, 'user_auth');

        if (!$user_id = user_auth_peer::instance()->insert($user_auth)) {
            return false;
        }

        $user_data            = $this->verify_structure($data, 'user_data');
        $user_data['user_id'] = $user_id;

        if (!user_data_peer::instance()->insert($user_data)) {
            return false;
        }

        $this->set_contacts($data, $user_id);

        $this->set_params($data, $user_id);

        return $user_id;
    }

    public static function generate_password($length = 8)
    {
        $password = '';

        for ($i = 0; $i < $length; $i++) {
            $var      = [];
            $var[]    = chr(rand(65, 90));
            $var[]    = chr(rand(97, 122));
            $var[]    = chr(rand(48, 57));
            $password .= $var[rand(0, 2)];
        }

        return $password;
    }

    private function verify_structure($data, $table = 'user_auth')
    {
        $struct = [];

        if ($table == 'user_auth') {
            $tpl = [
                'email',
                'password',
                'security',
                'active',
                'hidden',
                'credentials',
                'registrator',
                'last_invite',
                'type',
                'show_on_main',
                'approve',
                'del',
                'del_hist',
                'statement_type',
                'created_ts',
                'activated_ts',
            ];
        }

        if ($table == 'user_data') {
            $tpl = [
                'user_id',
                'first_name',
                'first_name_en',
                'middle_name',
                'last_name',
                'last_name_en',
                'birthday',
                'sex',
                'country',
                'region',
                'city',
                'another_city',
                'status',
                'smoking',
                'kids',
                'marital_status',
                'rank',
                'pid',
                'learned_about',
                'application',
                'application_approve',
                'manager_agency_id',
                'can_write',
            ];
        }

        foreach ($tpl as $key)
            if (array_key_exists($key, $data))
                switch ($key) {
                    case 'password':
                        //case "security":
                        $struct[$key] = md5($data[$key]);
                        break;

                    case 'credentials':
                        $struct[$key] = serialize($data[$key]);
                        break;

                    case 'birthday':
                        if (is_null($data[$key])) {
                            $struct[$key] = $data[$key];
                        } else {
                            $struct[$key] = date('Y-m-d', $data[$key]);
                        }
                        break;

                    default:
                        $struct[$key] = $data[$key];
                        break;
                }

        return $struct;
    }

    /* STATIC FUNCTIONS */

    public function set_contacts($data, $user_id)
    {
        $struct = [
            'email',
            'phone',
            'website',
            'skype',
            'icq',
            'facebook',
            'napodiume',
            'vkontakte',
            'instagram',
            'twitter',
            'wiki',
            'modelscom',
        ];

        $user_contacts_list = user_contacts_peer::instance()->get_list(['user_id' => $user_id]);

        $keys     = [];
        $contacts = [];
        foreach ($user_contacts_list as $user_contact_id) {
            $user_contact                   = user_contacts_peer::instance()->get_item($user_contact_id);
            $keys[]                         = $user_contact['key'];
            $contacts[$user_contact['key']] = $user_contact['id'];
        }

        foreach ($struct as $key) {
            if (!array_key_exists($key, $data)) {
                continue;
            }

            $_data = [
                'user_id' => $user_id,
                'key'     => $key,
            ];

            $value = $data[$key];
            if (is_array($data[$key])) {
                $value           = $data[$key]['value'];
                $_data['access'] = $data[$key]['access'];
            }

            $_data['value'] = $value;

            if (in_array($key, $keys)) {
                $_data['id'] = $contacts[$key];
                user_contacts_peer::instance()->update($_data);
            } else {
                user_contacts_peer::instance()->insert($_data);
            }
        }
    }

    public function set_params($data, $user_id)
    {
        $struct = [
            'growth',
            'weigth',
            'breast',
            'waist',
            'hip',
            'eye_color',
            'hair_color',
            'hair_length',
        ];

        $user_params_list = user_params_peer::instance()->get_list(['user_id' => $user_id]);

        $keys   = [];
        $params = [];
        foreach ($user_params_list as $user_param_id) {
            $user_param                 = user_params_peer::instance()->get_item($user_param_id);
            $keys[]                     = $user_param['key'];
            $params[$user_param['key']] = $user_param['id'];
        }

        foreach ($struct as $key) {
            if (!array_key_exists($key, $data)) {
                continue;
            }

            $_data = [
                'user_id' => $user_id,
                'key'     => $key,
                'value'   => $data[$key],
            ];
            if (in_array($key, $keys)) {
                $_data['id'] = $params[$key];
                user_params_peer::instance()->update($_data);
            } else {
                user_params_peer::instance()->insert($_data);
            }
        }
    }

    public function update($data, $user_id)
    {
        $user_auth = $this->verify_structure($data, 'user_auth');

        $user_auth['id'] = $user_id;

        foreach ($user_auth as $key => $val) {
            if (in_array($key, ['email', 'password', 'security', 'credentials', 'type'])) {
                unset($user_auth[$key]);
            }
        }

        if (count($user_auth) > 1) {
            user_auth_peer::instance()->update($user_auth);
        }

        $user_data = $this->verify_structure($data, 'user_data');

        $user_data['user_id'] = $user_id;
        if (count($user_data) > 1) {
            user_data_peer::instance()->update($user_data);
        }

        $this->set_contacts($data, $user_id);

        return $user_data;
    }

    public function change_password($user_id, $password)
    {
        $data = [
            'id'       => $user_id,
            'password' => md5($password),
        ];
        user_auth_peer::instance()->update($data);
    }

    public function delete_item($user_id)
    {
        user_auth_peer::instance()->delete_item($user_id);
        user_data_peer::instance()->delete_item($user_id);

        return true;
    }

    public function get_item($user_id)
    {
        if (!$user_auth = user_auth_peer::instance()->get_item($user_id)) {
            $user_auth = [];
        }

        if (!$user_data = user_data_peer::instance()->get_item($user_id)) {
            $user_data = [];
        }

        $data = array_merge($user_auth, $user_data);

        foreach ($data as $key => $val) {
            if ($key === 'id') {
                unset($data[$key]);
            }
        }

        $data['crop'] = unserialize($data['ph_crop']);
        if (!is_array($data['crop'])) {
            $data['crop'] = [];
        }

        return $data;
    }

    public function get_list($where = [], $bind = [], $order = [], $limit = '', $cache_key = null)
    {
        if (count($order) == 0) {
            $order = ['id DESC'];
        }

        return user_auth_peer::instance()->get_list($where, $bind, $order, $limit, $cache_key);
    }

    public function get_contacts($user_id)
    {
        $user_contacts_list = user_contacts_peer::instance()->get_list(['user_id' => $user_id]);

        $user_contacts = [];
        foreach ($user_contacts_list as $user_contact_id) {
            $user_contact                            = user_contacts_peer::instance()->get_item($user_contact_id);
            $user_contacts[$user_contact['key']]     = $user_contact['value'];
            $user_contacts['_'.$user_contact['key']] = $user_contact;
        }

        return $user_contacts;
    }

    public function get_params($user_id)
    {
        $user_params_list = user_params_peer::instance()->get_list(['user_id' => $user_id]);

        $user_params = [];
        foreach ($user_params_list as $user_param_id) {
            $user_param                      = user_params_peer::instance()->get_item($user_param_id);
            $user_params[$user_param['key']] = $user_param['value'];
        }

        return $user_params;
    }

    public function set_agency($data, $user_id)
    {
        $data['user_id'] = $user_id;
        user_agency_peer::instance()->insert($data);
    }

    public function clear_agency($user_id)
    {
        $agency_list = user_agency_peer::instance()->get_list(['user_id' => $user_id]);
        foreach ($agency_list as $agency_id) {
            user_agency_peer::instance()->delete_item($agency_id);
        }
    }

    public function get_last_del_hist($user_id)
    {
        if (!$user_auth = user_auth_peer::instance()->get_item($user_id)) {
            return [];
        }

        $user_auth['del_hist'] = unserialize($user_auth['del_hist']);

        return $user_auth['del_hist'][count($user_auth['del_hist']) - 1];
    }

    public function del_hist_push($data)
    {
        if (!$user_auth = user_auth_peer::instance()->get_item($data['user_id'])) {
            return false;
        }

        $user_auth['del_hist'] = unserialize($user_auth['del_hist']);
        if (!is_array($user_auth['del_hist'])) {
            $user_auth['del_hist'] = [];
        }

        $user_auth['del_hist'][] = [
            'user_id'  => $data['user_id'],
            'act'      => $data['act'],
            'operator' => session::get_user_id(),
            'time'     => time(),
        ];

        $_data = [
            'id'       => $data['user_id'],
            'del_hist' => serialize($user_auth['del_hist']),
        ];

        user_auth_peer::instance()->update($_data);

        return true;
    }

    public function is_exists($condition)
    {
        $result = user_auth_peer::instance()->get_list($condition);

        return (boolean) $result;
    }

    public function is_active($user_id)
    {

    }

    public function is_hidden($user_id)
    {

    }

    public function get_params_list()
    {
        return self::$params;
    }


    /* PRIVATE FUNCTIONS */

    public function get_additional_list()
    {
        return self::$additional;
    }
}

?>
