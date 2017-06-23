<?php

namespace Kioku\Helpers;

class Arr
{
    /**
     * @param array $keys
     * @param $value
     * @return mixed
     */
    public static function get(array $keys, $value)
    {
        foreach ($keys as $k) {
            $value = $value[$k];
        }

        return $value;
    }

    /**
     * @param array $keys
     * @param string $path
     * @return array
     */
    public static function include (array $keys, string $path): array
    {
        $dir = null;

        foreach ($keys as $k => $v) {
            if (is_dir($path.$v)) {
                $dir = $v.'/';
                unset($keys[$k]);
            }

            if (is_dir($path.$dir.$v)) {
                $dir .= $v.'/';
                unset($keys[$k]);
            }
        }

        return [
            'file' => include $path.$dir.array_shift($keys).'.php',
            'keys' => $keys,
        ];
    }

    /**
     * @param string $path
     * @return bool
     */
    public static function isDir(string $path): bool
    {
        return is_dir($path);
    }

    /**
     * @param string $class
     * @return bool
     */
    public static function hasFile(string $class): bool
    {
        return file_exists($class);
    }

    /**
     * @param string $path
     * @return bool
     */
    public static function hasClass(string $path): bool
    {
        return class_exists($path);
    }

    /**
     * @param string $key
     * @param array $data
     * @return bool
     */
    public static function has(string $key, array $data): bool
    {
        return array_key_exists($key, $data);
    }
}