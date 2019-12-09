<?php

load::app('modules/api/controller');

load::model('agency');

/**
 * Class api_agencies_action
 *
 * @property array $json
 */
class api_agencies_action extends api_controller
{
    public function getRoutes()
    {
        return [
            '/^\/api\/agencies\/countries\/(?P<country>(\d+))\/cities/' => [[$this, 'getCities'], ['country']],
            '/^\/api\/agencies/'                                        => [[$this, 'findBy'], []],
        ];
    }

    /**
     * @return array
     */
    protected function findBy()
    {
        $findBy = request::get_array('find_by');

        return array_map(
            static function ($id) {
                return agency_peer::instance()->get_item($id);
            },
            agency_peer::instance()->get_list($findBy)
        );
    }

    protected function getCities($country)
    {

    }

//    public function execute2()
//    {
//        parent::execute();
//
//        $args       = [];
//        $requestUri = $_SERVER['REQUEST_URI'];
//        $routes     = $this->getRoutes();
//        $idx        = array_filter(
//            array_keys($routes),
//            function ($pattern) use ($requestUri, &$args) {
//                return preg_match($pattern, $requestUri, $args) > 0;
//            }
//        );
//
//        $this->json['ok'] = call_user_func_array($routes[$idx[0]], [$args]);
//    }
}