<?php

/**
 * Class geo_peer
 */
class geo_peer extends db_peer_postgre
{
    const AUSTRALIA   = 4;
    const AUSTRIA     = 63;
    const BELGIUM     = 404;
    const BRAZIL      = 467;
    const CANADA      = 2172;
    const CHILE       = 582031;
    const CHINA       = 2374;
    const DENMARK     = 1366;
    const FRANCE      = 10668;
    const GERMANY     = 1012;
    const GREECE      = 1258;
    const INDONESIA   = 277559;
    const ISRAEL      = 1393;
    const ITALIA      = 1786;
    const JAPAN       = 11060;
    const LEBANON     = 2505884;
    const MALAYSIA    = 277563;
    const NETHERLANDS = 277551;
    const NORWAY      = 2880;
    const SINGAPORE   = 277565;
    const SOUTH_KOREA = 11014;
    const SPAIN       = 1707;
    const SWEDEN      = 10933;
    const SWITZERLAND = 10904;
    const TAIWAN      = 277567;
    const TURKEY      = 9705;
    const UAE         = 582051;
    const UK          = 616;
    const UKRAINE     = 9908;
    const USA         = 5681;
    const PORTUGAL    = 3141;

    const COUNTRY_IDS = [
        self::USA,
        self::FRANCE,
        self::ITALIA,
        self::CHINA,
        self::JAPAN,
        self::SINGAPORE,
        self::GERMANY,
        self::SPAIN,
        self::UK,
        self::SWITZERLAND,
        // ---
        self::AUSTRALIA,
        self::AUSTRIA,
        self::BELGIUM,
        self::BRAZIL,
        self::CANADA,
        self::CHILE,
        self::DENMARK,
        self::GREECE,
        self::INDONESIA,
        self::ISRAEL,
        self::LEBANON,
        self::MALAYSIA,
        self::NETHERLANDS,
        self::NORWAY,
        self::SOUTH_KOREA,
        self::SWEDEN,
        self::TAIWAN,
        self::TURKEY,
        self::UAE,
        self::PORTUGAL,
    ];

    const ALLOWED_CITY_IDS_BY_COUNTRY_ID = [
        self::AUSTRALIA   => [49],
        self::AUSTRIA     => [65, 278170],
        self::BELGIUM     => [409, 406],
        self::BRAZIL      => [589],
        self::CANADA      => [2183, 2215, 2287],
        self::CHILE       => [2412951],
        self::CHINA       => [2422, 2425, 3503075, 15790018, 15790019, 4691841],
        self::DENMARK     => [2331530],
        self::FRANCE      => [10805],
        self::GERMANY     => [1014, 1107, 278190, 278154, 1117, 1173, 1101],
        self::GREECE      => [1262],
        self::INDONESIA   => null,
        self::ISRAEL      => [1447],
        self::ITALIA      => [1820, 1835],
        self::JAPAN       => [11267],
        self::LEBANON     => null,
        self::MALAYSIA    => [279122],
        self::NETHERLANDS => [278093, 278219],
        self::NORWAY      => [2888,],
        self::SINGAPORE   => null,
        self::SOUTH_KOREA => [11053],
        self::SPAIN       => [1764, 1733],
        self::SWEDEN      => [10961],
        self::SWITZERLAND => [10932, 10917],
        self::TAIWAN      => null,
        self::TURKEY      => null,
        self::UAE         => [2372615, 5000000],
        self::UK          => [740],
        self::USA         => [7992, 279123, 278193, 6788, 6517, 8721, 9085, 9327, 6294],
        self::PORTUGAL    => [3148],
    ];

    protected $table_name = '';

    /**
     * @param string $peer
     *
     * @return db_peer|geo_peer
     */
    public static function instance($peer = 'geo_peer')
    {
        return parent::instance($peer);
    }

    public function get_list_by_table($table)
    {
        $this->table_name = $table;

        $list = [];

        foreach ($this->get_list() as $id) {
            $list[] = $this->get_item($id, true);
        }

        return $list;
    }

    public function get_item_by_table($id, $table)
    {
        $this->table_name = $table;

        return $this->get_item($id);
    }

    public function get_country($id)
    {
        return $this->get_country_metadata($id)['name_'.session::get('language', 'ru')];
    }

    /* COUNTRIES */

    public function get_country_metadata($country_id)
    {
        $this->table_name = 'countries';

        $id = $this->get_list(['country_id' => $country_id], [], [], 1)[0];

        return $this->get_item($id, true);
    }

    /**
     * @param array $exclude
     *
     * @return array
     */
    public function get_countries_diff($exclude)
    {
        $this->table_name = 'countries';

        $cond = [];
        if (!isset($cond['hidden'])) {
            $cond['hidden'] = 'false';
        }

        return $this->sanitizeRecords(array_diff($this->get_list($cond, [], ['priority DESC']), $exclude));
    }

    /**
     * @param array $ids
     *
     * @return array
     */
    private function sanitizeRecords($ids)
    {
        return array_map(
            function ($id) {
                $item = $this->get_item($id);

                return array_merge($item, ['name' => $item[sprintf("name_%s", session::get('language', 'ru'))]]);
            },
            $ids
        );
    }

    public function get_countries($cond = [])
    {
        $this->table_name = 'countries';

        if (!isset($cond['hidden'])) {
            $cond['hidden'] = 'false';
        }

        $topIds = [1, 4, 2, 3, 24, 10, 6, 9, 5, 99, 80];

        return $this->sanitizeRecords(array_merge($topIds, array_diff($this->get_list($cond, [], ['name_ru ASC']), $topIds)));
    }

    public function set_countries($countries)
    {
        $keys = [];
        foreach ($countries as $country) {
            $keys[] = $this->set_country($country);
        }

        return $keys;
    }

    public function set_country($country)
    {
        $this->table_name = 'countries';

        $data = [
            'country_id' => $country['country_id'],
            'name'       => $country['name'],
        ];

        if (is_bool($country['hidden'])) {
            $data['hidden'] = $country['hidden'];
        }

        return $this->insert($data);
    }

    /* REGIONS */

    public function get_region($region_id)
    {
        $this->table_name = 'regions';

        $cond = [
            'region_id' => $region_id,
        ];

        $regions_id = $this->get_list($cond, [], [], 1);

        $region = $this->get_item($regions_id[0]);

        $ret['name'] = $region['name_'.session::get('language', 'ru')];

        return $ret['name'];
    }

    public function get_regions($cond = [])
    {
        $this->table_name = 'regions';

        if (!isset($cond['hidden'])) {
            $cond['hidden'] = 'false';
        }

        $regions_id = $this->get_list($cond, [], ['name_'.session::get('language', 'ru').' ASC']);

        $regions = [];
        foreach ($regions_id as $region_id) {
            if (!in_array($region_id, [720], true)) {
                $regions[]                            = $this->get_item($region_id);
                $regions[count($regions) - 1]['name'] = $regions[count($regions) - 1]['name_'.session::get('language', 'ru')];
            }
        }

        return $regions;
    }

    public function set_regions($regions)
    {
        $keys = [];
        foreach ($regions as $region) {
            $keys[] = $this->set_region($region);
        }

        return $keys;
    }

    public function set_region($region)
    {
        $this->table_name = 'regions';

        $data = [
            'country_id' => $region['country_id'],
            'region_id'  => $region['region_id'],
            'name'       => $region['name'],
        ];

        if (is_bool($region['hidden'])) {
            $data['hidden'] = $region['hidden'];
        }

        return $this->insert($data);
    }

    /* CITIES */

    public function get_city($city_id)
    {
        $this->table_name = 'cities';
        $ids              = $this->get_list(['city_id' => $city_id], [], [], 1);
        if (count($ids) === 0) {
            return null;
        }

        return $this->get_item($ids[0])['name_'.session::get('language', 'ru')];
    }

    public function get_cities($cond = [], $options = [])
    {
        $this->table_name = 'cities';

        if (!isset($cond['hidden'])) {
            $cond['hidden'] = false;
        }

        if (isset($options['big-cities']) && $options['big-cities'] !== false) {
            unset($cond['hidden']);
        }

        $cities_id = $this->get_list($cond, [], ['name_'.session::get('language', 'ru').' ASC']);

        $districts = [];
        $cities    = [];
        $centers   = [];

        $big_cities = [
            [10184, 10398, 10029, 9977, 10532, 10337, 10251, 10252],
            [9916, 10076, 10119, 10151, 10214, 10299, 9955, 10340, 10430, 10452, 10475, 10501, 10108, 10556, 10579, 10603, 10631, 10647],
        ];

        foreach ($cities_id as $city_id) {
            $city         = $this->get_item($city_id);
            $city['name'] = $city['name_'.session::get('language', 'ru')];
            switch ($city['country_id']) {
                // Украина
                case self::UKRAINE:
                    if (isset($options['big-cities']) && $options['big-cities'] !== false) {
                        if (in_array($city['city_id'], $big_cities[0], true)) {
                            $centers[] = $city;
                        }

                        if (in_array($city['city_id'], $big_cities[1], true)) {
                            $cities[] = $city;
                        }
                    } else {
                        if (!$city['center']) {
                            if ($city['city_id'] >= 15789520) {
                                $districts[] = $city;
                            } else {
                                $cities[] = $city;
                            }
                        } else {
                            $centers[] = $city;
                        }
                    }
                    break;

                default:
                    if (!array_key_exists($city['country_id'], self::ALLOWED_CITY_IDS_BY_COUNTRY_ID)
                        || (self::ALLOWED_CITY_IDS_BY_COUNTRY_ID[$city['country_id']] !== null
                            && !in_array($city['city_id'], self::ALLOWED_CITY_IDS_BY_COUNTRY_ID[$city['country_id']], true))) {
                        continue 2;
                    }

                    $cities[] = $city;

                    break;
            }
        }

        if (isset($options['big-cities']) && $options['big-cities'] != false) {
            $tmp_arr = [];
            foreach ($big_cities[0] as $city_id) {
                foreach ($centers as $center) {
                    if ($center['city_id'] == $city_id) {
                        $tmp_arr[] = $center;
                    }
                }
            }
            $centers = $tmp_arr;
        }

        return array_merge($centers, $cities, $districts);
    }

    public function set_cities($cities)
    {
        $keys = [];
        foreach ($cities as $city) {
            $keys[] = $this->set_city($city);
        }

        return $keys;
    }

    public function set_city($city)
    {
        $this->table_name = 'cities';

        $data = [
            'country_id' => $city['country_id'],
            'region_id'  => $city['region_id'],
            'city_id'    => $city['city_id'],
            'name'       => $city['name'],
        ];

        if (is_bool($city['hidden'])) {
            $data['hidden'] = $city['hidden'];
        }

        return $this->insert($data);
    }
}
