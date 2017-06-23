<?php

namespace Kioku\Route\Resource;

class ExceptRoutes
{
    /**
     * ExceptRoutes constructor.
     * @param array $routes
     * @param array $params
     */
    public function __construct(array &$routes, array $params)
    {
        foreach ($routes as $key => $value) {
            foreach ($params as $k) {
                if ($value['name'] == '.'.$k) {
                    unset($routes[$key]);
                }
            }
        }
    }
}