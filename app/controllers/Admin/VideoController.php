<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\View;
use App\Core\Session;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Response;
use App\Services\VideoService;
use App\Services\VideoCategoryService;
use App\Services\NewsletterMailerService;
use App\Services\DepartmentService;
use App\Services\SubDepartmentService;

class VideoController extends Controller
{
    public function __construct(
        View $view,
        Session $session,
        Csrf $csrf,
        private Request $request,
        private Response $response,
        private VideoService $service,
        private VideoCategoryService $categoryService,
        private NewsletterMailerService $mailer,
        private DepartmentService $departmentService,
        private SubDepartmentService $subDepartmentService
    ) {
        parent::__construct($view, $session, $csrf);
    }

    public function index(): void
    {
        $this->render('admin/videos/index', [
            'title' => 'Videos',
            'videos' => $this->service->getAllVideos(),
        ], 'admin');
    }

    public function create(): void
    {
        $this->render('admin/videos/create', [
            'title' => 'Add Video',
            'categories' => $this->categoryService->getAllVideoCategories(),
            'departments' => $this->departmentService->getAll(),
            'subDepartments' => $this->subDepartmentService->getAllSubDepartments(),
        ], 'admin');
    }

    public function store(): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect('/admin/videos/create');
            return;
        }

        try {
            $this->service->createVideo($this->request->all());
            $this->session->flash('success', 'Video created.');
        } catch (\Exception $e) {
            $this->session->flash('error', $e->getMessage());
        }
        $this->response->redirect('/admin/videos');
    }

    public function edit(int $id): void
    {
        $video = $this->service->getVideoById($id);
        if (!$video) {
            $this->session->flash('error', 'Video not found.');
            $this->response->redirect('/admin/videos');
            return;
        }
        $this->render('admin/videos/edit', [
            'title' => 'Edit Video',
            'video' => $video,
            'categories' => $this->categoryService->getAllVideoCategories(),
            'departments' => $this->departmentService->getAll(),
            'subDepartments' => $this->subDepartmentService->getAllSubDepartments(),
        ], 'admin');
    }

    public function update(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect("/admin/videos/{$id}/edit");
            return;
        }

        try {
            $this->service->updateVideo($id, $this->request->all());
            $this->session->flash('success', 'Video updated.');
        } catch (\Exception $e) {
            $this->session->flash('error', $e->getMessage());
        }
        $this->response->redirect('/admin/videos');
    }

    public function delete(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect('/admin/videos');
            return;
        }

        $this->service->deleteVideo($id);
        $this->session->flash('success', 'Video deleted.');
        $this->response->redirect('/admin/videos');
    }

    public function notify(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect('/admin/videos');
            return;
        }

        $video = $this->service->getVideoById($id);
        if (!$video) {
            $this->session->flash('error', 'Video not found.');
            $this->response->redirect('/admin/videos');
            return;
        }

        try {
            $result = $this->mailer->notify(
                'video',
                $video->id,
                $video->title,
                $video->description ?: "A new video lecture, \"{$video->title}\", is now available on LGU Hub.",
                rtrim(env('APP_URL', ''), '/') . '/lectures'
            );
            $this->session->flash('success', "Notified {$result['sent']} of {$result['total']} active subscribers.");
        } catch (\Exception $e) {
            $this->session->flash('error', $e->getMessage());
        }

        $this->response->redirect('/admin/videos');
    }
}
