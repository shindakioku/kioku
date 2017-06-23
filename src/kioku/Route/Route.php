<?php

namespace Kioku\Route;

use Kioku\Route\Resource\Resource;
use Kioku\View\View;

class Route
{
    use RouteHelper;

    /**
     * @var array
     */
    protected $routes = [];

    /**
     * @var array
     */
    protected $names = [];

    /**
     * @var array
     */
    protected $patterns = [
        '{integer}' => '[0-9]+',
        '{string}' => '[a-zA-Z]+',
        '{any}' => '[^/]+',
    ];

    /**
     * @var Route
     */
    protected static $instance;

    /**
     * @var array
     */
    protected $group = [];

    /**
     * @var RouteInfo
     */
    protected $info;

    /**
     * Route constructor.
     */
    public function __construct()
    {
        $this->info = RouteInfo::instance();
        $this->info->url = $this->getUrl();
        $this->info->serverMethod = $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @return Route
     */
    public static function instance(): self
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function start(): void
    {
        $this->checkRoute();
    }

    /**
     * @param $method
     * @param string $url
     * @param $action
     * @param array $middleware
     * @param callable|null $callback
     * @return Route
     */
    public function add($method, string $url, $action, $middleware = [], callable $callback = null): self
    {
        $action = explode('@', $action);
        $url = !is_null($this->info->prefix) ? $this->info->prefix.$url : $url;

        $this->routes[] = [
            'method' => $method,
            'controller' => array_shift($action),
            'function' => array_shift($action),
            'url' => $this->replaceUrl($url, $this->patterns),
            'parsed_url' => $this->parse($url),
            'middleware' => $middleware['middleware'],
            'callback' => $callback,
        ];

        return $this;
    }

    /**
     * @param string $url
     * @param string $controller
     * @param array $params
     */
    public function resource(string $url, string $controller, array $params = []): void
    {
        new Resource($url, $controller, $params, $this);
    }

    /**
     * @param string $name
     */
    public function name(string $name): void
    {
        $route = end($this->routes);

        $this->names[$name]['url'] = $route['url'];
    }

    /**
     * @param array $data
     * @param callable|array|null $callback
     */
    public function group(array $data, $callback = null): void
    {
        $this->group = $data;

        if (!is_null($callback) && !is_array($callback)) {
            call_user_func($callback);
        }
    }

    /**
     * @return mixed|void
     * @throws \Exception
     */
    private function checkRoute()
    {
        $currentUrl = $this->info->url;

        foreach ($this->routes as $k => $v) {
            $uri = $this->getUrl(
                $this->removeSlashes(
                    $v['url'], $currentUrl
                )
            );

            $routesForCurrentUrl = [];
            foreach ($this->routes as $item => $eachRoute) {
                if ($uri == $eachRoute['url']) {
                    $routesForCurrentUrl[] = $eachRoute;
                }
            }

            $currentUrl = $this->removeQuestionMark($currentUrl);

            if (preg_match_all('#^'.$uri.'$#', $currentUrl, $matches, PREG_SET_ORDER)) {
                if (!$this->checkServerMethod($v['method'])) {
                    foreach ($routesForCurrentUrl as $index => $data) {
                        if ($routesForCurrentUrl[$index]['method'] == $v['method']) {
                            unset($routesForCurrentUrl[$index]);
                        }

                        if ($this->checkServerMethod($data['method'])) {
                            $v = $data;
                        }

                        if (1 == count($routesForCurrentUrl) && $v['method'] != $data['method']) {
                            if (!$this->checkServerMethod($data['method'])) {
                                throw new \Exception('Incorrect request method');
                            }
                        }
                    }
                }

                if (count($v['callback'])) {
                    array_shift($matches[0]);
                    return call_user_func_array($v['callback'], $matches[0]);
                }

                $this->setInfo($v);

                break;
            }
        }

        return $this->initController($matches);
    }

    /**
     * @param array $matches
     * @return mixed|void
     */
    private function initController(array $matches = [])
    {
        if (count($matches)) {
            return (new RouteAction())->start(
                $this->info, $matches
            );
        } else {
            return $this->initNotFoundRout();
        }
    }

    private function initNotFoundRout(): void
    {
        echo 404;

        return;
    }

    /**
     * @param $method
     * @return bool
     * @throws RouteException
     */
    private function checkServerMethod($method): bool
    {
        if (is_array($method)) {
            foreach ($method as $k) {
                if ($this->info->serverMethod == $k) {
                    return true;
                }
            }
        } else {
            if ($this->info->serverMethod == $method) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $data
     */
    private function setInfo(array $data): void
    {
        $this->info->routeUrl = $data['parsed_url'];
        $this->info->controller = $data['controller'];
        $this->info->routeMethod = $data['method'];
        $this->info->function = $data['function'];
        $this->info->middleware = $data['middleware'];
        $this->info->addGroupToInfo($this->group);
    }

    /**
     * @param string $namespace
     */
    public function setNamespace(string $namespace)
    {
        $this->info->namespace = $namespace;
    }

    /**
     * @param string $prefix
     */
    public function setPrefix(string $prefix)
    {
        $this->info->prefix = $prefix;
    }

    /**
     * @param string $name
     * @return array|null
     */
    public function __get(string $name)
    {
        if (array_key_exists($name, $this->names)) {
            return $this->names[$name];
        }
    }
}