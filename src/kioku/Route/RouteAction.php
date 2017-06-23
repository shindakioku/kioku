<?php

namespace Kioku\Route;

use Kioku\Container\ServiceContainer;
use Kioku\Helpers\Arr;
use Kioku\Http\Csrf;
use Kioku\Http\Session;

final class RouteAction
{
    /**
     * @var RouteInfo
     */
    protected $info;

    /**
     * @var ServiceContainer
     */
    protected $container;

    /**
     * @param RouteInfo $info
     * @param array $matches
     * @return mixed
     */
    public function start(RouteInfo $info, array $matches)
    {
        $this->container = ServiceContainer::instance();
        $this->info = $info;

        $this->container->build($this->info->controller);

        if (2 >= count($matches[0])) {
            return $this->callClassWithOneParam($matches);
        } else {
            return $this->callClassWithManyParams($matches);
        }
    }

    /**
     * @param array $matches
     * @return mixed
     */
    private function callClassWithOneParam(array $matches)
    {
        $controller = $this->initClass();
        $method = new \ReflectionMethod(
            $controller, $this->info->function
        );

        if (!count($method->getParameters())) {
            return $this->methodWithoutArgs($method, $controller, $matches[0][1]);
        } else {
            $arguments = $this->getArgumentsForMethod($method->getParameters());
            $arguments[] = $matches[0][1];

            return $this->methodWithArgs($method, $controller, $arguments);
        }
    }

    /**
     * @param \ReflectionMethod $method
     * @param $controller
     * @param $arguments
     * @return Middleware|mixed
     */
    private function methodWithoutArgs(\ReflectionMethod $method, $controller, $arguments)
    {
        return $this->checkMiddleware(
            function () use ($method, $controller, $arguments) {
                $method->invoke($controller, $arguments);
            }
        );
    }

    /**
     * @param \ReflectionMethod $method
     * @param $controller
     * @param $arguments
     * @return Middleware|mixed
     */
    private function methodWithArgs(\ReflectionMethod $method, $controller, $arguments)
    {
        return $this->checkMiddleware(
            function () use ($method, $controller, $arguments) {
                $method->invokeArgs($controller, $arguments);
            }
        );
    }

    /**
     * @param callable $next
     * @return Middleware|mixed
     */
    private function checkMiddleware(callable $next)
    {
        $session = $this->container->make(Session::class);

        if ($session->has('_csrf_token')) {
            if ($this->isMethod('POST')) {
                if (!array_key_exists($this->info->url, \App\Middleware\Middleware::EXCEPT_URLS)) {
                    new Middleware('csrf', function () { });
                }
            }
        }

        if ($this->isMethod('POST')) {
            (new Csrf())->set();
        }

        if (count($this->info->middleware)) {
            return new Middleware(
                $this->info->middleware, $next
            );
        }

        return call_user_func($next);
    }

    /**
     * @param array $arguments
     * @return array
     */
    private function getArgumentsForMethod(array $arguments): array
    {
        $result = [];

        foreach ($arguments as $k => $v) {
            if ($v->getClass()) {
                $result[] = $this->container->make($v->getClass()->name);
            }
        }

        return $result;
    }

    /**
     * @param array $matches
     * @return mixed
     */
    private function callClassWithManyParams(array $matches)
    {
        unset($matches[0][0]);
        $class = $this->initClass();

        $method = new \ReflectionMethod(
            $class, $this->info->function
        );

        $arguments = $this->getArgumentsForMethod($method->getParameters());

        foreach ($matches[0] as $k) {
            $arguments[] = stristr($k, '?') ? array_shift(explode('?', $k)) : $k;
        }

        return $this->methodWithArgs($method, $class, $arguments);
    }

    /**
     * @return mixed
     */
    private function initClass()
    {
        $path = null;

        if (0 != strlen($this->info->namespace)) {
            $path .= '\\'.$this->info->namespace;
        }

        if (Arr::hasClass($this->info->controller)) {
            return $this->container->make($this->info->controller);
        }

        return $this->container->make(
            $path.'\\'.$this->info->controller
        );
    }

    /**
     * @param string $method
     * @return bool
     */
    private function isMethod(string $method): bool
    {
        if (is_array($this->info->routeMethod)) {
            if (array_key_exists($method, $this->info->routeMethod)) {
                return true;
            }
        } elseif ('POST' == $this->info->routeMethod) {
            return true;
        }

        return false;
    }
}