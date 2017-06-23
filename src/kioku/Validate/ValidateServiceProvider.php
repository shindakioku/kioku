<?php

namespace Kioku\Validate;

use Kioku\Container\ServiceProvider;

class ValidateServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->build(Validate::class);
    }
}