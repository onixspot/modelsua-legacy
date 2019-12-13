<?php

/**
 * @property array json
 */
class adminka_successfull_action extends frontend_controller
{

    public function execute()
    {
        $new_face    = user_auth_peer::NEW_FACES;
        $perspective = user_auth_peer::PERSPECTIVE;
        $successful  = user_auth_peer::SUCCESSFUL;
        $legendary   = user_auth_peer::LEGENDARY;

        $model_type = request::get_int('mt');

        switch ($model_type) {
            // NEW FACES [100<show_on_main < 200]
            case 1:
                $sqladd    = sprintf(' show_on_main >= %s AND show_on_main < %s', $new_face, $perspective);
                $check_sql = sprintf(' ((show_on_main > %s AND show_on_main < %s) OR show_on_main >= %s)', $successful, $new_face, $perspective);
                $check_val = $new_face;
                $key       = 'new_faces_view_type';
                break;

            // PERSPECTIVE [show_on_main > 200]
            case 2:
                $sqladd    = sprintf(' show_on_main >= %s AND show_on_main < %s', $perspective, $legendary);
                $check_sql = sprintf(' show_on_main > %s AND show_on_main < %s', $successful, $perspective);
                $check_val = $perspective;
                $key       = 'perspective_models_view_type';
                break;

            // LEGENDARY [show_on_main >= 3000]
            case 3:
                $sqladd    = sprintf(' show_on_main >= %s', $legendary);
                $check_sql = sprintf(' show_on_main > %s', $legendary);
                $check_val = $legendary;
                $key       = 'legendary_models_view_type';
                break;

            // SUCCESSFUL [0 < show_on_main < 100]
            default:
                $sqladd    = sprintf(' show_on_main > %s AND show_on_main < %s', $successful, $new_face);
                $check_sql = sprintf(' show_on_main > %s', $new_face);
                $check_val = $successful;
                $key       = 'successfull_models_view_type';
                break;
        }

        $this->most = db::get_cols('SELECT id FROM user_auth WHERE '.$sqladd.' ORDER BY show_on_main');

        if (request::get('submit')) {

            $this->set_renderer('ajax');
            $action = request::get('type');
            $id     = request::get_int('id');

            switch ($action) {
                case 'add':

                    $limit     = db::get_scalar('SELECT count(id) FROM user_auth WHERE '.$sqladd);
                    $user_auth = db::get_row('SELECT * FROM user_auth WHERE id=:id', ['id' => $id]);
                    $user_data = db::get_row('SELECT * FROM user_data WHERE user_id=:id', ['id' => $id]);
                    $check     = db::get_scalar('SELECT id FROM user_auth WHERE id=:id AND '.$check_sql, ['id' => $id]);

                    if (isset($_GET['debug-mode']) && 1 === (int) $_GET['debug-mode']) {
                        die(sprintf("%s\n", json_encode($user_data, JSON_PRETTY_PRINT)));

                    }

                    if ($check) {
                        $this->json = ['success' => 0, 'reason' => 'Выбраный пользователь уже находиться в другом списке'];
                    } elseif (!$user_data['pid'] || !$user_data['ph_crop']) {
                        $this->json = ['success' => 0, 'reason' => 'У пользователя не стандартная фотография или она отсутствует'];
                    } elseif ($user_auth['show_on_main'] > $check_val) {
                        $this->json = ['success' => 0, 'reason' => 'Этот пользователь уже в списке'];
                    } elseif ($user_auth) {
                        $max                       = db::get_scalar('SELECT MAX(show_on_main) FROM user_auth WHERE '.$sqladd);
                        $user_auth['show_on_main'] = ($model_type && !$max) ? $check_val : ($max + 1);
                        user_auth_peer::instance()->update($user_auth);
                        $user_data  = user_data_peer::instance()->get_item($user_auth['id']);
                        $this->json = ['success' => 1, 'data' => $user_data];
                    } else {
                        $this->json = ['success' => 0, 'reason' => 'Користувач не існує'];
                    }

                    break;


                case 'delete':
                    $user_auth = user_auth_peer::instance()->get_item($id);
                    if ($user_auth) {
                        $user_auth['show_on_main'] = 0;
                        user_auth_peer::instance()->update($user_auth);
                        $this->json = [
                            'success' => 1,
                            'data'    => $user_auth,
                        ];
                    } else {
                        $this->json = ['success' => 0, 'reason' => 'Користувач не існує'];
                    }
                    break;

                case 'change_place':
                    $direct    = request::get_int('direct');
                    $user_auth = user_auth_peer::instance()->get_item($id);

                    if (in_array($direct, ['1', '2']) && $user_auth) {
                        $sql2 = 'SELECT id FROM user_auth WHERE show_on_main IN (SELECT '.(($direct == 1) ? 'MIN' : 'MAX')
                            .'(show_on_main) FROM user_auth WHERE show_on_main'.(($direct == 1) ? '>' : '<').':uid AND '.$sqladd.')';

                        $uid = db::get_scalar($sql2, ['uid' => $user_auth['show_on_main']]);
                        if ($uid) {
                            $user_change = user_auth_peer::instance()->get_item($uid);

                            $tmp                         = $user_change['show_on_main'];
                            $user_change['show_on_main'] = $user_auth['show_on_main'];
                            $user_auth['show_on_main']   = $tmp;


                            user_auth_peer::instance()->update($user_auth);
                            user_auth_peer::instance()->update($user_change);
                            $this->json = ['success' => 1, 'data' => $user_auth];
                        } else {
                            $this->json = ['success' => 0, 'reason' => 'Невірно вибраний напрямок'];
                        }
                    } else {
                        $this->json = ['success' => 0, 'reason' => 'Не коректні вхідні данні'];
                    }
                    break;


                case 'change_view':
                    $value = request::get_int('val');
                    db_key::i()->set($key, request::get_int('val'));
                    $this->json = ['value' => db_key::i()->get($key)];
                    break;

                default :
                    $this->json = ['success' => 0, 'reason' => 'Не коректні вхідні данні'];
                    break;
            }
        }
        if (db_key::i()->exists('verifyingSoM')) {
            if (db_key::i()->get('verifyingSoM') - time() > 86400) {
                $this->verifySoM();
            }
        } else {
            $this->verifySoM();
        }
    }

    private function verifySoM()
    {
        $offsets = [user_auth_peer::SUCCESSFUL, user_auth_peer::NEW_FACES, user_auth_peer::PERSPECTIVE];
        foreach ($offsets as $index => $offset) {
            $sql = 'SELECT * FROM user_auth WHERE show_on_main>'.$offset;
            if (isset($offsets[($index + 1)])) {
                $sql .= ' AND show_on_main<'.$offsets[($index + 1)];
            }
            $sql .= ' ORDER BY show_on_main ASC';
            //		echo $sql."<br/>";
            $list = db::get_rows($sql);
            foreach ($list as $key => $item) {
                $rVal = ($key + $offset + 1);
                //		    echo $rVal." => ".$item['show_on_main']."<br/>";
                if ($rVal != $item['show_on_main']) {
                    $item['show_on_main'] = $rVal;
                    user_auth_peer::instance()->update($item);

                    //			echo $item['show_on_main']." = ".$rVal."<br/>";
                    //			echo 'updating....<br/>';
                }
            }
        }
        db_key::i()->set('verifyingSoM', time());
    }
}

