<?php

declare(strict_types=1);

namespace App\Core;
use Closure;
use ReflectionClass;
use ReflectionException;

class Container
{
    private array $bindings = [];
    private array $instances = [];
    private array $singletonFactories = [];

    public function bind(string $abstract, Closure|string $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
    }

    /**
     * Registers the factory but does NOT resolve it yet — resolution
     * happens once, lazily, on first make()/get(). Registering ~40
     * controllers/services/repositories at boot used to construct every
     * one of them on every single request regardless of which route was
     * hit; this makes App::registerCoreServices() cheap again.
     */
    public function singleton(string $abstract, Closure|string $concrete): void
    {
        $this->singletonFactories[$abstract] = $concrete;
        unset($this->instances[$abstract]);
    }

    public function make(string $abstract): mixed
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        if (isset($this->singletonFactories[$abstract])) {
            return $this->instances[$abstract] = $this->resolve(
                $this->singletonFactories[$abstract]
            );
        }

        if (isset($this->bindings[$abstract])) {
            return $this->resolve($this->bindings[$abstract]);
        }

        return $this->resolve($abstract);
    }

    public function get(string $id): mixed
    {
        return $this->make($id);
    }

    private function resolve(Closure|string $concrete): mixed
    {
        if ($concrete instanceof Closure) {
            return $concrete($this);
        }

        try {
            $reflection = new ReflectionClass($concrete);

            if (!$reflection->isInstantiable()) {
                throw new \Exception("Class {$concrete} cannot be instantiated.");
            }

            $constructor = $reflection->getConstructor();

            if ($constructor === null) {
                return new $concrete;
            }

            $dependencies = [];

            foreach ($constructor->getParameters() as $parameter) {
                $type = $parameter->getType();

                if ($type === null || $type->isBuiltin()) {
                    throw new \Exception(
                        "Unable to resolve {$parameter->getName()}."
                    );
                }

                $dependencies[] = $this->make($type->getName());
            }

            return $reflection->newInstanceArgs($dependencies);

        } catch (ReflectionException $e) {
            throw new \Exception($e->getMessage());
        }
    }
}