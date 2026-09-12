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

        $database = $this->container->make(Database::class);

        $navDepartments = $database->fetchAll(
            "SELECT name, slug FROM departments WHERE is_active = 1 ORDER BY display_order ASC, name ASC"
        );

        $settingsRows = $database->fetchAll("SELECT setting_key, setting_value FROM site_settings");
        $siteSettings = [];
        foreach ($settingsRows as $row) {
            $siteSettings[$row['setting_key']] = $row['setting_value'];
        }

        $this->container->make(View::class)->render(
            'errors/404',
            [
                'title' => 'Page Not Found',
                'session' => $this->container->make(Session::class),
                'csrf' => $this->container->make(Csrf::class),
                'seo' => new \App\Services\SeoService(),
                'navDepartments' => $navDepartments,
                'siteSettings' => $siteSettings,
            ],
            'app'
        );

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