<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\View;
use App\Core\Session;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Response;
use App\Services\SiteSettingService;

class SiteSettingController extends Controller
{
    public function __construct(
        View $view, Session $session, Csrf $csrf,
        private Request $request, private Response $response,
        private SiteSettingService $service
    ) {
        parent::__construct($view, $session, $csrf);
    }

    public function index(): void
    {
        $this->render('admin/site_settings/index', [
            'title' => 'Site Settings',
            'settings' => $this->service->getAllSiteSettings(),
        ], 'admin');
    }

    public function create(): void
    {
        $this->render('admin/site_settings/create', ['title' => 'Add Setting'], 'admin');
    }

    public function store(): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect('/admin/site-settings/create');
            return;
        }

        try {
            $this->service->createSiteSetting($this->request->all());
            $this->session->flash('success', 'Setting created.');
        } catch (\Exception $e) {
            $this->session->flash('error', $e->getMessage());
        }
        $this->response->redirect('/admin/site-settings');
    }

    public function edit(int $id): void
    {
        $setting = $this->service->getSiteSettingById($id);
        if (!$setting) {
            $this->session->flash('error', 'Setting not found.');
            $this->response->redirect('/admin/site-settings');
            return;
        }
        $this->render('admin/site_settings/edit', ['title' => 'Edit Setting', 'setting' => $setting], 'admin');
    }

    public function update(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect("/admin/site-settings/{$id}/edit");
            return;
        }

        try {
            $this->service->updateSiteSetting($id, $this->request->all());
            $this->session->flash('success', 'Setting updated.');
        } catch (\Exception $e) {
            $this->session->flash('error', $e->getMessage());
        }
        $this->response->redirect('/admin/site-settings');
    }

    public function delete(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect('/admin/site-settings');
            return;
        }

        $this->service->deleteSiteSetting($id);
        $this->session->flash('success', 'Setting deleted.');
        $this->response->redirect('/admin/site-settings');
    }
}
