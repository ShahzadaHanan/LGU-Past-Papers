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

class VideoController extends Controller
{
    public function __construct(
        View $view, 
        Session $session, 
        Csrf $csrf,
        private Request $request, 
        private Response $response,
        private VideoService $service, 
        private VideoCategoryService $categoryService
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
        ], 'admin');
    }

    public function store(): void
    {
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
        ], 'admin');
    }

    public function update(int $id): void
    {
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
        $this->service->deleteVideo($id);
        $this->session->flash('success', 'Video deleted.');
        $this->response->redirect('/admin/videos');
    }
}
