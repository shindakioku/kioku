<?php

namespace Kioku\Route;

use Kioku\Container\ServiceContainer;

class RouteDelegate
{
    /**
     * @var RouteInfo
     */
    protected $info;

    /**
     * RouteDelegate constructor.
     */
    public function __construct()
    {
        $this->info = RouteInfo::instance();
    }

    /**
     * @param string $name
     * @param array $params
     * @return string
     * @throws IncorrectRouteName
     */
    public function name(string $name, array $params = []): string
    {
        $container = ServiceContainer::instance();

        $route = $container->make(Route::class)->instance();

        if ($url = $route->$name['url']) {
            if (!$params) {
                return $url;
            }

            $onTime = $this->changeValuesForName($params, $url);
            $result = [];

            // Если в конце url есть слеш, то обрезаем самый первый слеш в строке
            if (strpos($url, '/', -1)) {
                $url = substr($url, 1);
            }

            $url = count(explode('/', $url));
            // Лучше не знать
            $onTime = explode('/', '/'.implode('/', array_unique($onTime)));

            /**
             * Используется для того, если передадут два значения, а в url одна регулярка, не подставить в возвращаемый
             * url все два значения
             * То есть, мы сравниваем длину заданного url в routes и длину того, что у нас получилось, и если разная длина,
             * то мы помещаем в $result все значения из $onTime по индексу! (слишком запутано и не понятно)
             */
            if (count($onTime) != $url) {
                for ($i = 0; $i < $url; $i++) {
                    $result[] = $onTime[$i];
                }
                unset($onTime);
            }

            return implode('/', $onTime ?? $result);
        }

        throw new IncorrectRouteName();
    }

    /**
     * @param array $params
     * @param string $url
     * @return array
     */
    private function changeValuesForName(array $params, string $url): array
    {
        $result = [];

        // Перебираем все переданные значения
        foreach ($params as $key) {
            // Обрезаем url по /
            foreach (explode('/', $url) as $k) {
                // Если это не пустая строка (так как url начинается со слешей)
                if (0 != strlen($key) && 0 != strlen($k)) {
                    // Если эта строка содержит регулярное выражение, то вместо него
                    // пишем значение которое передали в параметры
                    if (stristr($k, '{[')) {
                        $result[] = $key;
                    } else {
                        $result[] = $k;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->info->$name;
    }
}