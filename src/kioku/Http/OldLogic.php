<?php

namespace Kioku\Http;

final class OldLogic
{
    /**
     * @var Session
     */
    protected static $session;

    /**
     * OldLogic constructor.
     */
    public function __construct()
    {
        static::$session = new Session();
    }

    /**
     * @param array $data
     */
    public static function save(array $data)
    {
        unset($data['_csrf_token']);

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    if (0 != strlen($v)) {
                        static::$session->set($key.'.'.$k, $v);
                    }
                }
            } else {
                if (0 != strlen($value)) {
                    static::$session->set($key, $value);
                }
            }

        }
    }

    /**
     * @param string $key
     * @param $default
     * @return mixed
     */
    public static function old(string $key, $default)
    {
        if (static::$session->has($key)) {
            $value = static::$session->get($key);
            static::$session->remove($key);

            return $value;
        }

        return $default;
    }
}