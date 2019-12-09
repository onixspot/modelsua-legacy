<?php

include_once __DIR__.'/core/Route.php';

/**
 * Class api_controller
 *
 * @property array json
 */
abstract class api_controller extends frontend_controller
{
    public function execute()
    {
        $this->disable_layout();
        $this->set_renderer('ajax');

        $requestUri = $_SERVER['REQUEST_URI'];
        $route      = $this->resolveRoute($requestUri);
        $this->json = call_user_func_array($route->getTarget(), $route->getArgs());
    }

    /**
     * @param $requestUri
     * @return Route
     */
    final public function resolveRoute($requestUri)
    {
        foreach ($this->getRoutes() as $pattern => $route) {
            if (!preg_match($pattern, $requestUri, $args)) {
                continue;
            }

            $request = new Route();
            $request->setTarget(
                $route[0],
                array_map(
                    static function ($key) use ($args) {
                        return $args[$key];
                    },
                    $route[1]
                )
            );

            return $request;
        }
    }

    /**
     * @return array
     */
    abstract public function getRoutes();
}