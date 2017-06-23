<?php

namespace Kioku\Http\Cookie;

class Cookie implements CookieInterface
{
    /**
     * @var array
     */
    protected $cookies = [];

    /**
     * Cookie constructor.
     */
    public function __construct()
    {
        $this->cookies = $_COOKIE;
    }

    /**
     * @param string $key
     * @param $value
     * @param int $time
     * @return Cookie
     */
    public function set(string $key, $value, int $time = 1): Cookie
    {
        $value = Hash::encrypt($value);
        setcookie($key, $value, time() + $time * 60 * 60);

        return $this;
    }

    /**
     * @param string $key
     * @return bool|string
     */
    public function get(string $key)
    {
        if ($this->has($key)) {
            return Hash::decrypt($_COOKIE[$key]);
        }

        return false;
    }

    /**
     * @param string $key
     * @return Cookie
     */
    public function remove(string $key): Cookie
    {
        $this->set($key, '', -1);

        return $this;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        if (isset ($_COOKIE[$key])) {
            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->cookies;
    }
}