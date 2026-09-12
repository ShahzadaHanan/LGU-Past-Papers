<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\View;
use App\Core\Session;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Response;
use App\Services\AlumniTestimonialService;

class AlumniTestimonialController extends Controller
{
    public function __construct(
        View $view, Session $session, Csrf $csrf,
        private Request $request, private Response $response,
        private AlumniTestimonialService $service
    ) {
        parent::__construct($view, $session, $csrf);
    }

    public function index(): void
    {
        $this->render('admin/alumni_testimonials/index', [
            'title' => 'Alumni',
            'items' => $this->service->getAll(),
        ], 'admin');
    }

    public function create(): void
    {
        $this->render('admin/alumni_testimonials/create', ['title' => 'Add Testimonial'], 'admin');
    }

    public function store(): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect('/admin/alumni-testimonials/create');
            return;
        }

        try {
            $this->service->create($this->request->all(), $this->request->file('photo'));
            $this->session->flash('success', 'Testimonial created.');
        } catch (\Exception $e) {
            $this->session->flash('error', $e->getMessage());
        }
        $this->response->redirect('/admin/alumni-testimonials');
    }

    public function edit(int $id): void
    {
        $item = $this->service->getById($id);
        if (!$item) {
            $this->session->flash('error', 'Testimonial not found.');
            $this->response->redirect('/admin/alumni-testimonials');
            return;
        }
        $this->render('admin/alumni_testimonials/edit', ['title' => 'Edit Testimonial', 'item' => $item], 'admin');
    }

    public function update(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect("/admin/alumni-testimonials/{$id}/edit");
            return;
        }

        try {
            $this->service->update($id, $this->request->all(), $this->request->file('photo'));
            $this->session->flash('success', 'Testimonial updated.');
        } catch (\Exception $e) {
            $this->session->flash('error', $e->getMessage());
        }
        $this->response->redirect('/admin/alumni-testimonials');
    }

    public function delete(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect('/admin/alumni-testimonials');
            return;
        }

        $this->service->delete($id);
        $this->session->flash('success', 'Testimonial deleted.');
        $this->response->redirect('/admin/alumni-testimonials');
    }
}
