<?php

namespace Kioku\View;

use Kioku\Container\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->build(View::class);
    }
}