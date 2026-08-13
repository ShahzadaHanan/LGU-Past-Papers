<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Services\AuthService;
use App\Core\Response;

class GuestMiddleware
{
    public function __construct(
        private AuthService $auth,
        private Response $response
    ) {
    }

    public function handle(): void
    {
        if (!$this->auth->check()) {
            return;
        }

        $this->response->redirect('/admin/dashboard');

        exit;
    }
}