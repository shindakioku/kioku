<?php

namespace Kioku\Http\Providers;

use Kioku\Container\ServiceProvider;

class RequestServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->build(Request::class);
    }
}