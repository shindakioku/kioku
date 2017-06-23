<?php

namespace Kioku\Container;

class ServiceContainer
{
    /**
     * @var array
     */
    protected $bindings = [];

    /**
     * @var array
     */
    protected $builds = [];

    /**
     * @var ServiceContainer
     */
    protected static $instance;

    /**
     * @param string $class
     * @param callable $callback
     */
    public function build(string $class, callable $callback = null): void
    {
        if (!is_null($callback)) {
            $object = call_user_func($callback, static::instance());

            $class = '\\' == ($newClassName = substr($class, 0, 1)) ? $newClassName : $class;
            $this->builds[$class]['class'] = $object;
        } else {
            $this->builds[$class] = compact('class');
        }
    }

    /**
     * @param string $interface
     * @param string $class
     */
    public function bind(string $interface, string $class): void
    {
        $this->bindings[$interface]['class'] = $class;
    }

    /**
     * @param string $class
     * @param array $data
     * @return object
     */
    public function make(string $class, array $data = [])
    {
        $bind = $this->getBindForMake($class);

        $reflection = new \ReflectionClass($bind['class']);

        $constructor = $reflection->getConstructor();

        if (is_null($constructor)) {
            return new $reflection->name();
        }

        $parameters = $constructor->getParameters();
        $dependency = $this->getDependencies($parameters, $data);

        return $reflection->newInstanceArgs($dependency);
    }

    /**
     * @return ServiceContainer
     */
    public static function instance(): ServiceContainer
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasBuild(string $key): bool
    {
        return (bool) array_key_exists($key, $this->builds);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasBinding(string $key): bool
    {
        return (bool) array_key_exists($key, $this->bindings);
    }

    /**
     * @param array $parameters
     * @param array $userParams
     * @return array
     */
    private function getDependencies(array $parameters, array $userParams): array
    {
        $result = [];

        foreach ($parameters as $parameter) {
            if (array_key_exists($parameter->name, $userParams)) {
                $result[] = $userParams[$parameter->name];

                continue;
            }

            $result[] = is_null($getClass = $parameter->getClass()) ? $this->resolvePrimitive(
                $parameter
            ) : $this->resolveClass($getClass);
        }

        return $result;
    }

    /**
     * @param string $class
     * @return array
     * @throws \Exception
     */
    private function getBindForMake(string $class): array
    {
        if ($this->hasBinding($class)) {
            $bind = $this->bindings[$class];
        } elseif ($this->hasBuild($class)) {
            $bind = $this->builds[$class];
        } else {
            $this->build($class);

            $bind = $this->builds[$class];
        }

        return $bind;
    }

    /**
     * @param \ReflectionParameter $parameter
     * @return mixed
     */
    private function resolvePrimitive(\ReflectionParameter $parameter)
    {
        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }
    }

    /**
     * @param \ReflectionClass $dependency
     * @return object
     */
    private function resolveClass(\ReflectionClass $dependency)
    {
        $dependency = $dependency->name;

        if ($this->hasBinding($dependency)) {
            return $this->make(
                $this->bindings[$dependency]['class']
            );
        }

        if ($this->hasBuild($dependency)) {
            return $this->make(
                $this->builds[$dependency]['class']
            );
        }
    }
}