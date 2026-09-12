<?php

declare(strict_types=1);

namespace App\Core;

use App\Services\SeoService;

class Controller
{
    public function __construct(
        protected View $view,
        protected Session $session,
        protected Csrf $csrf
    ) {
    }

    /**
     * Every mutating action (store/update/delete/login) must call this
     * first. Reads the token straight from the superglobals rather than a
     * Request dependency so it works from the one shared base class without
     * widening every controller's constructor. Flashes an error and returns
     * false on mismatch; callers redirect back on false.
     */
    protected function verifyCsrf(): bool
    {
        $token = $_POST['_token'] ?? $_GET['_token'] ?? null;

        if ($this->csrf->validate($token)) {
            return true;
        }

        $this->session->flash(
            'error',
            'Your session expired or the form could not be verified. Please try again.'
        );

        return false;
    }

    /**
     * A real 404 response (right status code, on-brand page) instead of
     * the 302-to-homepage "soft 404" pattern that hides a bad/removed link
     * from the visitor and gets devalued by search engines.
     */
    protected function notFound(): void
    {
        http_response_code(404);

        $this->render('errors/404', ['title' => 'Page Not Found']);
    }

    protected function render(
        string $view,
        array $data = [],
        string $layout = 'app'
    ): void {
        $seo = $data['seo'] ?? new SeoService();

        $shared = [
            'session' => $this->session,
            'csrf' => $this->csrf,
            'seo' => $seo,
        ];

        // Public header/footer need the department dropdown + site
        // settings. Centralized here (instead of header.php/footer.php
        // each running their own `new Database()` query) so the views stay
        // pure templates — admin/auth layouts don't render that nav, so
        // they skip the extra queries entirely.
        if ($layout === 'app') {
            $shared['navDepartments'] = $data['navDepartments'] ?? $this->navDepartments();
            $shared['siteSettings'] = $data['siteSettings'] ?? $this->siteSettings();
            $shared['announcements'] = $data['announcements'] ?? $this->announcements();
        }

        $this->view->render(
            $view,
            array_merge($shared, $data),
            $layout
        );
    }

    private function navDepartments(): array
    {
        static $cache = null;

        if ($cache === null) {
            $cache = (new Database())->fetchAll(
                "SELECT name, slug FROM departments WHERE is_active = 1 ORDER BY display_order ASC, name ASC"
            );
        }

        return $cache;
    }

    private function siteSettings(): array
    {
        static $cache = null;

        if ($cache === null) {
            $rows = (new Database())->fetchAll("SELECT setting_key, setting_value FROM site_settings");

            $cache = [];

            foreach ($rows as $row) {
                $cache[$row['setting_key']] = $row['setting_value'];
            }
        }

        return $cache;
    }

    private function announcements(): array
    {
        static $cache = null;

        if ($cache === null) {
            $cache = (new Database())->fetchAll(
                "SELECT message, link_url, link_label FROM announcements WHERE is_active = 1 ORDER BY display_order ASC, id ASC"
            );
        }

        return $cache;
    }
}