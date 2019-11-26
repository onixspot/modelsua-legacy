<?php

load::app('modules/home/controller');
load::model('user/user_albums');
load::model('user/profile');

/**
 * @property array json
 */
class home_index_action extends home_controller
{

    public function execute()
    {
        $act = request::get_string('act');
        if ('get_next_update' === $act) {
            $this->set_renderer('ajax');

            return $this->json['success'] = $this->$act();
        }

        // MAIN PAGE NEWS
        load::model('news');
        if ($news_id = db::get_scalar('SELECT id FROM news WHERE type=:type AND hidden=0 ORDER BY created_ts DESC LIMIT 1', ['type' => news_peer::NEWS_TYPE])) {
            $this->news = news_peer::instance()->get_content($news_id);
        }
        if ($publication_id = db::get_scalar('SELECT id FROM news WHERE type=:type AND hidden=0 ORDER BY created_ts DESC LIMIT 1', ['type' => news_peer::PUBLICATIONS_TYPE])) {
            $this->publication = news_peer::instance()->get_content($publication_id);
        }
        $anons_id = db::get_scalar('SELECT id FROM news WHERE type=:type AND hidden=0 AND end_ts>:date ORDER BY created_ts ASC LIMIT 1', [
            'type' => news_peer::ANONS_TYPE,
            'date' => time(),
        ]); ////////////Ближайшее событие
        if (!$anons_id) {
            $anons_id = db::get_scalar('SELECT id FROM news WHERE type=:type AND end_ts<=:date AND hidden=0 ORDER BY created_ts DESC LIMIT 1', [
                'type' => news_peer::ANONS_TYPE,
                'date' => time(),
            ]);
        } ////////////Последнее событие
        if ($anons_id) {
            $this->anons = news_peer::instance()->get_content($anons_id);
        }

        //              MOST POPULAR
        load::model('voting');
        $list             = voting_peer::getModelsVoteList();
        $checkList        = voting_peer::getModelsVoteList(session::get_user_id());
        $this->check_vote = count($checkList) > 1;
        $rand             = $this->get_random_data($list, 2);
        $this->model1     = $rand[0];
        $this->model2     = $rand[1];

        // MOST SUCCESSFULL
        $this->successful = $this->getGirls(0);

        // NEW FACES
        $this->new_faces = $this->getGirls(1);

        // PERSPECTIVE
        $this->perspective = $this->getGirls(2);

        // legendary
        $this->legendary = $this->getGirls(3);

        $updates = db::get_rows("SELECT id, category FROM user_albums WHERE category IN ('covers', 'fashion', 'defile', 'advertisement', 'catalogs') AND images <> 'a:0:{}' AND user_id IN (SELECT id FROM user_auth WHERE hidden = false AND type = 2) ORDER BY modified DESC");

        $boxes = [
            [
                'category' => 'covers',
                'updates'  => [],
                'images'   => [],
            ],
            [
                'category' => 'fashion',
                'updates'  => [],
                'images'   => [],
            ],
            [
                'category' => 'defile',
                'updates'  => [],
                'images'   => [],
            ],
            [
                'category' => 'adv',
                'updates'  => [],
                'images'   => [],
            ],
        ];

        $this->users = [];

        foreach ($updates as $update) {
            $index = 0;

            if ($update['category'] === 'fashion') {
                $index = 1;
            } elseif ($update['category'] === 'defile') {
                $index = 2;
            } elseif (in_array($update['category'], ['advertisement', 'catalogs'])) {
                $index = 3;
            } elseif ($update['category'] !== 'covers') {
                continue;
            }

            $album = user_albums_peer::instance()->get_item($update['id']);

            $boxes[$index]['images'] = array_merge($boxes[$index]['images'], $album['_i']);

            foreach ($album['_i'] as $pid) {
                $this->users[$pid] = $album['user_id'];
            }

            $boxes[$index]['updates'][] = $album;
        }

        $this->boxes = $boxes;

    }

    public function get_next_update()
    {
        $category = request::get_string('category');
        $step     = request::get_int('step');

        $updates = db::get_rows(
            "SELECT id, category FROM user_albums WHERE category = :category AND images <> 'a:0:{}' AND user_id IN (SELECT id FROM user_auth WHERE hidden = false AND type = 2) ORDER BY modified DESC",
            ['category' => $category]
        );

        $images = [];
        $users  = [];

        foreach ($updates as $update) {
            $album = user_albums_peer::instance()->get_item($update['id']);

            $images = array_merge($images, $album['_i']);

            foreach ($album['_i'] as $pid) {
                $users[$pid] = $album['user_id'];
            }
        }

        rsort($images);

        $this->json['image'] = $images[$step];

        $profile                 = profile_peer::instance()->get_item($users[$this->json['image']]);
        $this->json['user_name'] = '<a class="cwhite" href="/profile?id='.$users[$this->json['image']].'">'
            .profile_peer::get_name($profile, '&fn').' <span class="ucase">'.profile_peer::get_name($profile, '&ln').'</span></a>';

        return true;
    }

    private function get_random_data($data, $elements_count)
    {
        if ($elements_count > 0 && count($data) >= $elements_count) {
            for ($i = 0; $i < $elements_count; $i++) {
                $rand  = rand(0, (count($data) - 1));
                $ret[] = $data[$rand];
                unset($data[$rand]);
                sort($data);
            }

            return $ret;
        }

        return $data;
    }

    private function getGirls($type = 0)
    {
        $new_faces   = user_auth_peer::new_faces;
        $perspective = user_auth_peer::perspective;
        $successful  = user_auth_peer::successful;
        $legendary   = user_auth_peer::legendary;

        switch ($type) {
            case 1:
                $sqladd = sprintf(' a.show_on_main >= %s AND a.show_on_main < %s', $new_faces, $perspective);
                $key    = 'successfull_models_view_type';
                break;

            case 2:
                $sqladd = sprintf(' a.show_on_main >= %s AND a.show_on_main < %s', $perspective, $legendary);
                $key    = 'perspective_models_view_type';
                break;

            case 3:
                $sqladd = sprintf(' a.show_on_main >= %s', $legendary);
                $key    = 'legendary_models_view_type';
                break;

            default:
                $sqladd = sprintf(' a.show_on_main > %s AND a.show_on_main < %s', $successful, $new_faces);
                $key    = 'new_faces_view_type';
                break;
        }

        $sql = <<<SQL
SELECT a.id, d.pid, d.ph_crop, d.first_name, d.last_name
FROM user_auth AS a
    JOIN user_data d ON a.id = d.user_id
WHERE %s
  AND d.pid IS NOT NULL
  AND d.ph_crop IS NOT NULL
  AND a.hidden=false
ORDER BY d.rank
SQL;

        $list = db::get_rows(sprintf($sql, $sqladd));

        if (count($list) > 10) {
            if (db_key::i()->exists($key)) {
                $this->most = $this->get_random_data($list, 11);
            }

            return array_slice($list, 0, 11);
        }

        return $list;
    }

}

?>
