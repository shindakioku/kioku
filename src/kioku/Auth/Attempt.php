<?php

namespace Kioku\Auth;

final class Attempt
{
    /**
     * @var array
     */
    private $keys = [];

    /**
     * @var array
     */
    private $values = [];

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * AuthAttempt constructor.
     * @param array $data
     * @param Auth $auth
     */
    public function __construct(array $data, Auth $auth)
    {
        $this->auth = $auth;
        $this->keys = array_keys($data);
        $this->values = array_values($data);
        $this->auth();
    }

    /**
     * @return bool
     */
    private function auth(): bool
    {
        if ($this->auth->check()) {
            return false;
        }

        $user = $this->auth->em->getRepository(
            $this->auth->entity
        )->findOneBy(
            [$this->keys[0] => $this->values[0]]
        );


        if (!$user) {
            return false;
        }

        if (!($token = $this->checkData($user))) {
            return false;
        }

        $user->remember_token = $token;
        $this->auth->em->flush();

        return true;
    }

    /**
     * @param $user
     * @return bool|string
     */
    private function checkData($user)
    {
        $key = $this->keys[1];

        if ('password' == $key) {
            if ($this->checkPassword($this->values[1], $user->password)) {
                $this->set($user->id, ($token = generateToken(20)));

                return $token;
            }
        } else {
            if ($this->values[1] == $user->$key) {
                $this->set($user->id, ($token = generateToken(20)));

                return $token;
            }
        }

        return false;
    }

    /**
     * @param string $data
     * @param string $password
     * @return bool
     */
    private function checkPassword(string $data, string $password): bool
    {
        return password_verify($data, $password) ? true : false;
    }

    /**
     * @param int $id
     * @param string $token
     */
    private function set(int $id, string $token): void
    {
        $cookie = new \Kioku\Http\Cookie\Cookie();

        $cookie->set('user_id', $id)->set('remember_token', $token);
    }
}