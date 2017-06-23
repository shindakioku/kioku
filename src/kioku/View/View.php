<?php

namespace Kioku\View;

class View
{
    /**
     * @var string
     */
    private $dir = __DIR__.'/../../resources/views/';

    /**
     * @param $path
     * @param array $data
     */
    public function view($path, $data = [])
    {
        $path = false !== strpos($path, '@') ? str_replace('@', '/', $path) : $path;

        return $this->loadView($path, $data);
    }

    /**
     * @param string $path
     * @param $data
     */
    private function loadView(string $path, $data)
    {
        $loader = new \Twig_Loader_Filesystem($this->dir);
        $twig = new \Twig_Environment($loader);
        $this->addFunctions($twig);

        echo $twig->render($path, $data);
    }

    /**
     * @param \Twig_Environment $twig
     */
    private function addFunctions(\Twig_Environment $twig): void
    {
        $old = new \Twig_Function(
            'old', function (string $name, $default = null) {
            return old($name, $default);
        }
        );

        $route = new \Twig_Function(
            'route', function () {
            return route();
        }
        );

        $dd = new \Twig_Function(
            'dd', function () {
            return dd(func_get_args());
        }
        );

        $csrfToken = new \Twig_Function(
            'csrf_token', function () {
            return csrf_token();
        }
        );

        $flash = new \Twig_Function(
            'flash', function (string $key) {
            return flash($key);
        }
        );

        $twig->addFunction($old);
        $twig->addFunction($route);
        $twig->addFunction($dd);
        $twig->addFunction($csrfToken);
        $twig->addFunction($flash);
    }
}