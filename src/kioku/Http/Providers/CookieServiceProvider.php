<?php

namespace Kioku\Http\Providers;

use Kioku\Container\ServiceProvider;

class CookieServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->bind(
            'Kioku\Http\Cookie\CookieInterface',
            'Kioku\Http\Cookie\Cookie'
        );
    }
}