<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Database;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\View;

/**
 * Read-only admin view of public site accounts (visitors who registered
 * via /account/register) — separate from Admin\AdminUserController, which
 * manages internal admin-panel staff accounts.
 */
class SiteUserController extends Controller
{
    public function __construct(
        View $view,
        Session $session,
        Csrf $csrf,
        private Database $db,
        private Request $request,
        private Response $response
    ) {
        parent::__construct($view, $session, $csrf);
    }

    public function index(): void
    {
        $search = trim((string) $this->request->input('search', ''));
        $page = max(1, (int) $this->request->input('page', 1));
        $perPage = 20;
        $offset = ($page - 1) * $perPage;

        $where = '';
        $params = [];

        if ($search !== '') {
            $where = ' WHERE name LIKE ? OR email LIKE ?';
            $params = ["%{$search}%", "%{$search}%"];
        }

        $users = $this->db->fetchAll(
            "SELECT * FROM users{$where} ORDER BY created_at DESC LIMIT {$perPage} OFFSET {$offset}",
            $params
        );

        $total = (int) $this->db->fetch("SELECT COUNT(*) AS total FROM users{$where}", $params)['total'];

        $this->render(
            'admin/site_users/index',
            [
                'title' => 'Registered Users',
                'users' => $users,
                'total' => $total,
                'page' => $page,
                'perPage' => $perPage,
                'search' => $search,
            ],
            'admin'
        );
    }

    public function delete(int $id): void
    {
        if (!$this->verifyCsrf()) {
            $this->response->redirect('/admin/site-users');
            return;
        }

        $this->db->execute("DELETE FROM users WHERE id = ?", [$id]);
        $this->session->flash('success', 'User deleted.');
        $this->response->redirect('/admin/site-users');
    }
}
