<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class UserEntity
 * @ORM\Entity
 * @ORM\table(name="users")
 */
class UserEntity
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string $username
     *
     * @ORM\Column(name="username", type="string")
     */
    private $username;

    /**
     * @var string $password
     *
     * @ORM\Column(name="password", type="string")
     */
    private $password;

    /**
     * @var string $remember_token
     *
     * @ORM\Column(name="remember_token", type="string")
     */
    private $remember_token;

    public function __set(string $name, $value)
    {
        $this->$name = $value;

        return $this;
    }

    public function __get(string $name)
    {
        return $this->$name;
    }
}