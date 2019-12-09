<?php

load::app('modules/profile/controller');

/**
 * @property int user_id
 * @property array json
 * @property int|mixed|null card_user
 * @property mixed preview
 * @property mixed card_profile
 */
class profile_edit_action extends profile_controller
{
    public function execute()
    {
        parent::execute();

        load::model('user/user_photos');

        $this->card_user = (session::has_credential('admin') && request::get_int('id'))
            ? request::get_int('id')
            : session::get_user_id();
        $this->card_profile = profile_peer::instance()->get_item($this->card_user);
        $this->preview = db::get_row(
            'SELECT * FROM user_photos WHERE user_id=:uid AND type=:type AND name=:name',
            [
                'uid' => $this->card_user,
                'type' => user_photos_peer::TYPE_CARD_PREVIEW,
                'name' => 'ru',
            ]
        );

        if (session::get_user_id() != $this->user_id && !session::has_credential('admin')) {
            $this->redirect('/profile?id='.$this->user_id);
        }

        $group = request::get('group');

        if (in_array(
            $group,
            [
                'general',
                'locality',
                'contacts',
                'agency',
                'params',
                'additional',
                'options',
                'photo',
                'ph_crop',
            ]
        )) {
            $this->set_renderer('ajax');
            $this->json = ['success' => true];
            $this->$group();
        }
    }

    public function agency()
    {
        $_data = [];

        $contract = 0;
        if (request::get_bool('agency_contract-yes')) {
            $contract = 1;
        } elseif (request::get_bool('agency_contract-no')) {
            $contract = -1;
        }

        $contract_type = 0;
        if (request::get_bool('agency_contract_type-yes')) {
            $contract_type = 1;
        } elseif (request::get_bool('agency_contract_type-no')) {
            $contract_type = -1;
        }

        $_data[] = [
            'user_id' => $this->user_id,
            'agency_id' => request::get_int('agency'),
            'name' => request::get('another_agency'),
            'contract' => $contract,
            'contract_type' => $contract_type,
            'type' => request::get_int('agency-type') ? 1 : 0,
        ];

        for ($i = 1; $i <= 99; $i++) {
            if (request::get_int('foreign_agency_name-'.$i, -1) == -1) {
                break;
            }

            $_data[] = [
                'user_id' => $this->user_id,
                'agency_id' => -1,
                'name' => request::get('foreign_agency_name-'.$i),
                'city' => request::get('foreign_agency_city-'.$i),
                'foreign_agency' => 1,
                'type' => request::get_bool('foreign_agency_type-'.$i) ? 1 : 0,
            ];
        }

        profile_peer::instance()->clear_agency($this->user_id);
        foreach ($_data as $data) {
            profile_peer::instance()->set_agency($data, $this->user_id);
        }
    }

    public function params()
    {
        $data = [
            'user_id' => $this->user_id,
            'growth' => request::get('growth'),
            'weigth' => request::get('weigth'),
            'breast' => request::get('breast'),
            'waist' => request::get('waist'),
            'hip' => request::get('hip'),
            'eye_color' => request::get('eye_color'),
            'hair_color' => request::get('hair_color'),
            'hair_length' => request::get('hair_length'),
        ];

        profile_peer::instance()->set_params($data, $data['user_id']);
    }

    public function additional()
    {
        $data = [
            'user_id' => $this->user_id,
            'work_experience' => request::get('work_experience'),
            'visa' => request::get('visa'),
            //				"foreign_work_experience" => request::get_bool("foreign_work_experience-yes") ? 1 : 0,
            'foreign_work_experience_desc' => request::get('foreign_work_experience_desc'),
            //				"marital_status" => request::get_bool("marital_status-yes") ? 1 : 0,
            //				"kids" => request::get_bool("kids-yes") ? 1 : 0,
            'current_work_place_name' => request::get('current_work_place_name'),
            'current_work_place_appointment' => request::get('current_work_place_appointment'),
            //				"smoke" => request::get_bool("smoke-yes") ? 1 : 0,
            'about_self' => request::get_string('about_self'),
            'eng_knowledge' => request::get_int('eng_knowledge'),
        ];

        $data['foreign_work_experience'] = -1;
        if (request::get_bool('foreign_work_experience-yes')) {
            $data['foreign_work_experience'] = 1;
        } elseif (request::get_bool('foreign_work_experience-no')) {
            $data['foreign_work_experience'] = 0;
        }

        $data['marital_status'] = -1;
        if (request::get_bool('marital_status-yes')) {
            $data['marital_status'] = 1;
        } elseif (request::get_bool('marital_status-no')) {
            $data['marital_status'] = 0;
        }

        $data['kids'] = -1;
        if (request::get_bool('kids-yes')) {
            $data['kids'] = 1;
        } elseif (request::get_bool('kids-no')) {
            $data['kids'] = 0;
        }

        $data['smoke'] = -1;
        if (request::get_bool('smoke-yes')) {
            $data['smoke'] = 1;
        } elseif (request::get_bool('smoke-no')) {
            $data['smoke'] = 0;
        }

        $data['foreign_passport'] = -1;
        if (request::get_bool('foreign_passport-yes')) {
            $data['foreign_passport'] = 1;
        } elseif (request::get_bool('foreign_passport-no')) {
            $data['foreign_passport'] = 0;
        } elseif (request::get_bool('foreign_passport-wait')) {
            $data['foreign_passport'] = 2;
        }

        $higher_education = [
            0 => [
                'university' => request::get_string('university'),
                'faculty' => request::get_string('faculty'),
                'study' => request::get_int('study'),
                'status' => request::get_int('status'),
                'entry_year' => request::get_int('entry_year'),
                'ending_year' => request::get_int('ending_year'),
            ],
        ];

        $data['higher_education'] = serialize($higher_education);

        if ($id = user_additional_peer::instance()->get_list(['user_id' => $this->user_id])) {
            $data['id'] = $id[0];
            user_additional_peer::instance()->update($data);
        } else {
            user_additional_peer::instance()->insert($data);
        }
    }

    public function options()
    {
        if (request::get('ad') == 'subdomain') {
            $subdomain = request::get_string('subdomain');
            user_data_peer::instance()->update(
                [
                    'user_id' => $this->user_id,
                    'subdomain' => $subdomain,
                ]
            );

            return true;
        }

        //		$old_password = request::get("old_password");
        $new_password = request::get('new_password');
        $new_password_confirmation = request::get('new_password_confirmation');

        //		var_dump($this->profile);
        //		if($this->profile["password"] != md5($old_password))
        //		{
        //			$this->json = array(
        //					"success" => false,
        //					"error" => "password"
        //			);
        //			return false;
        //		}

        if ($new_password != $new_password_confirmation || $new_password == '') {
            $this->json = [
                'success' => false,
                'error' => 'confirmation',
            ];

            return false;
        }

        profile_peer::instance()->change_password($this->user_id, $new_password);
    }

    private function general()
    {
        $data = [
            'user_id' => $this->user_id,
            'last_name' => trim(request::get('last_name')),
            'first_name' => trim(request::get('first_name')),
            'last_name_en' => trim(request::get('last_name_en')),
            'first_name_en' => trim(request::get('first_name_en')),
            'middle_name' => trim(request::get('middle_name')),
            'sex' => request::get_bool('male') ? 0 : 1,
            'birthday' => ui_helper::dateval('birthday'),
            'country' => request::get_int('country'),
            'region' => request::get_int('region'),
            'city' => request::get_int('city'),
            'another_city' => request::get('another_city'),
        ];

        profile_peer::instance()->update($data, $data['user_id']);
    }

    private function locality()
    {
        $data = [
            'user_id' => $this->user_id,
            'country' => request::get_int('country'),
            'region' => request::get_int('region'),
            'city' => request::get_int('city'),
            'another_city' => request::get('another_city'),
        ];

        profile_peer::instance()->update($data, $data['user_id']);
    }

    private function contacts()
    {
        $data = [
            'user_id' => $this->user_id,
            'email' => [
                'value' => request::get_string('email'),
                'access' => request::get_int('email-access'),
            ],
            'phone' => [
                'value' => request::get('phone'),
                'access' => request::get_int('phone-access'),
            ],
            'website' => [
                'value' => request::get('website'),
                'access' => request::get_int('website-access'),
            ],
            'skype' => [
                'value' => request::get('skype'),
                'access' => request::get_int('skype-access'),
            ],
            'icq' => [
                'value' => request::get('icq'),
                'access' => request::get_int('icq-access'),
            ],
            'facebook' => [
                'value' => request::get('facebook'),
                'access' => request::get_int('facebook-access'),
            ],
            'napodiume' => [
                'value' => request::get('napodiume'),
                'access' => request::get_int('napodiume-access'),
            ],
            'vkontakte' => [
                'value' => request::get('vkontakte'),
                'access' => request::get_int('vkontakte-access'),
            ],
        ];

        $email = user_auth_peer::instance()->get_item($this->user_id);

        if (!filter_var($data['email']['value'], FILTER_VALIDATE_EMAIL)) {
            $inx = db::get_scalar(
                'SELECT COUNT(id) FROM user_auth WHERE id <> '.$this->user_id
                .' AND email = :email',
                ['email' => $data['email']['value']]
            );

            if ($data['email']['value'] == '' || $inx > 0) {
                $this->json['error'] = 'email';

                return $this->json['success'] = false;
            }

            user_auth_peer::instance()->update(['id' => $this->user_id, 'email' => $data['email']['value']]);
        }

        profile_peer::instance()->set_contacts($data, $data['user_id']);

        if (session::has_credential('admin')) {
            $profile = profile_peer::instance()->get_item($this->user_id);

            $hidden_data = is_array($arr = unserialize($profile['hidden_data'])) ? $arr : [];

            $hidden_data['email'] = request::get_string('hd_email');
            $hidden_data['phone'] = request::get_string('hd_phone');
            $hidden_data['alt_tel'] = request::get_string('hd_alt_tel');

            user_data_peer::instance()->update(
                [
                    'user_id' => $this->user_id,
                    'hidden_data' => serialize($hidden_data),
                ]
            );
        }
    }

    private function photo()
    {
        $data = [
            'user_id' => request::get_int('uid'),
            'pid' => request::get_int('pid'),
        ];
        user_data_peer::instance()->update($data);
        $this->json['pid'] = $data['pid'];
    }

    private function ph_crop()
    {
        $ph_crop = [
            'x' => request::get_int('x'),
            'y' => request::get_int('y'),
            'w' => request::get_int('w'),
            'h' => request::get_int('h'),
        ];

        $data = [
            'user_id' => request::get_int('uid'),
            'ph_crop' => serialize($ph_crop),
        ];

        user_data_peer::instance()->update($data);
    }

}
