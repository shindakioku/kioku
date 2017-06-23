<?php

namespace Kioku\Route;

trait RouteHelper
{
    /**
     * @param bool $uri
     * @return string
     */
    public function getUrl($uri = false): string
    {
        if (false === $uri) {
            $uri = $_SERVER['REQUEST_URI'];
        }

        if (1 != strlen($uri) && '/' == substr($uri, 0, 1)) {
            mb_internal_encoding("UTF-8");
            $uri = $this->replacePreg($uri);

            return mb_substr($uri, 1);
        }

        return $uri;
    }

    /**
     * @param string $uri
     * @param string $currentUrl
     * @return string
     */
    public function removeSlashes(string $uri, string $currentUrl): string
    {
        // Если у текущей ссылки в конце есть слеш
        if (1 != strlen($uri) && '/' == substr($currentUrl, -1)) {
            // Если у заданного роутера нету слеша - добавляем
            if ('/' != substr($uri, -1)) {
                $uri = $uri.'/';
            }
        } elseif (1 != strlen($uri)) { // если у текущей ссылки в конце нету слеша
            // Если у заданного роутера есть слеш - обрезаем
            if ('/' == substr($uri, -1)) {
                mb_internal_encoding("UTF-8");
                $uri = mb_substr($uri, 0, -1);
            }
        }

        return $uri;
    }

    /**
     * @param string $key
     * @return array
     */
    public function parse(string $key): array
    {
        $result = explode('/', $key);

        if (empty($result[0])) {
            unset($result[0]);
        }

        return $result;
    }

    /**
     * @param string $key
     * @param array $patterns
     * @return mixed
     */
    public function replaceUrl(string $key, array $patterns)
    {
        return preg_replace(
            array_keys($patterns), array_values($patterns), $key
        );
    }

    /**
     * @param string $url
     * @return string
     */
    public function removeQuestionMark(string $url): string
    {
        if (stristr($url, '?')) {
            $url = explode('?', $url)[0];
        }

        return $url;
    }

    /**
     * @param $uri
     * @return mixed
     */
    private function replacePreg($uri)
    {
        $a = [];
        $a[0] = '/{/';
        $a[1] = '/}/';

        $b = [];
        $b[0] = '(';
        $b[1] = ')';

        return preg_replace($a, $b, $uri);
    }
}