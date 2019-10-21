<?

class pages_peer extends db_peer_postgre
{

    protected        $table_name = 'pages';

    public           $html       = '';

    protected static $template;

    protected static $options;


    /**
     * @return user_auth_peer
     */
    public static function instance()
    {
        return parent::instance('pages_peer');
    }

    public function get_by_url($url)
    {
        return db::get_scalar('SELECT id FROM pages WHERE link=:url', ['url' => $url]);
    }

    public function get_parent($id)
    {
        return db::get_scalar('SELECT parent_id FROM pages WHERE id=:id', ['id' => $id]);
    }

    public function get_content($link = false, $lang = 'ru')
    {
        $ret = ['id' => 0, 'title' => '', 'content' => '', 'link' => '', 'parent_id' => 0];

        if (!$link) {
            return $ret;
        }
        $sql = 'SELECT * FROM pages WHERE 1=1';

        if (filter_var($link, FILTER_VALIDATE_INT)) {
            $sql .= ' AND id=:link';
        } else {
            $sql .= ' AND link=:link';
        }

        $sql .= (session::has_credential('admin')) ? ' AND "on"<2' : ' AND "on"=1';

        $data = db::get_row($sql, ['link' => $link]);
        if (!empty($data)) {
            $tmp['title']    = unserialize($data['title']);
            $tmp['content']  = unserialize($data['content']);
            $data['title']   = stripslashes($tmp['title'][$lang]);
            $data['content'] = stripslashes($tmp['content'][$lang]);

            return $data;
        } else {
            return $ret;
        }
    }

    public function build_tree($node_ids, $full_data = false)
    {
        $ret = [];
        foreach ($node_ids as $id) {
            if ($full_data) {
                $ret[$id] = $this->instance()->get_item($id);
            } else {
                $ret[$id]['id'] = $id;
            }
            $ret[$id]['children'] = $this->find_children($id, $full_data);
        }

        return $ret;
    }

    private function find_children($item_id, $full_data = false)
    {
        $child_ids = pages_peer::instance()->get_list(['parent_id' => $item_id]);
        $ret       = [];
        if (!empty($child_ids)) {
            foreach ($child_ids as $id) {
                $tmp_arr[] = pages_peer::instance()->get_item($id);
            }
            uasort($tmp_arr, 'pages_peer::sort');
            foreach ($tmp_arr as $id => $item) {
                $sorted_data[] = $item['id'];
            }
            foreach ($sorted_data as $id) {
                if ($full_data) {
                    $ret[$id] = $this->instance()->get_item($id);
                } else {
                    $ret[$id]['id'] = $id;
                }
                $ret[$id]['children'] = $this->find_children($id, $full_data);
            }
        }

        return $ret;
    }

    public static function sort($a, $b)
    {
        return ($a['weight'] > $b['weight']) ? 1 : (($a['weight'] == $b['weight']) ? 0 : -1);
    }

    public static function set_options($data)
    {
        if (!empty ($data)) {
            foreach ($data as $k => $v) {
                self::$options[$k] = $v;
            }
        }
    }

    public static function get_options()
    {
        return self::$options;
    }

    public static function set_template($template)
    {
        self::$template = $template;
    }

    public function display_children($tree, $lvl = 0, $html = '', $lang = 'ru')
    {
        if (!empty($tree)) {
            foreach ($tree as $k => $v) {
                $page_data = pages_peer::instance()->get_content($v['id'], $lang);
                $style     = !$page_data['parent_id'] ? ' font-weight: bold;' : ' ';
                $style     .= !$page_data['on'] ? ' color: #cccccc !important; ' : ' ';

                self::set_options([
                    '%margin%'   => ($lvl * 20),
                    '%level%'    => $lvl,
                    '%selected%' => (request::get('link') == $page_data['link'] ? 'selected' : ' '),
                    '%link%'     => $page_data['link'],
                    '%title%'    => $page_data['title'],
                    '%id%'       => $page_data['id'],
                    '%hidden%'   => !$page_data['show'] | !$page_data['on'] ? ' hide' : '',
                    '%style%'    => $style,
                ]);
                $html = self::$template;
                foreach (self::$options as $key => $val) {
                    $html = str_replace($key, $val, $html);
                }

                $this->html .= $html;
                $this->display_children($v['children'], ($lvl + 1), $this->html);
            }
        }
    }

    public function display($tree, $lang = 'ru')
    {
        $this->display_children($tree, 0, '', $lang);

        return $this->html;
    }


}
