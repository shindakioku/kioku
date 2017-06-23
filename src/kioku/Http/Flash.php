<?php

namespace Kioku\Http;

class Flash
{
    /**
     * @var Session
     */
    protected $session;

    /**
     * Flash constructor.
     */
    public function __construct()
    {
        $this->session = new Session();
    }

    /**
     * @param array $data
     */
    public function set(array $data = []): void
    {
        if (count($data)) {
            foreach ($data as $k => $v) {
                $this->session->set($k, $v);
            }
        }
    }

    /**
     * @param string $key
     * @return bool | string
     */
    public function get(string $key)
    {
        $value = false;

        if ($this->session->has($key)) {
            $value = $this->session->get($key);

            $this->session->remove($key);
        }

        return $value;
    }
}