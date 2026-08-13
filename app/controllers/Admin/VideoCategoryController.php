<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\View;
use App\Core\Session;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Response;
use App\Services\VideoCategoryService;

class VideoCategoryController extends Controller
{
    public function __construct(
        View $view, Session $session, Csrf $csrf,
        private Request $request, private Response $response,
        private VideoCategoryService $service
    ) {
        parent::__construct($view, $session, $csrf);
    }

    public function index(): void
    {
        $this->render('admin/video_categories/index', [
            'title' => 'Video Categories',
            'categories' => $this->service->getAllVideoCategories(),
        ], 'admin');
    }

    public function create(): void
    {
        $this->render('admin/video_categories/create', ['title' => 'Add Video Category'], 'admin');
    }

    public function store(): void
    {
        try {
            $this->service->createVideoCategory($this->request->all());
            $this->session->flash('success', 'Video category created.');
        } catch (\Exception $e) {
            $this->session->flash('error', $e->getMessage());
        }
        $this->response->redirect('/admin/video-categories');
    }

    public function edit(int $id): void
    {
        $category = $this->service->getVideoCategoryById($id);
        if (!$category) {
            $this->session->flash('error', 'Category not found.');
            $this->response->redirect('/admin/video-categories');
            return;
        }
        $this->render('admin/video_categories/edit', ['title' => 'Edit Video Category', 'category' => $category], 'admin');
    }

    public function update(int $id): void
    {
        try {
            $this->service->updateVideoCategory($id, $this->request->all());
            $this->session->flash('success', 'Video category updated.');
        } catch (\Exception $e) {
            $this->session->flash('error', $e->getMessage());
        }
        $this->response->redirect('/admin/video-categories');
    }

    public function delete(int $id): void
    {
        $this->service->deleteVideoCategory($id);
        $this->session->flash('success', 'Video category deleted.');
        $this->response->redirect('/admin/video-categories');
    }
}
