<?php

namespace App\Providers;

use Kioku\Route\RouteMaker;

class RouteServiceProvider
{
    /**
     * @var string
     */
    protected $namespace = 'App\Controllers';

    /**
     * @var RouteMaker
     */
    protected $maker;

    /**
     * RouteServiceProvider constructor.
     */
    public function __construct()
    {
        $this->maker = new RouteMaker();
    }

    public function boot(): void
    {
        // It's a general routes
        $this->maker->add('shinda', $this->namespace, 'shinda.php');

        $this->maker->add('/api', $this->namespace.'\\Api', 'api.php');

        //
        $this->maker->start();
    }
}