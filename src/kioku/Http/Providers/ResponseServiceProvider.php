<?php

namespace Kioku\Http\Providers;

use Kioku\Container\ServiceProvider;

class ResponseServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->build(Response::class);
    }
}