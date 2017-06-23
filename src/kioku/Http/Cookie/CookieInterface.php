<?php

namespace Kioku\Http\Cookie;

interface CookieInterface
{
    public function set(string $key, $value, int $time = 1): Cookie;

    public function get(string $key);

    public function remove(string $key): Cookie;

    public function has(string $key): bool;

    public function all(): array;
}