<?php

load::app('modules/api/controller');

load::model('user/user_agency');

/**
 * Class api_profiles_action
 */
class api_profiles_action extends api_controller
{
    public function getRoutes()
    {
        return [
            '/^\/api\/profiles\/(?P<id>\d+)\/agencies.*/' => [[$this, 'updateProfileAgencies'], ['id']],
        ];
    }

    protected function updateProfileAgencies($id)
    {
        $aid  = user_agency_peer::instance()->getAgencyByUser($id);
        $uua  = request::get_array('uua');
        $ufa  = request::get_array('ufa');
        $maid = (int)request::get_array('mother_agency', [-7])[0];

        user_agency_peer::instance()->saveAgency(
            $aid,
            [
                'user_id'        => $id,
                'agency_id'      => $uua['id'],
                'name'           => $uua['name'],
                'city'           => '',
                'foreign_agency' => false,
                'contract_type'  => $uua['contract_type'],
                'type'           => $maid === -1 ? 1 : 0,
                'contract'       => $uua['contract'],
                'country_id'     => geo_peer::UKRAINE,
                'region_id'      => null,
                'city_id'        => $uua['city'],
            ]
        );

        array_map(
            static function ($id) {
                user_agency_peer::instance()->delete_item($id);
            },
            user_agency_peer::instance()->get_list(
                [
                    'user_id'        => $id,
                    'foreign_agency' => true,
                ]
            )
        );

        array_map(
            static function ($key, $ufa) use ($id, $maid) {
                user_agency_peer::instance()->insert(
                    [
                        'user_id'        => $id,
                        'agency_id'      => $ufa['id'],
                        'name'           => $ufa['name'],
                        'city'           => '',
                        'foreign_agency' => true,
                        'type'           => $maid === (int)$key ? 1 : 0,
                        'country_id'     => $ufa['country'],
                        'region_id'      => null,
                        'city_id'        => $ufa['city'],
                    ]
                );
            },
            array_keys($ufa),
            array_values($ufa)
        );

        return null;
    }
}