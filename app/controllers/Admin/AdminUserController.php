<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\View;
use App\Core\Session;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Response;
use App\Services\AdminUserService;

class AdminUserController extends Controller
{
    public function __construct(
        View $view, Session $session, Csrf $csrf,
        private Request $request, private Response $response,
        private AdminUserService $service
    ) {
        parent::__construct($view, $session, $csrf);
    }

    public function index(): void
    {
        $this->render('admin/admin_users/index', [
            'title' => 'Admin Users',
            'items' => $this->service->getAll(),
        ], 'admin');
    }

    public function create(): void
    {
        $this->render('admin/admin_users/create', ['title' => 'Add Admin User'], 'admin');
    }

    public function store(): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect('/admin/users/create');
            return;
        }

        try {
            $this->service->create($this->request->all());
            $this->session->flash('success', 'Admin user created.');
        } catch (\Exception $e) {
            $this->session->flash('error', $e->getMessage());
        }
        $this->response->redirect('/admin/users');
    }

    public function edit(int $id): void
    {
        $item = $this->service->getById($id);
        if (!$item) {
            $this->session->flash('error', 'Admin user not found.');
            $this->response->redirect('/admin/users');
            return;
        }
        $this->render('admin/admin_users/edit', ['title' => 'Edit Admin User', 'item' => $item], 'admin');
    }

    public function update(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect("/admin/users/{$id}/edit");
            return;
        }

        try {
            $this->service->update($id, $this->request->all());
            $this->session->flash('success', 'Admin user updated.');
        } catch (\Exception $e) {
            $this->session->flash('error', $e->getMessage());
        }
        $this->response->redirect('/admin/users');
    }

    public function delete(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect('/admin/users');
            return;
        }

        $this->service->delete($id);
        $this->session->flash('success', 'Admin user deleted.');
        $this->response->redirect('/admin/users');
    }
}
