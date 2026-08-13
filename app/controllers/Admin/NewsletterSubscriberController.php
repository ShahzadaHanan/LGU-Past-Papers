<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\View;
use App\Core\Session;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Response;
use App\Services\NewsletterSubscriberService;

class NewsletterSubscriberController extends Controller
{
    public function __construct(
        View $view, Session $session, Csrf $csrf,
        private Request $request, private Response $response,
        private NewsletterSubscriberService $service
    ) {
        parent::__construct($view, $session, $csrf);
    }

    public function index(): void
    {
        $this->render('admin/newsletter_subscribers/index', [
            'title' => 'Newsletter Subscribers',
            'items' => $this->service->getAll(),
        ], 'admin');
    }

    public function create(): void
    {
        $this->render('admin/newsletter_subscribers/create', ['title' => 'Add Subscriber'], 'admin');
    }

    public function store(): void
    {
        $data = $this->request->all();
        $data['is_verified'] = isset($data['is_verified']) ? 1 : 0;
        try {
            $this->service->create($data);
            $this->session->flash('success', 'Subscriber added.');
        } catch (\Exception $e) {
            $this->session->flash('error', $e->getMessage());
        }
        $this->response->redirect('/admin/newsletter-subscribers');
    }

    public function edit(int $id): void
    {
        $item = $this->service->getById($id);
        if (!$item) {
            $this->session->flash('error', 'Subscriber not found.');
            $this->response->redirect('/admin/newsletter-subscribers');
            return;
        }
        $this->render('admin/newsletter_subscribers/edit', ['title' => 'Edit Subscriber', 'item' => $item], 'admin');
    }

    public function update(int $id): void
    {
        $data = $this->request->all();
        $data['is_verified'] = isset($data['is_verified']) ? 1 : 0;
        try {
            $this->service->update($id, $data);
            $this->session->flash('success', 'Subscriber updated.');
        } catch (\Exception $e) {
            $this->session->flash('error', $e->getMessage());
        }
        $this->response->redirect('/admin/newsletter-subscribers');
    }

    public function delete(int $id): void
    {
        $this->service->delete($id);
        $this->session->flash('success', 'Subscriber deleted.');
        $this->response->redirect('/admin/newsletter-subscribers');
    }

    public function toggleStatus(int $id): void
    {
        $this->service->toggleStatus($id);
        $this->response->redirect('/admin/newsletter-subscribers');
    }

    public function exportCsv(): void
    {
        $items = $this->service->getAll();
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="newsletter_subscribers.csv"');
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Email', 'Verified', 'Subscribed At']);
        foreach ($items as $item) {
            fputcsv($output, [$item->id, $item->email, $item->is_verified ? 'Yes' : 'No', $item->subscribed_at]);
        }
        fclose($output);
        exit;
    }
}
