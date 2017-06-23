<?php

namespace Kioku\Helpers;

class Config
{
    const PATH = __DIR__.'/../../config/';

    /**
     * @param string $key
     * @return array|null
     */
    public function get(string $key)
    {
        $result = $this->loadFile($key);
        $keys = $result['keys'];

        return Arr::get($keys, $result['file']);
    }

    /**
     * @param string $keys
     * @return mixed
     */
    private function loadFile(string $keys)
    {
        if (!stristr($keys, '.')) {
            return include self::PATH.$keys.'.php';
        }

        return Arr::include(explode('.', $keys), self::PATH);
    }
}