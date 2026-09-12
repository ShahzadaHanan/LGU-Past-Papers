<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\View;
use App\Core\Session;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Response;
use App\Services\AnnouncementService;
use App\Services\NewsletterMailerService;

class AnnouncementController extends Controller
{
    public function __construct(
        View $view, Session $session, Csrf $csrf,
        private Request $request, private Response $response,
        private AnnouncementService $service,
        private NewsletterMailerService $mailer
    ) {
        parent::__construct($view, $session, $csrf);
    }

    public function index(): void
    {
        $this->render('admin/announcements/index', [
            'title' => 'News Bar',
            'items' => $this->service->getAllAnnouncements(),
        ], 'admin');
    }

    public function create(): void
    {
        $this->render('admin/announcements/create', ['title' => 'Add Announcement'], 'admin');
    }

    public function store(): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect('/admin/announcements/create');
            return;
        }

        try {
            $this->service->createAnnouncement($this->request->all());
            $this->session->flash('success', 'Announcement created.');
        } catch (\Exception $e) {
            $this->session->flash('error', $e->getMessage());
        }
        $this->response->redirect('/admin/announcements');
    }

    public function edit(int $id): void
    {
        $item = $this->service->getAnnouncementById($id);
        if (!$item) {
            $this->session->flash('error', 'Announcement not found.');
            $this->response->redirect('/admin/announcements');
            return;
        }
        $this->render('admin/announcements/edit', ['title' => 'Edit Announcement', 'item' => $item], 'admin');
    }

    public function update(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect("/admin/announcements/{$id}/edit");
            return;
        }

        try {
            $this->service->updateAnnouncement($id, $this->request->all());
            $this->session->flash('success', 'Announcement updated.');
        } catch (\Exception $e) {
            $this->session->flash('error', $e->getMessage());
        }
        $this->response->redirect('/admin/announcements');
    }

    public function delete(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect('/admin/announcements');
            return;
        }

        $this->service->deleteAnnouncement($id);
        $this->session->flash('success', 'Announcement deleted.');
        $this->response->redirect('/admin/announcements');
    }

    public function toggleActive(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect('/admin/announcements');
            return;
        }

        $this->service->toggleActive($id);
        $this->response->redirect('/admin/announcements');
    }

    public function notify(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect('/admin/announcements');
            return;
        }

        $item = $this->service->getAnnouncementById($id);
        if (!$item) {
            $this->session->flash('error', 'Announcement not found.');
            $this->response->redirect('/admin/announcements');
            return;
        }

        try {
            $result = $this->mailer->notify(
                'announcement',
                $item->id,
                mb_strimwidth(strip_tags($item->message), 0, 80, '…'),
                $item->message,
                $item->link_url ?: rtrim(env('APP_URL', ''), '/') . '/'
            );
            $this->session->flash('success', "Notified {$result['sent']} of {$result['total']} active subscribers.");
        } catch (\Exception $e) {
            $this->session->flash('error', $e->getMessage());
        }

        $this->response->redirect('/admin/announcements');
    }
}
