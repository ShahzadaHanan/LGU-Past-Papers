<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\View;
use App\Core\Session;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Response;
use App\Services\DepartmentService;

class DepartmentController extends Controller
{
    public function __construct(
        View $view,
        Session $session,
        Csrf $csrf,
        private Request $request,
        private Response $response,
        private DepartmentService $service
    ) {
        parent::__construct(
            $view,
            $session,
            $csrf
        );
    }

    public function index(): void
    {
        $search = trim(
            $this->request->input(
                'search',
                ''
            )
        );

        $page = max(
            1,
            (int) $this->request->input(
                'page',
                1
            )
        );

        $departments = $this->service->search(
            $search,
            $page
        );

        $total = $this->service->total(
            $search
        );

        $this->render(
            'admin/departments/index',
            [
                'title' => 'Departments',
                'departments' => $departments,
                'search' => $search,
                'page' => $page,
                'total' => $total
            ],
            'admin'
        );
    }

    public function create(): void
    {
        $this->render(
            'admin/departments/create',
            [
                'title' => 'Create Department'
            ],
            'admin'
        );
    }

    public function store(): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect('/admin/departments/create');
            return;
        }

        try {
            $this->service->create(
                $this->request->all(),
                $_FILES['hero_image'] ?? []
            );

            $this->session->flash(
                'success',
                'Department created successfully.'
            );
        } catch (\Exception $e) {
            $this->session->flash(
                'error',
                $e->getMessage()
            );
        }

        $this->response->redirect(
            '/admin/departments'
        );
    }

    public function edit(int $id): void
    {
        $department = $this->service->find(
            $id
        );

        if (!$department) {
            $this->session->flash(
                'error',
                'Department not found.'
            );

            $this->response->redirect(
                '/admin/departments'
            );

            return;
        }

        $this->render(
            'admin/departments/edit',
            [
                'title' => 'Edit Department',
                'department' => $department
            ],
            'admin'
        );
    }

    public function update(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect("/admin/departments/{$id}/edit");
            return;
        }

        try {
            $this->service->update(
                $id,
                $this->request->all(),
                $_FILES['hero_image'] ?? []
            );

            $this->session->flash(
                'success',
                'Department updated successfully.'
            );
        } catch (\Exception $e) {
            $this->session->flash(
                'error',
                $e->getMessage()
            );
        }

        $this->response->redirect(
            '/admin/departments'
        );
    }

    public function delete(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect('/admin/departments');
            return;
        }

        try {
            $this->service->delete(
                $id
            );

            $this->session->flash(
                'success',
                'Department deleted successfully.'
            );
        } catch (\Exception $e) {
            $this->session->flash(
                'error',
                $e->getMessage()
            );
        }

        $this->response->redirect(
            '/admin/departments'
        );
    }
}