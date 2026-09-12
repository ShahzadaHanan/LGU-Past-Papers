<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Services\AuthService;
use App\Core\Response;

class RoleMiddleware
{
    public function __construct(
        private AuthService $auth,
        private Response $response
    ) {
    }

    /**
     * ponytail: Dispatcher::dispatch() calls handle() with no arguments,
     * and Route/Router::protect() carry no per-route parameters, so this
     * can't yet be wired per-route with a caller-supplied role. Ceiling:
     * one hardcoded gate (super_admin). Upgrade path if a second
     * restricted role shows up — extend Route::$middleware to carry
     * "Class:param" entries and have Dispatcher split/pass them.
     */
    public function handle(
        string $role = 'super_admin'
    ): void {

        if ($this->auth->hasRole($role)) {
            return;
        }

        $this->response->redirect(
            '/admin/dashboard'
        );

        exit;
    }
}