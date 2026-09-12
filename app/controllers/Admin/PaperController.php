<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\View;
use App\Core\Session;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Response;
use App\Services\PaperService;
use App\Services\SubDepartmentService;
use App\Services\NewsletterMailerService;

class PaperController extends Controller
{
    public function __construct(
        View $view,
        Session $session,
        Csrf $csrf,
        private Request $request,
        private Response $response,
        private PaperService $service,
        private SubDepartmentService $subDepartmentService,
        private NewsletterMailerService $mailer
    ) {
        parent::__construct($view, $session, $csrf);
    }

    public function index(): void
    {
        $search = trim((string) $this->request->input('search', ''));
        $page = max(1, (int) $this->request->input('page', 1));

        $this->render('admin/papers/index', [
            'title' => 'Papers',
            'papers' => $this->service->search($search, $page),
            'search' => $search,
            'page' => $page,
            'total' => $this->service->total($search),
        ], 'admin');
    }

    public function create(): void
    {
        $this->render('admin/papers/create', [
            'title' => 'Add Paper',
            'subDepartments' => $this->subDepartmentService->getAllSubDepartments(),
        ], 'admin');
    }

    public function store(): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect('/admin/papers/create');
            return;
        }

        try {
            $this->service->createPaper(
                $this->request->all(),
                $this->request->file('paper_image'),
                $this->request->file('solution_image'),
                $this->request->file('download_file')
            );
            $this->session->flash('success', 'Paper created successfully.');
        } catch (\Exception $e) {
            $this->session->flash('error', $e->getMessage());
        }

        $this->response->redirect('/admin/papers');
    }

    public function edit(int $id): void
    {
        $paper = $this->service->getPaperById($id);

        if (!$paper) {
            $this->session->flash('error', 'Paper not found.');
            $this->response->redirect('/admin/papers');
            return;
        }

        $this->render('admin/papers/edit', [
            'title' => 'Edit Paper',
            'paper' => $paper,
            'subDepartments' => $this->subDepartmentService->getAllSubDepartments(),
        ], 'admin');
    }

    public function update(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect("/admin/papers/{$id}/edit");
            return;
        }

        try {
            $this->service->updatePaper(
                $id,
                $this->request->all(),
                $this->request->file('paper_image'),
                $this->request->file('solution_image'),
                $this->request->file('download_file')
            );
            $this->session->flash('success', 'Paper updated successfully.');
        } catch (\Exception $e) {
            $this->session->flash('error', $e->getMessage());
        }

        $this->response->redirect('/admin/papers');
    }

    public function delete(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect('/admin/papers');
            return;
        }

        $this->service->deletePaper($id);
        $this->session->flash('success', 'Paper deleted successfully.');
        $this->response->redirect('/admin/papers');
    }

    public function notify(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect('/admin/papers');
            return;
        }

        $paper = $this->service->getPaperById($id);
        if (!$paper) {
            $this->session->flash('error', 'Paper not found.');
            $this->response->redirect('/admin/papers');
            return;
        }

        try {
            $result = $this->mailer->notify(
                'paper',
                $paper->id,
                $paper->subject_name,
                "A new {$paper->exam_type} paper for {$paper->subject_name} ({$paper->session}) has just been added to LGU Hub.",
                rtrim(env('APP_URL', ''), '/') . '/paper/' . $paper->slug
            );
            $this->session->flash('success', "Notified {$result['sent']} of {$result['total']} active subscribers.");
        } catch (\Exception $e) {
            $this->session->flash('error', $e->getMessage());
        }

        $this->response->redirect('/admin/papers');
    }
}
