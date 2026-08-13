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

class PaperSubmissionController extends Controller
{
    public function __construct(
        View $view, Session $session, Csrf $csrf,
        private Request $request, private Response $response,
        private PaperSubmissionService $service
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
        $this->render('admin/paper_submissions/edit', ['title' => 'Submission Details', 'item' => $item], 'admin');
    }

    public function delete(int $id): void
    {
        $this->service->delete($id);
        $this->session->flash('success', 'Submission deleted.');
        $this->response->redirect('/admin/paper-submissions');
    }

    public function approve(int $id): void
    {
        $this->service->approve($id);
        $this->session->flash('success', 'Submission approved. Add the paper under Papers using the submitted image.');
        $this->response->redirect('/admin/paper-submissions');
    }

    public function reject(int $id): void
    {
        $this->service->reject($id);
        $this->session->flash('success', 'Submission rejected.');
        $this->response->redirect('/admin/paper-submissions');
    }
}
