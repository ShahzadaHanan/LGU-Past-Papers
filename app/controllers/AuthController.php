<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Core\Session;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Response;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function __construct(
        View $view,
        Session $session,
        Csrf $csrf,
        private Request $request,
        private Response $response,
        private AuthService $auth
    ) {
        parent::__construct(
            $view,
            $session,
            $csrf
        );
    }


    public function showLogin(): void
    {
        $this->render(
            'auth/login',
            [
                'title' => 'Admin Login',
                'action' => '/login'
            ],
            'auth'
        );
    }

    public function login(): void
    {
        $email = trim(
            (string) $this->request->input('email')
        );

        $password = $this->request->input('password');

        $success = $this->auth->login(
            $email,
            $password
        );

        if (!$success) {
            $this->session->flash(
                'error',
                'Invalid email or password.'
            );

            $this->render(
                'auth/login',
                [
                    'title' => 'Admin Login',
                    'action' => '/login'
                ],
                'auth'
            );

            return;
        }

        $this->session->flash(
            'success',
            'Welcome back.'
        );

        $this->response->redirect(
            '/admin/dashboard'
        );
    }

    public function logout(): void
    {
        $this->auth->logout();

        $this->session->flash(
            'success',
            'Logged out successfully.'
        );

        $this->response->redirect(
            '/login'
        );
    }

    
}