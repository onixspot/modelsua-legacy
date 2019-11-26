<?php

/**
 * Class geo_peer
 */
class geo_peer extends db_peer_postgre
{
    const UKRAINE     = 9908;
    const ITALIA      = 1786;
    const FRANCE      = 10668;
    const USA         = 5681;
    const CHINA       = 2374;
    const JAPAN       = 11060;
    const SINGAPORE   = 277565;
    const GERMANY     = 1012;
    const INDONESIA   = 277559;
    const MALAYSIA    = 277563;
    const TURKEY      = 9705;
    const LEBANON     = 582060;
    const SPAIN       = 1707;
    const TAIWAN      = 277567;
    const UK          = 616;
    const SOUTH_KOREA = 11014;

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

    public function get_country_metadata($country_id)
    {
        $this->table_name = 'countries';

        $id = $this->get_list(['country_id' => $country_id], [], [], 1)[0];

        return $this->get_item($id, true);
    }

    /* COUNTRIES */
    public function get_country($id)
    {
        return $this->get_country_metadata($id)['name_'.session::get('language', 'ru')];
    }

    public function get_countries($cond = [])
    {
        $this->table_name = 'countries';

        if (!isset($cond['hidden'])) {
            $cond['hidden'] = 'false';
        }

        $countries_id = $this->get_list($cond, [], ['priority DESC']);

        $countries = [];
        foreach ($countries_id as $country_id) {
            $countries[]                              = $this->get_item($country_id);
            $countries[count($countries) - 1]['name'] = $countries[count($countries) - 1]['name_'.session::get('language', 'ru')];
        }

        return $countries;
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

    public function set_countries($countries)
    {
        $keys = [];
        foreach ($countries as $country) {
            $keys[] = $this->set_country($country);
        }

        return $keys;
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

    public function set_regions($regions)
    {
        $keys = [];
        foreach ($regions as $region) {
            $keys[] = $this->set_region($region);
        }

        return $keys;
    }

    /* CITIES */
    public function get_city($city_id)
    {
        $this->table_name = 'cities';

        $cond = [
            'city_id' => $city_id,
        ];

        $cities_id = $this->get_list($cond, [], [], 1);

        $city = $this->get_item($cities_id[0]);

        $ret['name'] = $city['name_'.session::get('language', 'ru')];

        return $ret['name'];
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
                // Германия
                case self::GERMANY:
                    if (in_array($city['city_id'], [1014, 1107, 278190, 278154, 1117], true)) {
                        $cities[] = $city;
                    }
                    break;

                // Испания
                case self::SPAIN:
                    if (in_array($city['city_id'], [1764, 1733], true)) {
                        $cities[] = $city;
                    }
                    break;

                // Малайзия
                case self::MALAYSIA:
                    if (in_array($city['city_id'], [279122], true)) {
                        $cities[] = $city;
                    }
                    break;

                // ОАЭ
                case 582051:
                    if (in_array($city['city_id'], [2372615, 5000000], true)) {
                        $cities[] = $city;
                    }
                    break;

                // Китай
                case self::CHINA:
                    if (in_array($city['city_id'], [2422, 2425, 3503075, 15790018, 15790019, 4691841], true)) {
                        $cities[] = $city;
                    }
                    break;

                // Япония
                case self::JAPAN:
                    if (in_array($city['city_id'], [11267/*, 11125, 11199*/], true)) {
                        $cities[] = $city;
                    }
                    break;

                // США
                case self::USA:
                    if (in_array($city['city_id'], [7992, 279123, 278193, 6788, 6517, 8721, 9085, 9327], true)) {
                        $cities[] = $city;
                    }
                    break;

                // Италия
                case self::ITALIA:
                    if (in_array($city['city_id'], [1820, 1835/*, 278193, 1863, 1853, 1822, 1886, 1875, 5911563*/], true)) {
                        $cities[] = $city;
                    }
                    break;

                // Франция
                case self::FRANCE:
                    if (in_array($city['city_id'], [10805/*, 10685, 10822*/], true)) {
                        $cities[] = $city;
                    }
                    break;

                case self::UK:
                    if (in_array($city['city_id'], [740/*, 10685, 10822*/], true)) {
                        $cities[] = $city;
                    }
                    break;

                case self::SOUTH_KOREA:
                    if (in_array($city['city_id'], [0, 11053], true)) {
                        $cities[] = $city;
                    }
                    break;

                case self::INDONESIA:
                case self::TURKEY:
                case self::LEBANON:
                case self::TAIWAN:
                case self::SINGAPORE:
                    $cities[] = $city;
                    break;

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

    public function set_cities($cities)
    {
        $keys = [];
        foreach ($cities as $city) {
            $keys[] = $this->set_city($city);
        }

        return $keys;
    }
}
