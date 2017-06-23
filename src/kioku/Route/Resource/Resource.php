<?php

namespace Kioku\Route\Resource;

use Kioku\Route\Route;

class Resource
{
    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $controller;

    /**
     * @var Route
     */
    protected $route;

    /**
     * @var array
     */
    protected $addRoutes = [
        [
            'method' => 'GET',
            'url' => '',
            'controller' => '@index',
            'name' => '.index',
        ],
        [
            'method' => 'GET',
            'url' => '/create',
            'controller' => '@create',
            'name' => '.create',
        ],
        [
            'method' => 'POST',
            'url' => '/',
            'controller' => '@store',
            'name' => '.store',
        ],
        [
            'method' => 'GET',
            'url' => '/{integer}',
            'controller' => '@show',
            'name' => '.show',
        ],
        [
            'method' => 'GET',
            'url' => '/{integer}/edit',
            'controller' => '@edit',
            'name' => '.edit',
        ],
        [
            'method' => 'PUT',
            'url' => '/{integer}',
            'controller' => '@update',
            'name' => '.update',
        ],
        [
            'method' => 'DELETE',
            'url' => '/{integer}',
            'controller' => '@delete',
            'name' => '.delete',
        ],
    ];

    /**
     * Resource constructor.
     * @param string $url
     * @param string $controller
     * @param array $params
     * @param Route $route
     */
    public function __construct(string $url, string $controller, array $params, Route $route)
    {
        $this->url = $url;
        $this->controller = $controller;
        $this->route = $route;

        $this->addRoutes($params);
    }

    /**
     * @param array $params
     */
    private function addRoutes(array $params): void
    {
        if (count($params)) {
            $this->changeParams($params);
        }

        foreach ($this->addRoutes as $key => $value) {
            $this->addWithoutParams($value);
        }
    }

    /**
     * @param array $value
     */
    private function addWithoutParams(array $value): void
    {
        $this->add(
            $value['method'], $this->url.$value['url'], $this->controller.$value['controller'],
            $this->url.$value['name']
        );
    }

    /**
     * @param array $params
     */
    private function changeParams(array $params): void
    {
        if (array_key_exists('names', $params)) {
            new ChangeNames($this->addRoutes, $params['names']);
        }

        if (array_key_exists('except', $params)) {
            new ExceptRoutes($this->addRoutes, $params['except']);
        }

        if (array_key_exists('only', $params)) {
            new OnlyRoutes($this->addRoutes, $params['only']);
        }
    }

    /**
     * @param string $method
     * @param string $url
     * @param string $action
     * @param string $name
     */
    private function add(string $method, string $url, string $action, string $name): void
    {
        $this->route->add(
            $method, $url, $action
        )->name($name);
    }
}