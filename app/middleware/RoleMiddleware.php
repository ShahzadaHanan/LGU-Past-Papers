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

    public function handle(
        string $role
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