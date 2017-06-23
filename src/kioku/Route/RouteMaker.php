<?php

namespace Kioku\Route;

final class RouteMaker
{
    /**
     * @var array
     */
    protected $services = [];

    /**
     * @var RouteMaker
     */
    protected static $instance;

    /**
     * @return RouteMaker
     */
    public static function instance(): self
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * @param string $prefix
     * @param string $namespace
     * @param string $load
     */
    public function add(string $prefix, string $namespace, string $load): void
    {
        $this->services[$prefix] = [
            'namespace' => $namespace,
            'load' => $load,
        ];
    }

    public function start(): void
    {
        $route = Route::instance();
        compact('route');

        foreach ($this->services as $k => $v) {
            if (substr($_SERVER['REQUEST_URI'], 0, strlen($k)) == $k) {
                $this->load($k, $v);

                return;
            }
        }

        $this->load('', $this->services['shinda']);
    }

    /**
     * @param string $prefix
     * @param array $data
     */
    private function load(string $prefix, array $data): void
    {
        $route = Route::instance();
        compact('route');

        $route->setNamespace($data['namespace']);
        $route->setPrefix($prefix);

        require __DIR__.'/../../routes/'.$data['load'];

        $route->start();
    }
}