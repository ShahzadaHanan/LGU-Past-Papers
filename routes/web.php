<?php

use App\Controllers\HomeController;
use App\Controllers\Admin\LoginController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\DepartmentController;
use App\Controllers\Admin\SubDepartmentController;
use App\Controllers\Admin\HeroSlideController;
use App\Controllers\Admin\ContentBlockController;
use App\Controllers\Admin\VideoCategoryController;
use App\Controllers\Admin\VideoController;
use App\Controllers\Admin\PaperController;
use App\Controllers\Admin\SiteSettingController;
use App\Controllers\AuthController;
use App\Controllers\Admin\ClassBookingController;
use App\Controllers\Admin\PaperSubmissionController;
use App\Controllers\Admin\NewsletterSubscriberController;
use App\Controllers\Admin\AlumniTestimonialController;
use App\Controllers\Admin\AdminUserController;

$router->get('/', HomeController::class, 'index');
$router->get('/departments', HomeController::class, 'departments');
$router->get('/department/{slug}', HomeController::class, 'department');
$router->get('/department/{deptSlug}/{subSlug}', HomeController::class, 'degree');
$router->get('/paper/{slug}', HomeController::class, 'paper');
$router->get('/lectures', HomeController::class, 'lectures');
$router->post('/lectures/book', HomeController::class, 'bookClass');
$router->post('/newsletter/subscribe', HomeController::class, 'subscribeNewsletter');
$router->post('/paper/submit', HomeController::class, 'submitPaper');
$router->get('/about-lgu', HomeController::class, 'aboutLgu');
$router->get('/about-us', HomeController::class, 'aboutUs');
$router->get('/alumni', HomeController::class, 'alumni');
$router->get('/contact-us', HomeController::class, 'contactUs');

$router->get('/admin/login', LoginController::class, 'index');
$router->post('/admin/login', LoginController::class, 'login');
$router->get('/admin/logout', LoginController::class, 'logout');

$router->get('/admin/dashboard', DashboardController::class, 'index');

// Auth
$router->get('/login', AuthController::class, 'showLogin');
$router->post('/login', AuthController::class, 'login');
$router->post('/logout', AuthController::class, 'logout');

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
    'users' => AdminUserController::class,
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

// Protect all /admin routes except the login screen itself with AuthMiddleware
$router->protect(
    '/admin',
    [\App\Middleware\AuthMiddleware::class],
    ['/admin/login']
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
