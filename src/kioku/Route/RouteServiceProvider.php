<?php

namespace Kioku\Route;

use Kioku\Container\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->build(RouteDelegate::class);
    }
}