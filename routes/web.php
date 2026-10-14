<?php

use App\Controllers\HomeController;
use App\Controllers\UserAuthController;
use App\Controllers\Admin\LoginController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\DepartmentController;
use App\Controllers\Admin\SubDepartmentController;
use App\Controllers\Admin\SubjectController;
use App\Controllers\Admin\SiteUserController;
use App\Controllers\Admin\HeroSlideController;
use App\Controllers\Admin\ContentBlockController;
use App\Controllers\Admin\VideoCategoryController;
use App\Controllers\Admin\VideoController;
use App\Controllers\Admin\PaperController;
use App\Controllers\Admin\SiteSettingController;
use App\Controllers\Admin\ClassBookingController;
use App\Controllers\Admin\PaperSubmissionController;
use App\Controllers\Admin\NewsletterSubscriberController;
use App\Controllers\Admin\AlumniTestimonialController;
use App\Controllers\Admin\TestimonialController;
use App\Controllers\Admin\AdminUserController;
use App\Controllers\Admin\AnnouncementController;

$router->get('/', HomeController::class, 'index');
$router->get('/departments', HomeController::class, 'departments');
$router->get('/department/{slug}', HomeController::class, 'department');
$router->get('/department/{deptSlug}/{subSlug}', HomeController::class, 'degree');
$router->get('/paper/{slug}', HomeController::class, 'paper');
$router->get('/lectures', HomeController::class, 'lectures');
$router->get('/lectures/book', HomeController::class, 'bookClassPage');
$router->post('/lectures/book', HomeController::class, 'bookClass');
$router->post('/newsletter/subscribe', HomeController::class, 'subscribeNewsletter');
$router->post('/paper/submit', HomeController::class, 'submitPaper');
$router->get('/about-lgu', HomeController::class, 'aboutLgu');
$router->get('/about-us', HomeController::class, 'aboutUs');
$router->get('/alumni', HomeController::class, 'alumni');
$router->get('/contact-us', HomeController::class, 'contactUs');
$router->get('/fee-structure', HomeController::class, 'feeStructure');
$router->get('/scholarships', HomeController::class, 'scholarships');
$router->get('/roadmaps', HomeController::class, 'roadmaps');
$router->get('/roadmaps/{slug}', HomeController::class, 'roadmapDetail');

// Public site accounts (visitors) — separate from /admin/login.
$router->get('/account/login', UserAuthController::class, 'showLogin');
$router->post('/account/login', UserAuthController::class, 'login');
$router->get('/account/register', UserAuthController::class, 'showRegister');
$router->post('/account/register', UserAuthController::class, 'register');
$router->post('/account/google', UserAuthController::class, 'google');
$router->get('/account/logout', UserAuthController::class, 'logout');

$router->get('/admin/login', LoginController::class, 'index');
$router->post('/admin/login', LoginController::class, 'login');
$router->get('/admin/logout', LoginController::class, 'logout');

$router->get('/admin/dashboard', DashboardController::class, 'index');

// Auth — there is exactly one login implementation (LoginController /
// AuthService); /login and /logout are kept as aliases to /admin/login and
// /admin/logout for anyone with an old bookmark, not a second auth flow.
$router->get('/login', LoginController::class, 'index');
$router->post('/login', LoginController::class, 'login');
$router->get('/logout', LoginController::class, 'logout');

// Departments
$router->get('/admin/departments', DepartmentController::class, 'index');
$router->get('/admin/departments/create', DepartmentController::class, 'create');
$router->post('/admin/departments', DepartmentController::class, 'store');
$router->get('/admin/departments/{id}/edit', DepartmentController::class, 'edit');
$router->post('/admin/departments/{id}', DepartmentController::class, 'update');
$router->post('/admin/departments/{id}/delete', DepartmentController::class, 'delete');

// Sub Departments
$router->get('/admin/sub-departments', SubDepartmentController::class, 'index');
$router->get('/admin/sub-departments/create', SubDepartmentController::class, 'create');
$router->post('/admin/sub-departments', SubDepartmentController::class, 'store');
$router->get('/admin/sub-departments/{id}/edit', SubDepartmentController::class, 'edit');
$router->post('/admin/sub-departments/{id}', SubDepartmentController::class, 'update');
$router->post('/admin/sub-departments/{id}/delete', SubDepartmentController::class, 'delete');

// Subjects — the per-degree course catalogue
$router->get('/admin/subjects', SubjectController::class, 'index');
$router->get('/admin/subjects/create', SubjectController::class, 'create');
$router->post('/admin/subjects', SubjectController::class, 'store');
$router->get('/admin/subjects/{id}/edit', SubjectController::class, 'edit');
$router->post('/admin/subjects/{id}', SubjectController::class, 'update');
$router->post('/admin/subjects/{id}/delete', SubjectController::class, 'delete');

// Registered site users (public visitors) — read-only + delete.
$router->get('/admin/site-users', SiteUserController::class, 'index');
$router->post('/admin/site-users/{id}/delete', SiteUserController::class, 'delete');

$modules = [
    'hero-slides' => HeroSlideController::class,
    'content-blocks' => ContentBlockController::class,
    'video-categories' => VideoCategoryController::class,
    'videos' => VideoController::class,
    'papers' => PaperController::class,
    'site-settings' => SiteSettingController::class,
    'class-bookings' => ClassBookingController::class,
    'paper-submissions' => PaperSubmissionController::class,
    'newsletter-subscribers' => NewsletterSubscriberController::class,
    'alumni-testimonials' => AlumniTestimonialController::class,
    'testimonials' => TestimonialController::class,
    'users' => AdminUserController::class,
    'announcements' => AnnouncementController::class,
];

foreach ($modules as $uri => $controllerClass) {
    $router->get("/admin/{$uri}", $controllerClass, 'index');
    $router->get("/admin/{$uri}/create", $controllerClass, 'create');
    $router->post("/admin/{$uri}", $controllerClass, 'store');
    $router->get("/admin/{$uri}/{id}/edit", $controllerClass, 'edit');
    $router->post("/admin/{$uri}/{id}", $controllerClass, 'update');
    $router->post("/admin/{$uri}/{id}/delete", $controllerClass, 'delete');
}

// Custom routes
$router->get('/admin/class-bookings/export/csv', ClassBookingController::class, 'exportCsv');
$router->post('/admin/class-bookings/{id}/status', ClassBookingController::class, 'updateStatus');

$router->get('/admin/paper-submissions/list/pending', PaperSubmissionController::class, 'pending');
$router->post('/admin/paper-submissions/{id}/approve', PaperSubmissionController::class, 'approve');
$router->post('/admin/paper-submissions/{id}/reject', PaperSubmissionController::class, 'reject');

$router->get('/admin/newsletter-subscribers/export/csv', NewsletterSubscriberController::class, 'exportCsv');
$router->post('/admin/newsletter-subscribers/{id}/toggle', NewsletterSubscriberController::class, 'toggleStatus');

$router->post('/admin/announcements/{id}/toggle', AnnouncementController::class, 'toggleActive');

$router->post('/admin/papers/{id}/notify', PaperController::class, 'notify');
$router->post('/admin/videos/{id}/notify', VideoController::class, 'notify');
$router->post('/admin/announcements/{id}/notify', AnnouncementController::class, 'notify');

$router->get('/newsletter/unsubscribe', HomeController::class, 'unsubscribeNewsletter');

// Protect all /admin routes except the login screen itself with AuthMiddleware
$router->protect(
    '/admin',
    [\App\Middleware\AuthMiddleware::class],
    ['/admin/login']
);

// Admin user management (role/permission editing) is super_admin only —
// RoleMiddleware existed but was never attached to a route before this.
$router->protect(
    '/admin/users',
    [\App\Middleware\RoleMiddleware::class]
);

// Keep the guest-only login pages from being visited while already logged in
$router->protect(
    '/login',
    [\App\Middleware\GuestMiddleware::class]
);
$router->protect(
    '/admin/login',
    [\App\Middleware\GuestMiddleware::class]
);
