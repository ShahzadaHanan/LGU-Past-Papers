<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\View;
use App\Core\Session;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Response;
use App\Services\ClassBookingService;

class ClassBookingController extends Controller
{
    public function __construct(
        View $view, Session $session, Csrf $csrf,
        private Request $request, private Response $response,
        private ClassBookingService $service
    ) {
        parent::__construct($view, $session, $csrf);
    }

    public function index(): void
    {
        $this->render('admin/class_bookings/index', [
            'title' => 'Class Bookings',
            'items' => $this->service->getAll(),
        ], 'admin');
    }

    public function create(): void
    {
        $this->render('admin/class_bookings/create', ['title' => 'Add Booking'], 'admin');
    }

    public function store(): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect('/admin/class-bookings/create');
            return;
        }

        try {
            $this->service->create($this->request->all());
            $this->session->flash('success', 'Booking created.');
        } catch (\Exception $e) {
            $this->session->flash('error', $e->getMessage());
        }
        $this->response->redirect('/admin/class-bookings');
    }

    public function edit(int $id): void
    {
        $item = $this->service->getById($id);
        if (!$item) {
            $this->session->flash('error', 'Booking not found.');
            $this->response->redirect('/admin/class-bookings');
            return;
        }
        $this->render('admin/class_bookings/edit', ['title' => 'Edit Booking', 'item' => $item], 'admin');
    }

    public function update(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect("/admin/class-bookings/{$id}/edit");
            return;
        }

        try {
            $this->service->update($id, $this->request->all());
            $this->session->flash('success', 'Booking updated.');
        } catch (\Exception $e) {
            $this->session->flash('error', $e->getMessage());
        }
        $this->response->redirect('/admin/class-bookings');
    }

    public function delete(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect('/admin/class-bookings');
            return;
        }

        $this->service->delete($id);
        $this->session->flash('success', 'Booking deleted.');
        $this->response->redirect('/admin/class-bookings');
    }

    public function updateStatus(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect('/admin/class-bookings');
            return;
        }

        $this->service->updateStatus($id, (string) $this->request->input('status', 'new'));
        $this->session->flash('success', 'Status updated.');
        $this->response->redirect('/admin/class-bookings');
    }

    public function exportCsv(): void
    {
        $items = $this->service->getAll();
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="class_bookings.csv"');
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Name', 'Email', 'WhatsApp', 'Subject', 'Topic', 'Fee Type', 'Status', 'Created At']);
        foreach ($items as $item) {
            fputcsv($output, [$item->id, $item->name, $item->email, $item->whatsapp, $item->subject, $item->topic, $item->fee_type, $item->status, $item->created_at]);
        }
        fclose($output);
        exit;
    }
}
