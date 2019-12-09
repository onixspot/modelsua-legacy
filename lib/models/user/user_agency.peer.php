<?php

class user_agency_peer extends db_peer_postgre
{
    protected $table_name = 'user_agency';
    protected $primary_key = 'id';

    /**
     * @param string $peer
     * @return db_peer|object|user_agency_peer
     */
    public static function instance($peer = 'user_agency_peer')
    {
        return parent::instance($peer);
    }

    public function getAgencyByUser($user)
    {
        $ids = $this->get_list(
            [
                'user_id'        => $user,
                'foreign_agency' => false,
            ]
        );

        if (count($ids) > 0) {
            return $ids[0];
        }

        return null;
    }

    public function saveAgency($id, $agency)
    {
        if ($id !== null) {
            $this->update($agency, ['id' => $id]);

            return (int)$id;
        }

        return $this->insert($agency);
    }
}

