<?php

load::app('modules/agency/controller');

/**
 * @property int         per_page
 * @property int         page
 * @property array       list
 * @property array|mixed cities
 * @property array       context
 */
class agency_list_action extends agency_controller
{
    const COUNTRY_IDS = [
        geo_peer::UKRAINE,
        geo_peer::USA,
        geo_peer::UK,
        geo_peer::FRANCE,
        geo_peer::ITALIA,
        geo_peer::CHINA,
        geo_peer::JAPAN,
        geo_peer::SINGAPORE,
        geo_peer::GERMANY,
        geo_peer::INDONESIA,
        geo_peer::MALAYSIA,
        geo_peer::TURKEY,
        geo_peer::LEBANON,
        geo_peer::SPAIN,
        geo_peer::TAIWAN,
        geo_peer::SOUTH_KOREA,
    ];

    public function execute()
    {
        parent::execute();

        $this->context                 = $this->getContext();
        $this->agenciesWithoutLocation = $this->getAgenciesWithoutLocation();

        $this->per_page = 25;
        $this->page     = request::get_int('page', 1);

        $list       = agency_peer::instance()->get_list(['page_active' => true, 'public' => true]);
        $this->list = [];

        foreach ($list as $id) {
            $sql = <<<SQL
SELECT ud.pid 
FROM user_auth AS ua 
    INNER JOIN user_data AS ud ON ua.id = ud.user_id 
    INNER JOIN user_agency AS ug ON ug.user_id = ud.user_id 
WHERE ug.agency_id = {$id} 
  AND ua.type = 2 
  AND ud.status > 20 
  AND ua.hidden = false 
  AND ud.agency_rank >= 0 
ORDER BY ud.agency_rank
SQL;

            $this->list[$id] = db::get_cols($sql);
        }

        arsort($this->list);
        reset($this->list);

        $act = request::get('act');
        if ('set_rank' === $act) {
            $this->set_renderer('ajax');
            $this->$act();
        }
    }

    public function getAgenciesWithoutLocation()
    {
        $sql = 'select * from agency where city < 1';

        $agencies = [];
        foreach (db::get_cols($sql) as $col) {
            $agency = agency_peer::instance()->get_item($col);

            $link = '/adminka/agency_manager';
            if ($agency['page_active'] === true) {
                $link = sprintf('/agency/?id=%s', $agency['id']);
            }

            $agencies[] = array_merge($agency, ['link' => $link]);
        }

        return $agencies;
    }

    private function set_rank()
    {
        $data = request::get('data');
        foreach ($this->list as $id => $agency_id) {
            agency_peer::instance()->update([
                'id'   => $agency_id,
                'rank' => (array_search($agency_id, $data) + (request::get_int('page', 1) - 1)
                    * request::get_int('per_page')),
            ]);
        }

        $this->json = ($data);
    }

    private function getContext()
    {
        $context = [];
        foreach (self::COUNTRY_IDS as $id) {
            $sql     = sprintf('SELECT city FROM agency WHERE country = %s AND city > 0 GROUP BY city;', $id);
            $country = geo_peer::instance()->get_country_metadata($id);
            $cities  = array_map(function ($id) {
                return $this->getCity($id);
            }, db::get_cols($sql));

            usort($cities, static function ($a, $b) {
                return $a['agencies.count'] < $b['agencies.count'];
            });

            $context[] = [
                'id'     => $id,
                'name'   => $country['name_ru'],
                'code'   => $country['alpha_2_code'],
                'cities' => $cities,
            ];
        }

        return $context;
    }

    private function getCity($id)
    {
        $agencyIds = agency_peer::instance()->get_list(['city' => $id], [], ['name']);

        return [
            'id'             => $id,
            'name'           => geo_peer::instance()->get_city($id),
            'agencies'       => array_map(function ($id) {
                return $this->getAgency($id);
            }, $agencyIds),
            'agencies.count' => count($agencyIds),
        ];
    }

    private function getAgency($id)
    {
        $sql    = <<<SQL
SELECT ud.pid
FROM user_auth AS ua
    INNER JOIN user_data AS ud ON ua.id = ud.user_id
    INNER JOIN user_agency AS ug ON ug.user_id = ud.user_id
WHERE ug.agency_id = {$id}
  AND ua.type = 2
  AND ud.status > 20
  AND ua.hidden = false
  AND ud.agency_rank >= 0
ORDER BY ud.agency_rank
SQL;
        $agency = agency_peer::instance()->get_item($id);

        return array_merge($agency, [
            'members_count' => count(db::get_cols($sql)),
        ]);
    }
}
