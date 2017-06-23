<?php

namespace App\Middleware;

class AuthMiddleware
{
    public function handle($request, callable $next)
    {
        if ( !$request->user ) {
            dd('Не авторизован');
        }

        return $next;
    }
}