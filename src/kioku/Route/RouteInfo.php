<?php

namespace Kioku\Route;

final class RouteInfo
{
    /**
     * @var string
     * Current url
     */
    public $url;

    /**
     * @var string
     * System url
     */
    public $routeUrl;

    /**
     * @var string
     * Current controller
     */
    public $controller;

    /**
     * @var string
     * Current method of controller
     */
    public $function;

    /**
     * @var string
     */
    public $serverMethod;

    /**
     * @var string | array
     */
    public $routeMethod;

    /**
     * @var string
     */
    public $namespace;

    /**
     * @var string | array | null
     */
    public $middleware;

    /**
     * @var string
     */
    public $prefix;

    /**
     * @var RouteInfo
     */
    protected static $instance;

    /**
     * @return RouteInfo
     */
    public static function instance(): RouteInfo
    {
        if ( is_null(static::$instance) ) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * @param array $group
     */
    public function addGroupToInfo(array $group): void
    {
        foreach ( $group as $k => $v ) {
            $this->$k = $v;
        }
    }
}