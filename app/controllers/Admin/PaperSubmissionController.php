<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\View;
use App\Core\Session;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Response;
use App\Services\PaperSubmissionService;
use App\Services\DepartmentService;
use App\Services\SubDepartmentService;

class PaperSubmissionController extends Controller
{
    public function __construct(
        View $view, Session $session, Csrf $csrf,
        private Request $request, private Response $response,
        private PaperSubmissionService $service,
        private DepartmentService $departmentService,
        private SubDepartmentService $subDepartmentService
    ) {
        parent::__construct($view, $session, $csrf);
    }

    public function index(): void
    {
        $this->render('admin/paper_submissions/index', [
            'title' => 'Paper Submissions',
            'items' => $this->service->getAll(),
        ], 'admin');
    }

    public function pending(): void
    {
        $this->render('admin/paper_submissions/index', [
            'title' => 'Pending Submissions',
            'items' => $this->service->getPending(),
        ], 'admin');
    }

    public function edit(int $id): void
    {
        $item = $this->service->getById($id);
        if (!$item) {
            $this->session->flash('error', 'Submission not found.');
            $this->response->redirect('/admin/paper-submissions');
            return;
        }
        $this->render('admin/paper_submissions/edit', [
            'title' => 'Submission Details',
            'item' => $item,
            'departments' => $this->departmentService->getAll(),
            'subDepartments' => $this->subDepartmentService->getAllSubDepartments(),
        ], 'admin');
    }

    public function delete(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect('/admin/paper-submissions');
            return;
        }

        $this->service->delete($id);
        $this->session->flash('success', 'Submission deleted.');
        $this->response->redirect('/admin/paper-submissions');
    }

    public function approve(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect("/admin/paper-submissions/{$id}/edit");
            return;
        }

        try {
            $paperId = $this->service->approveAndPublish(
                $id,
                [
                    'sub_department_id' => (int) $this->request->input('sub_department_id'),
                    'exam_type' => (string) $this->request->input('exam_type'),
                    'session' => trim((string) $this->request->input('session')),
                    'subject_name' => trim((string) $this->request->input('subject_name')),
                    'solution_text' => trim((string) $this->request->input('solution_text', '')) ?: null,
                ],
                $this->request->file('solution_image')
            );

            $this->session->flash('success', "Submission approved and published as paper #{$paperId}.");
            $this->response->redirect('/admin/paper-submissions');
        } catch (\Exception $e) {
            $this->session->flash('error', $e->getMessage());
            $this->response->redirect("/admin/paper-submissions/{$id}/edit");
        }
    }

    public function reject(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect('/admin/paper-submissions');
            return;
        }

        $this->service->reject($id);
        $this->session->flash('success', 'Submission rejected.');
        $this->response->redirect('/admin/paper-submissions');
    }
}
