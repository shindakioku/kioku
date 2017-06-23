<?php

namespace Kioku\Http\Providers;

use Kioku\Container\ServiceProvider;

class SessionServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->build(Session::class);
    }
}