<?php

namespace Kioku\Events;

use Kioku\Container\ServiceContainer;

class Event
{
    /**
     * @var string
     * Класс который служит объектом для слушателей
     */
    protected $event;

    /***
     * @var array
     * Все слушатели
     */
    protected $listeners = [];

    /**
     * @var string
     */
    protected $method;

    /**
     * @var ServiceContainer
     */
    protected $container;

    /**
     * Event constructor.
     * @param string $class
     */
    public function __construct(string $class)
    {
        $this->event = $class;
        $this->container = ServiceContainer::instance();
    }

    /**
     * @param string $method
     * @param callable|null $callback
     */
    public function on(string $method, callable $callback = null)
    {
        if (!is_null($callback)) {
            call_user_func($callback, $this);
        }

        $this->method = $method;
    }

    /**
     * @param array $parameters
     */
    public function emit(array $parameters = []): void
    {
        foreach ($this->listeners as $k) {
            call_user_func_array(
                [$this->container->make($k), $this->method], $parameters
            );
        }
    }

    /**
     * @param string $class
     * @param string|array|null $method
     * @param array $parameters
     */
    public function emitListener(string $class, $method = null, array $parameters = []): void
    {
        if (is_array($method)) {
            $this->emitListenerWithMethods($class, $method, $parameters);
        } else {
            call_user_func_array(
                [$this->container->make($class), $method ?? $this->method], $parameters
            );
        }
    }

    /**
     * @param string $class
     * @param array $method
     * @param array $parameters
     */
    private function emitListenerWithMethods(string $class, $method = null, array $parameters = []): void
    {
        foreach ($method as $k => $v) {
            if (is_array($v)) {
                $this->argumentsForMethod($class, $k, $v, $parameters);

                call_user_func_array(
                    [$this->container->make($class), $k], $v
                );
            } else {
                $k = is_string($k) ? $k : $v;
                call_user_func_array(
                    [$this->container->make($class), $k], $parameters
                );
            }
        }
    }

    /**
     * @param string $class
     * @param string $method
     * @param array $arguments
     * @param array $parameters
     */
    private function argumentsForMethod(string $class, string $method, array &$arguments, array $parameters): void
    {
        /**
         * Если количество аргументов которое требует метод не совпадает с количеством переданных аргументов($v)
         * то мы проходим по $parameters и добавляем в $v (переданное количество аргументов).
         * Если совпадает, то мы не добавляем в $v ничего.
         */
        $reflection = new \ReflectionClass($class);

        if (count($parameters) && count($reflection->getMethod($method)->getParameters()) != count($arguments)) {
            if (isset($parameters[1])) {
                $arguments[] = $parameters[1];
            } else {
                foreach ($parameters as $value) {
                    $arguments[] = $value;
                }
            }
        }
    }

    /**
     * @param string $listener
     * @return Event
     */
    public function listener(string $listener): self
    {
        $this->listeners[] = $listener;

        return $this;
    }

    /**
     * @param array $listeners
     * @return Event
     */
    public function listeners(array $listeners): self
    {
        foreach ($listeners as $k) {
            $this->listener($k);
        }

        return $this;
    }
}