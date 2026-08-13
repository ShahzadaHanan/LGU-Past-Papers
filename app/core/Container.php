<?php

declare(strict_types=1);

namespace App\Core;

<<<<<<< HEAD
use Closure;
use ReflectionClass;
use ReflectionException;

class Container
{
    private array $bindings = [];
    private array $instances = [];

    public function bind(string $abstract, Closure|string $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
    }

    public function singleton(string $abstract, Closure|string $concrete): void
    {
        $this->instances[$abstract] = $this->resolve($concrete);
    }

    public function make(string $abstract): mixed
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
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
=======
class Container
{
    private array $services = [];

    public function set(string $key, mixed $service): void
    {
        $this->services[$key] = $service;
    }

    public function get(string $key): mixed
    {
        return $this->services[$key] ?? null;
    }

    public function has(string $key): bool
    {
        return isset($this->services[$key]);
>>>>>>> 6fe3e775d7907baf387ac1fad4911d33907d3705
    }
}