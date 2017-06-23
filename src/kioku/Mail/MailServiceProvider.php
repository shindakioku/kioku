<?php

namespace Kioku\Mail;

use Kioku\Container\ServiceProvider;

class MailServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->build(Mail::class);
    }
}