<?php

namespace Kioku\Http;

use Kioku\Auth\Auth;
use Kioku\Container\ServiceContainer;
use Kioku\Helpers\Arr;
use Kioku\Http\Cookie\CookieInterface;

class Request
{
    /**
     * @var CookieInterface
     */
    public $cookie;

    /**
     * @var Upload
     */
    public $upload;

    /**
     * @var Response
     */
    public $response;

    /**
     * @var \App\Entities\User|null
     */
    public $user;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $container = ServiceContainer::instance();

        $this->cookie = $container->make(CookieInterface::class);
        $this->upload = $container->make(Upload::class);
        $this->response = $container->make(Response::class);
        $this->user = (new Auth())->user();
        (new OldLogic)->save($_POST ?? []);
    }

    /**
     * @param $key
     * @param null $default
     * @return array|null|string
     */
    public function input($key, $default = null)
    {
        if (is_array($key)) {
            return $this->getInputs($key);
        }

        if ($this->hasPost($key)) {
            return $this->post($key);
        }

        return $default;
    }

    /**
     * @param array $keys
     * @return array
     */
    private function getInputs(array $keys): array
    {
        $result = [];

        foreach ($keys as $key) {
            if ($this->hasPost($key)) {
                $result[$key] = $this->post($key);
            } else {
                $result[$key] = null;
            }
        }

        return $result;
    }

    /**
     * @param $key
     * @param null $default
     * @return null|string
     */
    public function fromUrl($key, $default = null)
    {
        if (is_array($key)) {
            return $this->getFromUrl($key);
        }

        if ($this->hasGet($key)) {
            return $this->get($key);
        }

        return $default;
    }

    /**
     * @param array $keys
     * @return array
     */
    private function getFromUrl(array $keys): array
    {
        $result = [];

        foreach ($keys as $key) {
            if ($this->hasGet($key)) {
                $result[$key] = $this->get($key);
            } else {
                $result[$key] = null;
            }
        }

        return $result;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasPost(string $key): bool
    {
        return (bool) !empty($this->post($key));
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasGet(string $key): bool
    {
        return (bool) !empty($this->get($key));
    }

    /**
     * @param string $name
     * @return bool|mixed
     */
    public function header(string $name)
    {
        return $this->response->getHeader($name);
    }

    /**
     * @param string $key
     * @return array|string
     */
    private function post(string $key)
    {
        if (stristr($key, '.')) {
            $keys = explode('.', $key);
            $value = $_POST[array_shift($keys)];
            $value = Arr::get($keys, $value);
        } else {
            $value = $_POST[$key];
        }

        if (is_array($value)) {
            return $value;
        }

        return $this->clean($value);
    }

    /**
     * @param string $key
     * @return string
     */
    private function get(string $key): string
    {
        return $this->clean($_GET[$key]);
    }

    /**
     * @param $data
     * @return string
     */
    private function clean($data): string
    {
        return strip_tags(htmlspecialchars($data));
    }

    /**
     * @param string $name
     * @return null|string
     */
    public function __get(string $name): ?string
    {
        if ($this->hasPost($name)) {
            return $this->input($name);
        }

        if ($this->hasGet($name)) {
            return $this->fromUrl($name);
        }

        return null;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $_POST;
    }
}