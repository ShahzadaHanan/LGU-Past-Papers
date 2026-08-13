<?php

declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(
        string $uri,
        string $controller,
        string $action
    ): void {
        $this->add('GET', $uri, $controller, $action);
    }

    public function post(
        string $uri,
        string $controller,
        string $action
    ): void {
        $this->add('POST', $uri, $controller, $action);
    }

    public function put(
        string $uri,
        string $controller,
        string $action
    ): void {
        $this->add('PUT', $uri, $controller, $action);
    }

    public function patch(
        string $uri,
        string $controller,
        string $action
    ): void {
        $this->add('PATCH', $uri, $controller, $action);
    }

    public function delete(
        string $uri,
        string $controller,
        string $action
    ): void {
        $this->add('DELETE', $uri, $controller, $action);
    }

    private function add(
        string $method,
        string $uri,
        string $controller,
        string $action
    ): void {
        $this->routes[] = new Route(
            $method,
            $uri,
            $controller,
            $action
        );
    }

    public function match(Request $request): ?Route
    {
        $uri = $request->uri();
        $method = $request->method();

        foreach ($this->routes as $route) {
            if ($route->method !== $method) {
                continue;
            }

            if ($route->uri === $uri) {
                return $route;
            }

            $pattern = preg_replace('#\{[^/]+\}#', '([^/]+)', $route->uri);
            $regex = '#^' . $pattern . '$#';

            if (preg_match($regex, $uri, $matches)) {
                array_shift($matches);
                $route->parameters = $matches;

                return $route;
            }
        }

        return null;
    }

    public function routes(): array
    {
        return $this->routes;
    }

    /**
     * Attach middleware to every already-registered route whose URI starts
     * with $prefix, skipping any URI listed in $except.
     */
    public function protect(string $prefix, array $middleware, array $except = []): void
    {
        foreach ($this->routes as $route) {
            if (in_array($route->uri, $except, true)) {
                continue;
            }

            if (strpos($route->uri, $prefix) === 0) {
                $route->middleware = array_merge($route->middleware, $middleware);
            }
        }
    }
}