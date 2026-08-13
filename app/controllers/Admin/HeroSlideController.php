<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\View;
use App\Core\Session;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Response;
use App\Services\HeroSlideService;

class HeroSlideController extends Controller
{
    public function __construct(
        View $view, Session $session, Csrf $csrf,
        private Request $request, private Response $response,
        private HeroSlideService $service
    ) {
        parent::__construct($view, $session, $csrf);
    }

    public function index(): void
    {
        $this->render('admin/hero_slides/index', [
            'title' => 'Hero Slides',
            'slides' => $this->service->getAllHeroSlides(),
        ], 'admin');
    }

    public function create(): void
    {
        $this->render('admin/hero_slides/create', ['title' => 'Add Hero Slide'], 'admin');
    }

    public function store(): void
    {
        try {
            $this->service->createHeroSlide($this->request->all(), $this->request->file('image'));
            $this->session->flash('success', 'Hero slide created.');
        } catch (\Exception $e) {
            $this->session->flash('error', $e->getMessage());
        }
        $this->response->redirect('/admin/hero-slides');
    }

    public function edit(int $id): void
    {
        $slide = $this->service->getHeroSlideById($id);
        if (!$slide) {
            $this->session->flash('error', 'Slide not found.');
            $this->response->redirect('/admin/hero-slides');
            return;
        }
        $this->render('admin/hero_slides/edit', ['title' => 'Edit Hero Slide', 'slide' => $slide], 'admin');
    }

    public function update(int $id): void
    {
        try {
            $this->service->updateHeroSlide($id, $this->request->all(), $this->request->file('image'));
            $this->session->flash('success', 'Hero slide updated.');
        } catch (\Exception $e) {
            $this->session->flash('error', $e->getMessage());
        }
        $this->response->redirect('/admin/hero-slides');
    }

    public function delete(int $id): void
    {
        $this->service->deleteHeroSlide($id);
        $this->session->flash('success', 'Hero slide deleted.');
        $this->response->redirect('/admin/hero-slides');
    }
}
