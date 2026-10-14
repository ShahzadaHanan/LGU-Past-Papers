<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\View;
use App\Services\DepartmentService;
use App\Services\SubDepartmentService;

class SubDepartmentController extends Controller
{
    public function __construct(
        View $view,
        Session $session,
        Csrf $csrf,
        private SubDepartmentService $service,
        private DepartmentService $departments,
        private Request $request,
        private Response $response
    ) {
        parent::__construct(
            $view,
            $session,
            $csrf
        );
    }

    public function index(): void
    {
        $subDepartments =
            $this->service->getAllSubDepartments();

        $this->render(
            'admin/sub_departments/index',
            [
                'title' => 'Degrees',
                'subDepartments' => $subDepartments
            ],
            'admin'
        );
    }

    public function create(): void
    {
        $departments = $this->departments->getAll();

        $this->render(
            'admin/sub_departments/create',
            [
                'title' => 'Create Degree',
                'departments' => $departments
            ],
            'admin'
        );
    }

    public function store(): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect(
                '/admin/sub-departments/create'
            );

            return;
        }

        $data = $this->request->all();

        if (
            $this->service->createSubDepartment($data)
        ) {
            $this->session->flash(
                'success',
                'Degree created successfully.'
            );

            $this->response->redirect(
                '/admin/sub-departments'
            );

            return;
        }

        $this->session->flash(
            'error',
            'Unable to create degree.'
        );

        $this->response->redirect(
            '/admin/sub-departments/create'
        );
    }

    public function edit(int $id): void
    {
        $subDepartment =
            $this->service->getSubDepartmentById($id);

        if (!$subDepartment) {
            $this->session->flash(
                'error',
                'Degree not found.'
            );

            $this->response->redirect(
                '/admin/sub-departments'
            );

            return;
        }

        $departments = $this->departments->getAll();

        $this->render(
            'admin/sub_departments/edit',
            [
                'title' => 'Edit Degree',
                'subDepartment' => $subDepartment,
                'departments' => $departments
            ],
            'admin'
        );
    }

    public function update(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect(
                "/admin/sub-departments/{$id}/edit"
            );

            return;
        }

        $data = $this->request->all();

        if (
            $this->service->updateSubDepartment(
                $id,
                $data
            )
        ) {
            $this->session->flash(
                'success',
                'Degree updated successfully.'
            );

            $this->response->redirect(
                '/admin/sub-departments'
            );

            return;
        }

        $this->session->flash(
            'error',
            'Unable to update degree.'
        );

        $this->response->redirect(
            "/admin/sub-departments/{$id}/edit"
        );
    }

    public function delete(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect(
                '/admin/sub-departments'
            );

            return;
        }

        $this->service->deleteSubDepartment($id);

        $this->session->flash(
            'success',
            'Degree deleted successfully.'
        );

        $this->response->redirect(
            '/admin/sub-departments'
        );
    }
}
