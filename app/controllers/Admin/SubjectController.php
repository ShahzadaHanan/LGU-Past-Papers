<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\View;
use App\Services\SubDepartmentService;
use App\Services\SubjectService;

class SubjectController extends Controller
{
    public function __construct(
        View $view,
        Session $session,
        Csrf $csrf,
        private SubjectService $service,
        private SubDepartmentService $subDepartments,
        private Request $request,
        private Response $response
    ) {
        parent::__construct($view, $session, $csrf);
    }

    public function index(): void
    {
        $subDepartmentId = (int) $this->request->input('degree', 0);
        $search = trim((string) $this->request->input('search', ''));
        $page = max(1, (int) $this->request->input('page', 1));

        $this->render(
            'admin/subjects/index',
            [
                'title' => 'Subjects',
                'subjects' => $this->service->paginate($subDepartmentId, $search, $page),
                'total' => $this->service->total($subDepartmentId, $search),
                'subDepartments' => $this->subDepartments->getAllSubDepartments(),
                'selectedSubDepartment' => $subDepartmentId,
                'search' => $search,
                'page' => $page,
            ],
            'admin'
        );
    }

    public function create(): void
    {
        $this->render(
            'admin/subjects/create',
            [
                'title' => 'Add Subject',
                'subDepartments' => $this->subDepartments->getAllSubDepartments(),
            ],
            'admin'
        );
    }

    public function store(): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect('/admin/subjects/create');
            return;
        }

        if ($this->service->createSubject($this->request->all())) {
            $this->session->flash('success', 'Subject created successfully.');
            $this->response->redirect('/admin/subjects');
            return;
        }

        $this->session->flash('error', 'Unable to create subject.');
        $this->response->redirect('/admin/subjects/create');
    }

    public function edit(int $id): void
    {
        $subject = $this->service->getSubjectById($id);

        if (!$subject) {
            $this->session->flash('error', 'Subject not found.');
            $this->response->redirect('/admin/subjects');
            return;
        }

        $this->render(
            'admin/subjects/edit',
            [
                'title' => 'Edit Subject',
                'subject' => $subject,
                'subDepartments' => $this->subDepartments->getAllSubDepartments(),
            ],
            'admin'
        );
    }

    public function update(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect("/admin/subjects/{$id}/edit");
            return;
        }

        if ($this->service->updateSubject($id, $this->request->all())) {
            $this->session->flash('success', 'Subject updated successfully.');
            $this->response->redirect('/admin/subjects');
            return;
        }

        $this->session->flash('error', 'Unable to update subject.');
        $this->response->redirect("/admin/subjects/{$id}/edit");
    }

    public function delete(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect('/admin/subjects');
            return;
        }

        $this->service->deleteSubject($id);
        $this->session->flash('success', 'Subject deleted successfully.');
        $this->response->redirect('/admin/subjects');
    }
}
