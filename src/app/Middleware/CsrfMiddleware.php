<?php

namespace App\Middleware;

use Kioku\Http\Cookie\Hash;
use Kioku\Http\TokenIsMatch;

class CsrfMiddleware
{
    /**
     * @param $request
     * @param callable $next
     * @return bool
     * @throws TokenIsMatch
     */
    public function handle($request, callable $next)
    {
        if (($token = $request->_csrf_token)) {
            if (Hash::decrypt(session()->get('_csrf_token')) === Hash::decrypt($token)) {
                return $next;
            }
        }

        throw new TokenIsMatch();
    }
}