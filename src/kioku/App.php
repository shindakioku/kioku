<?php

namespace Kioku;

use App\Providers\RouteServiceProvider;

final class App
{
    public function __construct()
    {
        $this->initProviders();

        app()->make(RouteServiceProvider::class)->boot();
    }

    private function initProviders(): void
    {
        if (count($builds = yaml(__DIR__.'/../config/main.yaml')['providers'])) {
            $builds = explode(' ', $builds);
            foreach ($builds as $k) {
                (new $k())->boot();

                unset($k);
            }
        }
    }
}