<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Services\AuthService;
use App\Core\Response;

class AuthMiddleware
{
    public function __construct(
        private AuthService $auth,
        private Response $response
    ) {
    }

    public function handle(): void
    {
        if ($this->auth->check() && $this->auth->checkIdleTimeout()) {
            return;
        }

        $this->response->redirect('/admin/login');

        exit;
    }
}