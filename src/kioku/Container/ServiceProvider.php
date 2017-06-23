<?php

namespace Kioku\Container;

abstract class ServiceProvider
{
    /**
     * @var ServiceContainer
     */
    public $app;

    /**
     * ServiceProvider constructor.
     */
    public function __construct()
    {
        $this->app = ServiceContainer::instance();
    }
}
