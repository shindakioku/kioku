<?php

namespace Kioku\Route;

use Kioku\Container\ServiceContainer;

class Middleware
{

    /**
     * Middleware constructor.
     * @param $middleware
     * @param callable $next
     */
    public function __construct($middleware, callable $next)
    {
        if (!is_array($middleware)) {
            $this->init(
                $this->get($middleware), $next
            );
        }

        if (is_array($middleware)) {
            foreach ($middleware as $k) {
                $this->init(
                    $this->get($k), $next
                );
            }
        }
    }

    /**
     * @param string $middleware
     * @param callable $next
     */
    private function init(string $middleware, callable $next)
    {
        $container = ServiceContainer::instance();
        
        call_user_func(
            $container->make($middleware)->handle(
                request(), $next
            )
        );
    }

    /**
     * @param string $name
     * @return mixed
     */
    private function get(string $name)
    {
        if ($middleware = \App\Middleware\Middleware::MIDDLEWARE[$name]) {
            return $middleware;
        }

        throw new \InvalidArgumentException('Incorrect name for middleware');
    }
}