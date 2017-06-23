<?php

namespace Kioku\Auth;

use App\Entities\UserEntity;
use Doctrine\ORM\EntityManager;
use Kioku\Helpers\Doctrine;
use Kioku\Http\Cookie\Cookie;
use Symfony\Component\Yaml\Yaml;

class Auth
{
    /**
     * @var User|null
     */
    protected $user;

    /**
     * @var string
     */
    public $entity;

    /**
     * @var EntityManager
     */
    public $em;

    /**
     * @var Cookie
     */
    protected $cookie;

    /**
     * @var Yaml
     */
    protected $yaml;

    /**
     * Auth constructor.
     */
    public function __construct()
    {
        $this->cookie = new Cookie();
        $this->yaml = new Yaml();
        $this->em = (new Doctrine())->init();
        $this->entity = $this->yaml->parse(file_get_contents(__DIR__.'/../../config/auth.yaml'))['entity'];
        $this->user = $this->check() ? $this->em->find($this->entity, $this->cookie->get('user_id')) : null;
    }

    /**
     * @param array $data
     * @return Attempt
     */
    public function attempt(array $data): Attempt
    {
        return new Attempt($data, $this);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function authById(int $id): bool
    {
        $user = $this->em->find($this->entity, $id);

        if (!$user) {
            return false;
        }

        $this->cookie->set('user_id', $user->id)->set('remember_token', $user->remember_token);

        return true;
    }

    public function logout(): void
    {
        $this->cookie->remove('user_id')->remove('remember_token');
    }

    /**
     * @return bool
     */
    public function check(): bool
    {
        if ($this->cookie->has('user_id') && $this->cookie->has('remember_token')) {
            return true;
        }

        return false;
    }

    /**
     * @return UserEntity|null
     */
    public function user()
    {
        return $this->user;
    }

    /**
     * @param string $name
     * @return null
     */
    public function __get(string $name)
    {
        if ($value = $this->user->$name) {
            return $value;
        }

        return null;
    }

    public function __debugInfo()
    {
        return [
            $this->user,
            $this->entity,
        ];
    }
}