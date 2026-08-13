<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Csrf;
use App\Services\AuthService;
use App\Core\View;
use App\Core\Session;

class LoginController extends Controller
{
    public function __construct(
        View $view,
        Session $session,
        Csrf $csrf,
        private Request $request,
        private AuthService $auth
    ) {
        parent::__construct(
            $view,
            $session,
            $csrf
        );
    }

    public function index(): void
    {
        $this->render(
            'auth/login',
            [
                'title' => 'Admin Login',
                'action' => '/admin/login'
            ],
            'auth'
        );
    }

    public function login(): void
    {
        $success = $this->auth->login(
            $this->request->input('email'),
            $this->request->input('password')
        );

        if ($success) {

            header(
                'Location: /admin/dashboard'
            );

            exit;
        }

        $this->session->flash(
            'error',
            'Invalid credentials.'
        );

        $this->render(
            'auth/login',
            [
                'title' => 'Admin Login',
                'action' => '/admin/login'
            ],
            'auth'
        );
    }

    public function logout(): void
    {
        $this->auth->logout();

        header(
            'Location: /admin/login'
        );

        exit;
    }
}
