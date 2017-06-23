<?php

namespace Kioku\Validate;

final class Errors
{
    /**
     * @var array
     */
    public static $errors = [];

    /**
     * @var Errors
     */
    protected static $instance;

    /**
     * @return Errors
     */
    public static function instance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * @param string $key
     * @param array $error
     */
    public function set(string $key, array $error): void
    {
        $this->errors[$key][] = $error;
    }
}