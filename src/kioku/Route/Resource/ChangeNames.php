<?php

namespace Kioku\Route\Resource;

class ChangeNames
{
    /**
     * ChangeNames constructor.
     * @param array $routes
     * @param array $params
     */
    public function __construct(array &$routes, array $params)
    {
        foreach ($routes as $key => $value) {
            foreach ($params as $k => $v) {
                if ($value['name'] == '.'.$k) {
                    unset($routes[$key]);
                }
            }
        }
    }
}