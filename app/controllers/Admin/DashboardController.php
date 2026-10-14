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
        $count = fn(string $table, string $where = ''): int =>
            (int) ($this->db->fetch("SELECT COUNT(*) as total FROM {$table}{$where}")['total'] ?? 0);

        $stats = [
            'departments' => $count('departments', ' WHERE is_active = 1'),
            'degrees' => $count('sub_departments', ' WHERE is_active = 1'),
            'subjects' => $count('subjects', ' WHERE is_active = 1'),
            'papers' => $count('papers'),
            'videos' => $count('videos'),
            'bookings' => $count('class_bookings'),
            'registeredUsers' => $count('users'),
            'admins' => $count('admins'),
            'subscribers' => $count('newsletter_subscribers'),
            'pendingSubmissions' => $count('paper_submissions', " WHERE status = 'pending'"),
            'alumni' => $count('alumni'),
        ];

        // Papers by department — top 8 by volume, for the bar chart.
        $papersByDepartment = $this->db->fetchAll("
            SELECT d.name AS label, COUNT(p.id) AS total
            FROM papers p
            JOIN sub_departments sd ON sd.id = p.sub_department_id
            JOIN departments d ON d.id = sd.department_id
            GROUP BY d.id, d.name
            ORDER BY total DESC
            LIMIT 8
        ");

        // Paper submission review status — for the donut breakdown.
        $submissionStatus = $this->db->fetchAll("
            SELECT status AS label, COUNT(*) AS total FROM paper_submissions GROUP BY status
        ");

        // Class booking status — same donut component, different data.
        $bookingStatus = $this->db->fetchAll("
            SELECT status AS label, COUNT(*) AS total FROM class_bookings GROUP BY status
        ");

        // New visitor registrations per day, last 14 days (oldest first so
        // the bar chart reads left-to-right chronologically). Days with no
        // registrations don't come back from a GROUP BY at all, so the
        // gaps are filled in here rather than leaving holes in the chart.
        $registrationRows = $this->db->fetchAll("
            SELECT DATE(created_at) AS day, COUNT(*) AS total
            FROM users
            WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 13 DAY)
            GROUP BY DATE(created_at)
        ");
        $registrationCounts = [];
        foreach ($registrationRows as $row) {
            $registrationCounts[$row['day']] = (int) $row['total'];
        }
        $registrationsByDay = [];
        for ($i = 13; $i >= 0; $i--) {
            $day = date('Y-m-d', strtotime("-{$i} days"));
            $registrationsByDay[] = [
                'label' => date('M j', strtotime($day)),
                'total' => $registrationCounts[$day] ?? 0,
            ];
        }

        // Most-viewed papers — top 5, for a quick "what's popular" glance.
        $topPapers = $this->db->fetchAll("
            SELECT subject_name, session, exam_type, views_count
            FROM papers
            ORDER BY views_count DESC
            LIMIT 5
        ");

        $this->render(
            'admin/dashboard',
            [
                'auth' => $this->auth,
                'stats' => $stats,
                'papersByDepartment' => $papersByDepartment,
                'submissionStatus' => $submissionStatus,
                'bookingStatus' => $bookingStatus,
                'registrationsByDay' => $registrationsByDay,
                'topPapers' => $topPapers,
            ],
            'admin'
        );
    }
}
