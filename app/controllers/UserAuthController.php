<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\View;
use App\Services\FileUploadService;
use App\Services\UserAuthService;

class UserAuthController extends Controller
{
    public function __construct(
        View $view,
        Session $session,
        Csrf $csrf,
        private Request $request,
        private Response $response,
        private UserAuthService $auth,
        private FileUploadService $uploads
    ) {
        parent::__construct($view, $session, $csrf);
    }

    /** Where to send the visitor back to after a login/signup submitted
     * from the header dialog (which lives on every page) — falls back to
     * home for the standalone /account/login|register pages. */
    private function redirectTarget(): string
    {
        $target = (string) $this->request->input('redirect_to', '/');

        // Only ever redirect back into our own site — an absolute or
        // protocol-relative value here would be an open-redirect vector.
        if ($target === '' || $target[0] !== '/' || str_starts_with($target, '//')) {
            return '/';
        }

        return $target;
    }

    public function showLogin(): void
    {
        if ($this->auth->check()) {
            $this->response->redirect('/');
            return;
        }

        $this->render('auth/user_login', [
            'title' => 'Login',
            'action' => '/account/login',
        ], 'auth');
    }

    public function login(): void
    {
        $redirectTo = $this->redirectTarget();

        if (!$this->verifyCsrf()) {
            $this->response->redirect($redirectTo);
            return;
        }

        $success = $this->auth->login(
            (string) $this->request->input('email', ''),
            (string) $this->request->input('password', '')
        );

        if ($success) {
            $this->response->redirect($redirectTo);
            return;
        }

        $this->session->flash('error', 'Invalid email or password.');
        $this->response->redirect($redirectTo);
    }

    public function showRegister(): void
    {
        if ($this->auth->check()) {
            $this->response->redirect('/');
            return;
        }

        $this->render('auth/user_register', [
            'title' => 'Create Account',
            'action' => '/account/register',
        ], 'auth');
    }

    public function register(): void
    {
        $redirectTo = $this->redirectTarget();

        if (!$this->verifyCsrf()) {
            $this->response->redirect($redirectTo);
            return;
        }

        try {
            $profileImage = null;
            $file = $this->request->file('profile_image');

            if (!empty($file) && !empty($file['name'])) {
                $profileImage = $this->uploads->image($file, 'user_avatars');
            }

            $this->auth->register(
                (string) $this->request->input('name', ''),
                (string) $this->request->input('email', ''),
                (string) $this->request->input('phone', ''),
                (string) $this->request->input('password', ''),
                (string) $this->request->input('password_confirmation', ''),
                $profileImage
            );

            $this->response->redirect($redirectTo);
        } catch (\Exception $e) {
            $this->session->flash('error', $e->getMessage());
            $this->response->redirect($redirectTo);
        }
    }

    /** Handles the Google Identity Services credential (an ID token JWT)
     * posted from the header dialog's Google button. */
    public function google(): void
    {
        $redirectTo = $this->redirectTarget();

        if (!$this->verifyCsrf()) {
            $this->response->redirect($redirectTo);
            return;
        }

        try {
            $this->auth->loginWithGoogleToken(
                (string) $this->request->input('credential', '')
            );

            $this->response->redirect($redirectTo);
        } catch (\Exception $e) {
            $this->session->flash('error', $e->getMessage());
            $this->response->redirect($redirectTo);
        }
    }

    public function logout(): void
    {
        $this->auth->logout();
        $this->response->redirect($this->redirectTarget());
    }
}
