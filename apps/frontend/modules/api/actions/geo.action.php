<?php

load::app('modules/api/controller');

load::model('agency');

/**
 * Class api_agency_action
 *
 * @property array $json
 */
class api_geo_action extends api_controller
{
    public function getCitiesByCountry($country)
    {
        $sql = sprintf('SELECT city FROM agency WHERE country = %s AND city > 0 GROUP BY city;', $country);

        return array_map(
            static function ($id) {
                return [
                    'id'   => $id,
                    'name' => geo_peer::instance()->get_city($id),
                ];
            },
            db::get_cols($sql)
        );
    }

    public function getRoutes()
    {
        return [
            '/^\/api\/geo\/countries\/(?P<country>(\d+))\/cities/' => [[$this, 'getCitiesByCountry'], ['country']],
        ];
    }
}