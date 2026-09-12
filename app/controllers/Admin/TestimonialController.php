<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\View;
use App\Core\Session;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Response;
use App\Services\TestimonialService;

class TestimonialController extends Controller
{
    public function __construct(
        View $view, Session $session, Csrf $csrf,
        private Request $request, private Response $response,
        private TestimonialService $service
    ) {
        parent::__construct($view, $session, $csrf);
    }

    public function index(): void
    {
        $this->render('admin/testimonials/index', [
            'title' => 'Testimonials',
            'items' => $this->service->getAll(),
        ], 'admin');
    }

    public function create(): void
    {
        $this->render('admin/testimonials/create', ['title' => 'Add Testimonial'], 'admin');
    }

    public function store(): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect('/admin/testimonials/create');
            return;
        }

        try {
            $this->service->create($this->request->all(), $this->request->file('photo'));
            $this->session->flash('success', 'Testimonial created.');
        } catch (\Exception $e) {
            $this->session->flash('error', $e->getMessage());
        }
        $this->response->redirect('/admin/testimonials');
    }

    public function edit(int $id): void
    {
        $item = $this->service->getById($id);
        if (!$item) {
            $this->session->flash('error', 'Testimonial not found.');
            $this->response->redirect('/admin/testimonials');
            return;
        }
        $this->render('admin/testimonials/edit', ['title' => 'Edit Testimonial', 'item' => $item], 'admin');
    }

    public function update(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect("/admin/testimonials/{$id}/edit");
            return;
        }

        try {
            $this->service->update($id, $this->request->all(), $this->request->file('photo'));
            $this->session->flash('success', 'Testimonial updated.');
        } catch (\Exception $e) {
            $this->session->flash('error', $e->getMessage());
        }
        $this->response->redirect('/admin/testimonials');
    }

    public function delete(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect('/admin/testimonials');
            return;
        }

        $this->service->delete($id);
        $this->session->flash('success', 'Testimonial deleted.');
        $this->response->redirect('/admin/testimonials');
    }
}
