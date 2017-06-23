<?php

namespace Kioku\Tests\Event;

use Kioku\Events\Event;
use Kioku\Tests\Event\ForTest\EventClass;
use Kioku\Tests\Event\ForTest\Listener1;
use Kioku\Tests\Event\ForTest\Listener2;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    public function testEmitCommonMethodWithListeners()
    {
        $event = new Event(EventClass::class);

        $event->listeners([Listener1::class, Listener2::class]);
        $event->on('example');
        $this->assertEmpty($event->emit());
    }

    public function testEmitCommonMethodWithListener()
    {
        $event = new Event(EventClass::class);

        $event->listener(Listener1::class)->listener(Listener2::class);
        $event->on('example');
        $this->assertEmpty($event->emit());
    }

    public function testEmitWithParameters()
    {
        $event = new Event(EventClass::class);

        $event->listener(Listener1::class)->listener(Listener2::class);
        $event->on('getString');
        $this->assertEmpty(
            $event->emit(['shinda'])
        );

        $this->expectException(\Error::class);
        $event->emit();
    }

    public function testEmitWithDifferentParameters()
    {
        $event = new Event(EventClass::class);

        $event->listener(Listener1::class)->listener(Listener2::class);
        $this->assertEmpty(
            $event->emitListener(Listener1::class, 'withoutParameters')
        );

        $this->assertEmpty(
            $event->emitListener(Listener1::class, 'withParameters', [true])
        );

        $this->assertEmpty(
            $event->emitListener(Listener1::class, ['withoutParameters', 'example'])
        );

        $this->assertEmpty(
            $event->emitListener(
                Listener2::class, [
                'withTwoParameters' => ['name'],
                'withOneParameter',
            ], [123]
            )
        );

        $this->assertEmpty(
            $event->emitListener(
                Listener2::class, [
                'withTwoParameters' => ['name', 11],
                'withOneParameter',
            ], [123]
            )
        );

        $this->assertEmpty(
            $event->emitListener(
                Listener2::class, [
                'withTwoParameters' => ['name', 2],
                'withOneParameter' => [1],
            ]
            )
        );
    }
}