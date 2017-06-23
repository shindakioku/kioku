<?php

namespace Kioku\Http;

class Session
{
    /**
     * @param string $name
     * @param $value
     */
    public function set(string $name, $value): void
    {
        $_SESSION[$name] = $value;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function remove(string $name): bool
    {
        if ($this->has($name)) {
            unset($_SESSION[$name]);

            return true;
        }

        return false;
    }

    /**
     * @param string $name
     * @return string
     */
    public function get(string $name)
    {
        if ($this->has($name)) {
            return $_SESSION[$name];
        }
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return array_key_exists($name, $_SESSION);
    }
}