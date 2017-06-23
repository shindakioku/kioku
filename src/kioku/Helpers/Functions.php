<?php

if (!function_exists('app')) {
    function app()
    {
        return Kioku\Container\ServiceContainer::instance();
    }
}

if (!function_exists('cookie')) {
    function cookie()
    {
        return app()->make(Kioku\Http\Cookie\CookieInterface::class);
    }
}

if (!function_exists('session')) {
    function session()
    {
        return app()->make(Kioku\Http\Session::class);
    }
}

if (!function_exists('old')) {
    function old(string $key, $default = null)
    {
        return Kioku\Http\OldLogic::old($key, $default);
    }
}

if (!function_exists('request')) {
    function request()
    {
        return app()->make(Kioku\Http\Request::class);
    }
}

if (!function_exists('flash')) {
    function flash(string $key)
    {
        return app()->make(Kioku\Http\Flash::class)->get($key);
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token()
    {
        return app()->make(Kioku\Http\Csrf::class)->get();
    }
}

if (!function_exists('config')) {
    function config(string $key = '')
    {
        app()->build(Kioku\Helpers\Config::class);

        return app()->make(Kioku\Helpers\Config::class)->get($key);
    }
}

if (!function_exists('route')) {
    function route()
    {
        return app()->make(Kioku\Route\RouteDelegate::class);
    }
}

if (!function_exists('yaml')) {
    function yaml(string $path)
    {
        return \Symfony\Component\Yaml\Yaml::parse(file_get_contents($path));
    }
}

if (!function_exists('view')) {
    function view(string $path, $data = [])
    {
        return app()->make(Kioku\View\View::class)->view($path, $data);
    }
}

if (!function_exists('validate')) {
    function validate(array $data, array $rules)
    {
        return app()->make(Kioku\Validate\Validate::class)->validate($data, $rules);
    }
}

if (!function_exists('response')) {
    function response()
    {
        return app()->make(Kioku\Http\Response::class);
    }
}

if (!function_exists('generateString')) {
    function generateToken(int $length)
    {
        $chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';
        $numChars = strlen($chars);
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= substr($chars, rand(1, $numChars) - 1, 1);
        }

        return $string;
    }
}

if (!function_exists('dd')) {
    function dd()
    {
        $args = func_get_args();
        call_user_func_array('dump', $args);
        die();
    }
}