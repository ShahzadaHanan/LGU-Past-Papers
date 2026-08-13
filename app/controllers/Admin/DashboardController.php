<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\View;
use App\Core\Session;
use App\Core\Csrf;
use App\Core\Database;
use App\Services\AuthService;

class DashboardController extends Controller
{
    public function __construct(
        View $view,
        Session $session,
        Csrf $csrf,
        private AuthService $auth,
        private Database $db
    ) {
        parent::__construct(
            $view,
            $session,
            $csrf
        );
    }

    public function index(): void
    {
        $stats = [
            'departments' => (int) ($this->db->fetch("SELECT COUNT(*) as total FROM departments")['total'] ?? 0),
            'papers' => (int) ($this->db->fetch("SELECT COUNT(*) as total FROM papers")['total'] ?? 0),
            'videos' => (int) ($this->db->fetch("SELECT COUNT(*) as total FROM videos")['total'] ?? 0),
            'bookings' => (int) ($this->db->fetch("SELECT COUNT(*) as total FROM class_bookings")['total'] ?? 0),
        ];

        $this->render(
            'admin/dashboard',
            [
                'auth' => $this->auth,
                'stats' => $stats
            ],
            'admin'
        );
    }
}