<?php

namespace App\Middleware;

final class Middleware
{
    const MIDDLEWARE = [
        'auth' => AuthMiddleware::class,
        'csrf' => CsrfMiddleware::class,
    ];

    const EXCEPT_URLS = [
        '/auth'
    ];
}