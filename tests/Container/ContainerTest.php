<?php

namespace Kioku\Tests\Container;

use Kioku\Container\ServiceContainer;
use Kioku\Tests\Container\ForTest\BindClass;
use Kioku\Tests\Container\ForTest\ClassA;
use Kioku\Tests\Container\ForTest\ClassB;
use Kioku\Tests\Container\ForTest\ImplementsB;
use Kioku\Tests\Container\ForTest\InterfaceB;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    protected $container;

    public function setUp()
    {
        $this->container = new ServiceContainer();
    }

    public function testBuild()
    {
        $this->container->build(\stdClass::class);
        $this->container->build(ClassA::class);
        $this->container->build(BindClass::class);

        $this->container->build(ImplementsB::class);
        $this->container->build(ClassB::class, function ($app) { // $app - container
            return $app->make(ImplementsB::class);
        });

        $this->assertTrue(
            $this->container->hasBuild(\stdClass::class)
        );

        $this->assertTrue(
            $this->container->hasBuild(ClassA::class)
        );

        $this->assertInstanceOf(
            ClassA::class,
            $this->container->make(
                ClassA::class,
                ['a' => 'string',]
            )
        );

        // See build method for ClassB, there return
        $this->assertInstanceOf(
            ImplementsB::class,
            $this->container->make(ClassB::class)
        );
    }

    public function testBind()
    {
        $this->container->bind(
            InterfaceB::class,
            ImplementsB::class
        );

        $this->assertInstanceOf(
            BindClass::class,
            $this->container->make(BindClass::class)
        );
    }
}