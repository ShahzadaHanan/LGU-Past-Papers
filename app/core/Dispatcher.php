<?php

declare(strict_types=1);

namespace App\Core;

class Dispatcher
{
    public function __construct(
        private Router $router,
        private Container $container
    ) {
    }

    public function dispatch(
    Request $request
): void
{
    $route = $this->router->match($request);

    if (!$route) {

        http_response_code(404);

        echo "404 Page Not Found";

        return;

    }

    foreach ($route->middleware as $middlewareClass) {
        $middleware = $this->container->make($middlewareClass);
        $middleware->handle();
    }

    $controller = $this->container->make(
        $route->controller
    );

    call_user_func_array(
        [$controller, $route->action],
        $route->parameters
    );
}
}