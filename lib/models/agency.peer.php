<?php

class agency_peer extends db_peer_postgre
{
    protected $table_name = 'agency';

    protected $primary_key = 'id';

    public static function get_agency($id = false)
    {
        $data = db::get_rows('SELECT * FROM agency');
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                $ret[$v['id']] = $v['name'];
            }

            return ($id) ? (isset($ret[$id]) ? $ret[$id] : false) : $ret;
        }

        return false;
    }

    public function getCities($countryId)
    {
        $sql    = sprintf('SELECT city FROM agency WHERE country = %s AND city > 0 GROUP BY city;', $countryId);
        return array_map(
            function ($id) {
                return $this->getCity($id);
            },
            db::get_cols($sql)
        );
    }

    private function getCity($id)
    {
        $agencyIds = agency_peer::instance()->get_list(['city' => $id], [], ['name']);

        return [
            'id'       => $id,
            'name'     => geo_peer::instance()->get_city($id),
            'agencies' => array_map(
                function ($id) {
                    return agency_peer::instance()->get_item($id);
                },
                $agencyIds
            ),
        ];
    }

    /**
     * @param string $peer
     * @return agency_peer|db_peer|object
     */
    public static function instance($peer = 'agency_peer')
    {
        return parent::instance($peer);
    }

    /**
     * @param $primary_key
     * @return mixed
     */
    public function get_item($primary_key)
    {
        $item = parent::get_item($primary_key);

        $item['contacts'] = unserialize($item['contacts']);
        if (!is_array($item)) {
            $item['contacts'] = [];
        }

        return $item;
    }

    public function update($data, $keys = null)
    {
        if (isset($data['contacts']) && is_array($data['contacts'])) {
            $data['contacts'] = serialize($data['contacts']);
        }

        parent::update($data, $keys);
    }

    public function is_exists($agency_name)
    {
        $sql = sprintf('SELECT * FROM %s WHERE name = \'%s\' LIMIT 1;', $this->table_name, $agency_name);

        return db::get_row($sql);
    }
}


