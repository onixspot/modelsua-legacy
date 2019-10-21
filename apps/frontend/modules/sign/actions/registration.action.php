<?php

load::view_helper('ui');
load::system('email/email');
load::model('user/profile');
load::model('user/user_photos');
load::model('user/user_albums');

class sign_registration_action extends frontend_controller
{

    public function execute()
    {
        if (request::get('submit')) {
            $this->save_data();
        }

        $act = request::get_string('act');
        if (in_array($act, ['remove_photo'])) {
            $this->set_renderer('ajax');

            return $this->json['success'] = $this->$act();
        }
    }

    private function remove_photo()
    {
        $pid = request::get_int('pid');
        user_photos_peer::instance()->delete_item($pid);

        return true;
    }

    private function save_data()
    {
        $this->set_renderer('ajax');
        $this->json['success'] = true;

        $learned_about = '';
        if (request::get_bool('from_facebook')) {
            $learned_about = 'facebook';
        } elseif (request::get_bool('from_vkontakte')) {
            $learned_about = 'vkontakte';
        } elseif (request::get_bool('from_odnoklassniki')) {
            $learned_about = 'odnoklassniki';
        } elseif (request::get_bool('from_friends')) {
            $learned_about = 'friends';
        } elseif (request::get_bool('from_banners')) {
            $learned_about = 'banners';
        }

        $params = [
            'eye_color'   => request::get_int('eye_color'),
            'hair_color'  => request::get_int('hair_color'),
            'hair_length' => request::get_int('hair_length'),
        ];

        foreach ($params as $param_key => $param_value) {
            if ($params[$param_key] < 1) {
                $this->json['msgErr'] = $param_key.'-empty';

                return $this->json['success'] = false;
            }
        }

        $work_experience = request::get_int('work_experience');
        //		if($work_experience < 0)
        //		{
        //			$this->json["success"] = false;
        //			$this->json["msgErr"] = "work_experience-empty";
        //			return false;
        //		}

        $__images = request::get('images');
        if (!is_array($__images)) {
            $this->json['success'] = false;
            $this->json['msgErr']  = 'images-empty';

            return false;
        }

        $images = [];
        foreach ($__images as $img) {
            if ((int) $img > 0) {
                $images[] = $img;
            }
        }

        if (count($images) < 3) {
            $this->json['success'] = false;
            $this->json['msgErr']  = 'images-empty';

            return false;
        }

        $data = [
            'created_ts'    => time(),
            'type'          => 2,
            'sex'           => request::get_bool('male') ? 0 : 1,
            'first_name'    => trim(request::get_string('first_name')),
            'middle_name'   => trim(request::get_string('middle_name')),
            'last_name'     => trim(request::get_string('last_name')),
            'birthday'      => ui_helper::dateval('birthday'),
            'country'       => request::get_int('country'),
            'region'        => request::get_int('region'),
            'city'          => request::get_int('city'),
            'another_city'  => request::get_string('another_city'),
            'growth'        => request::get('growth'),
            'weigth'        => request::get('weigth'),
            'breast'        => request::get('breast'),
            'waist'         => request::get('waist'),
            'hip'           => request::get('hip'),
            'email'         => request::get('email'),
            'phone'         => request::get('phone'),
            'website'       => request::get('website'),
            'skype'         => request::get('skype'),
            'icq'           => request::get('icq'),
            'facebook'      => request::get('facebook'),
            'napodiume'     => request::get('napodiume'),
            'vkontakte'     => request::get('vkontakte'),
            'pid'           => $images[0],
            'learned_about' => $learned_about,
        ];


        //              Заявка на членство в АМУ вер. 2.0 блян
        if (request::get_int('application')) {
            $data = array_merge($data, (['application' => profile_peer::MODEL_OR_CANDIDATE_TYPE, 'application_approve' => 0]));
        }

        $statment_type = [];
        //		if(request::get_bool('model'))
        //			$statment_type[] = array(
        //				'key' => 'model',
        //				'status' => 0
        //			);
        //
        //		if(request::get_bool('association_member'))
        //		{
        //			$statment_type[] = array(
        //				'key' => 'association_member',
        //				'status' => 0
        //			);
        //			$statment_type[] = array(
        //				'key' => 'model',
        //				'status' => 0
        //			);
        //		}
        $statment_type[] = [
            'key'    => 'model',
            'status' => 1,
        ];

        $data['statement_type'] = serialize($statment_type);

        if (profile_peer::instance()->is_exists(['email' => $data['email']])) {
            $this->json['success'] = false;
            $this->json['msgErr']  = 'email-exists';

            return false;
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->json['success'] = false;
            $this->json['msgErr']  = 'email-incorrect';

            return false;
        }

        $notNull = ['birthday', 'email', 'first_name', 'last_name', 'country', 'city', 'phone', 'growth', 'weigth', 'breast', 'waist', 'hip'];
        foreach ($notNull as $item) {
            if ($data[$item] == '' || $data[$item] == '0') {
                $this->json['success'] = false;
                $this->json['msgErr']  = $item.'-empty';

                return false;
            }
        }

        if ($data['city'] == '-1' && $data['another_city'] == '') {
            $this->json['success'] = false;
            $this->json['msgErr']  = 'city-empty';

            return false;
        }

        if (!$user_id = profile_peer::instance()->insert($data)) {
            $this->json['success'] = false;
            $this->json['msgErr']  = 'registration-error';

            return false;
        }
        load::model('email_templates');
        $cnt     = 0;
        $_images = [];
        foreach ($images as $image) {
            user_photos_peer::instance()->update([
                'id'      => $image,
                'user_id' => $user_id,
            ]);
            if ($cnt > 0) {
                $_images[] = $image;
            }
            $cnt++;
        }

        user_albums_peer::instance()->insert([
            'user_id'  => $user_id,
            'category' => 'portfolio',
            'images'   => serialize($_images),
        ]);

        user_additional_peer::instance()->insert([
            'user_id'         => $user_id,
            'work_experience' => $work_experience,
            'about_self'      => request::get_string('about_self'),
        ]);

        profile_peer::instance()->set_params($params, $user_id);

        $hidden_data = [];
        if (request::get_bool('iwantbemember')) {
            $hidden_data['iwant']      = 'member';
            $hidden_data['workedwith'] = request::get_string('workedwith');
        } else {
            $hidden_data['iwant'] = 'model';
            $hidden_data['why']   = request::get_string('whydoyouwantbemodel');
        }

        $ud = [
            'user_id'     => $user_id,
            'hidden_data' => serialize($hidden_data),
        ];

        user_data_peer::instance()->update($ud);

        $this->json['email'] = $data['email'];
        $email_data          = email_templates_peer::get_email_template('registration');

        $email = new email($data['email'], $email_data['subject']);
        $email->isHTML();
        $cnt = 0;
        foreach ($statment_type as $type) {
            if ($cnt > 0) {
                continue;
            }
            $replace = [
                profile_peer::get_name($data),
                'регистрацию '.profile_peer::get_statement($type['key']),
                'Регистрация '.profile_peer::get_statement($type['key']),
            ];
            $_body   = str_replace(['{$name}', '{$statement}', '{$statement2}'], $replace, $email_data['body']);
            $email->setSender($email_data['sender_email'], $email_data['sender_name']);
            $email->setBody($_body);
            $email->send();
            $cnt++;
        }
    }

}
