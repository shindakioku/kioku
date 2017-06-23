<?php

namespace Kioku\Http;

use Kioku\Http\Cookie\Hash;

final class Csrf
{
    /**
     * @var Session
     */
    protected $session;

    /**
     * Csrf constructor.
     */
    public function __construct()
    {
        $this->session = new Session();
    }

    public function set(): void
    {
        $this->session->set('_csrf_token', Hash::encrypt(generateToken(25)));
    }

    /**
     * @return mixed
     */
    public function get()
    {
        return $this->session->get('_csrf_token');
    }
}