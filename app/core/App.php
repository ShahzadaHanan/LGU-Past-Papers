<?php

declare(strict_types=1);

namespace App\Core;

class App
{
    private Container $container;

    public function __construct()
    {
        $this->container = new Container();
    }

    public function boot(): void
    {
        $this->registerCoreServices();
    }

    public function run(): void
    {
        $router = $this->container->make(
            Router::class
        );

        $request = $this->container->make(
            Request::class
        );

        require basePath('routes/web.php');

        // $dispatcher = new Dispatcher(
        //     $router,
        //     $this->container
        // );

        $dispatcher = $this->container->make(Dispatcher::class);

        $dispatcher->dispatch(
            $request
        );
    }

    private function registerCoreServices(): void
    {
        $this->container->singleton(
            Container::class,
            fn() => $this->container
        );

        $this->container->singleton(
            Router::class,
            fn() => new Router($this->container)
        );

        $this->container->singleton(
            Request::class,
            fn() => new Request()
        );

        $this->container->singleton(
            Response::class,
            fn() => new Response()
        );

        $this->container->singleton(
            Session::class,
            fn() => new Session()
        );

        $this->container->singleton(
            Database::class,
            fn() => new Database()
        );

        $this->container->singleton(
            \App\Services\FileUploadService::class,
            fn() => new \App\Services\FileUploadService()
        );

        $this->container->singleton(
            \App\Core\Session::class,
            fn() => new \App\Core\Session()
        );

        $this->container->singleton(
            \App\Services\AuthService::class,
            fn() => new \App\Services\AuthService(
                $this->container->get(
                    \App\Models\Admin::class
                ),
                $this->container->get(
                    \App\Core\Session::class
                )
            )
        );

        $this->container->singleton(
            \App\Controllers\AuthController::class,
            fn() => new \App\Controllers\AuthController(
                $this->container->get(
                    \App\Core\View::class
                ),
                $this->container->get(
                    \App\Core\Session::class
                ),
                $this->container->get(
                    \App\Core\Csrf::class
                ),
                $this->container->get(
                    \App\Core\Request::class
                ),
                $this->container->get(
                    \App\Core\Response::class
                ),
                $this->container->get(
                    \App\Services\AuthService::class
                )
            )
        );



        $this->container->singleton(
            \App\Middleware\AuthMiddleware::class,
            fn() => new \App\Middleware\AuthMiddleware(
                $this->container->get(\App\Services\AuthService::class),
                $this->container->get(\App\Core\Response::class)
            )
        );

        $this->container->singleton(
            \App\Middleware\GuestMiddleware::class,
            fn() => new \App\Middleware\GuestMiddleware(
                $this->container->get(\App\Services\AuthService::class),
                $this->container->get(\App\Core\Response::class)
            )
        );

        $this->container->singleton(
            \App\Middleware\RoleMiddleware::class,
            fn() => new \App\Middleware\RoleMiddleware(
                $this->container->get(\App\Services\AuthService::class),
                $this->container->get(\App\Core\Response::class)
            )
        );

        $this->container->singleton(
            \App\Core\Csrf::class,
            fn() => new \App\Core\Csrf()
        );


        $this->container->singleton(
            \App\Repositories\DepartmentRepository::class,
            fn() => new \App\Repositories\DepartmentRepository(
                $this->container->get(
                    \App\Core\Database::class
                )
            )
        );

        $this->container->singleton(
            \App\Repositories\AdminRepository::class,
            fn() => new \App\Repositories\AdminRepository(
                $this->container->get(
                    \App\Core\Database::class
                )
            )
        );

        // $router->get(
        //     '/admin/departments/edit/{id}',
        //     DepartmentController::class,
        //     'edit'
        // );

        // $router->post(
        //     '/admin/departments/update/{id}',
        //     DepartmentController::class,
        //     'update'
        // );

        // $router->post(
        //     '/admin/departments/delete/{id}',
        //     DepartmentController::class,
        //     'delete'
        // );


        $this->container->singleton(
            \App\Controllers\Admin\LoginController::class,
            fn() => new \App\Controllers\Admin\LoginController(
                $this->container->get(
                    \App\Core\View::class
                ),
                $this->container->get(
                    \App\Core\Session::class
                ),
                $this->container->get(
                    \App\Core\Csrf::class
                ),
                $this->container->get(
                    \App\Core\Request::class
                ),
                $this->container->get(
                    \App\Services\AuthService::class
                )
            )
        );


        $this->container->singleton(
            \App\Controllers\Admin\DashboardController::class,
            fn() => new \App\Controllers\Admin\DashboardController(
                $this->container->get(\App\Core\View::class),
                $this->container->get(\App\Core\Session::class),
                $this->container->get(\App\Core\Csrf::class),
                $this->container->get(\App\Services\AuthService::class),
                $this->container->get(\App\Core\Database::class)
            )
        );


        $this->container->singleton(
            \App\Services\FileUploadService::class,
            fn() => new \App\Services\FileUploadService()
        );

        $this->container->singleton(
            \App\Services\SeoService::class,
            fn() => new \App\Services\SeoService()
        );

        $this->container->singleton(
            \App\Services\CacheService::class,
            fn() => new \App\Services\CacheService()
        );

        $this->container->singleton(
            \App\Services\MailService::class,
            fn() => new \App\Services\MailService()
        );

        $this->container->singleton(
            \App\Services\ActivityLogger::class,
            fn() => new \App\Services\ActivityLogger(
                $this->container->get(
                    \App\Core\Database::class
                )
            )
        );
        $this->container->singleton(
            \App\Repositories\SubDepartmentRepository::class,
            fn() => new \App\Repositories\SubDepartmentRepository(
                $this->container->get(\App\Core\Database::class)
            )
        );

        $this->container->singleton(
            \App\Services\SubDepartmentService::class,
            fn() => new \App\Services\SubDepartmentService(
                $this->container->get(\App\Repositories\SubDepartmentRepository::class)
            )
        );

        $this->container->singleton(
            \App\Controllers\Admin\SubDepartmentController::class,
            fn() => new \App\Controllers\Admin\SubDepartmentController(
                $this->container->get(\App\Core\View::class),
                $this->container->get(\App\Core\Session::class),
                $this->container->get(\App\Core\Csrf::class),
                $this->container->get(\App\Services\SubDepartmentService::class),
                $this->container->get(\App\Services\DepartmentService::class),
                $this->container->get(\App\Core\Request::class),
                $this->container->get(\App\Core\Response::class)
            )
        );


        $modules = [
            'HeroSlide',
            'ContentBlock',
            'VideoCategory',
            'Video',
            'Paper',
            'SiteSetting',
            'ClassBooking',
            'NewsletterSubscriber',
            'AlumniTestimonial'
        ];

        foreach ($modules as $module) {

            $repoClass = "\\App\\Repositories\\{$module}Repository";
            $serviceClass = "\\App\\Services\\{$module}Service";
            $controllerClass = "\\App\\Controllers\\Admin\\{$module}Controller";

            // Repository
            $this->container->singleton(
                $repoClass,
                fn() => new $repoClass(
                    $this->container->get(\App\Core\Database::class)
                )
            );

            // Service
            if ($module === 'Paper' || $module === 'HeroSlide') {

                $this->container->singleton(
                    $serviceClass,
                    fn() => new $serviceClass(
                        $this->container->get($repoClass),
                        $this->container->get(\App\Services\FileUploadService::class)
                    )
                );
            } elseif ($module === 'AlumniTestimonial') {

                $this->container->singleton(
                    $serviceClass,
                    fn() => new $serviceClass(
                        $this->container->get($repoClass),

                        // Replace this with the actual #2 dependency
                        $this->container->get(\App\Services\FileUploadService::class)
                    )
                );
            } else {

                $this->container->singleton(
                    $serviceClass,
                    fn() => new $serviceClass(
                        $this->container->get($repoClass)
                    )
                );
            }

            // Controller
            if ($module === 'Video') {

                $this->container->singleton(
                    $controllerClass,
                    fn() => new $controllerClass(
                        $this->container->get(\App\Core\View::class),
                        $this->container->get(\App\Core\Session::class),
                        $this->container->get(\App\Core\Csrf::class),
                        $this->container->get(\App\Core\Request::class),
                        $this->container->get(\App\Core\Response::class),
                        $this->container->get($serviceClass),
                        $this->container->get(\App\Services\VideoCategoryService::class)
                    )
                );
            } elseif ($module === 'Paper') {

                $this->container->singleton(
                    $controllerClass,
                    fn() => new $controllerClass(
                        $this->container->get(\App\Core\View::class),
                        $this->container->get(\App\Core\Session::class),
                        $this->container->get(\App\Core\Csrf::class),
                        $this->container->get(\App\Core\Request::class),
                        $this->container->get(\App\Core\Response::class),
                        $this->container->get($serviceClass),

                        // 7th dependency goes here
                        $this->container->get(\App\Services\SubDepartmentService::class)
                    )
                );
            } else {

                $this->container->singleton(
                    $controllerClass,
                    fn() => new $controllerClass(
                        $this->container->get(\App\Core\View::class),
                        $this->container->get(\App\Core\Session::class),
                        $this->container->get(\App\Core\Csrf::class),
                        $this->container->get(\App\Core\Request::class),
                        $this->container->get(\App\Core\Response::class),
                        $this->container->get($serviceClass)
                    )
                );
            }
        }

        // PaperSubmission has PaperService dependency
        $this->container->singleton(\App\Repositories\PaperSubmissionRepository::class, fn() => new \App\Repositories\PaperSubmissionRepository($this->container->get(\App\Core\Database::class)));
        $this->container->singleton(\App\Services\PaperSubmissionService::class, fn() => new \App\Services\PaperSubmissionService(
            $this->container->get(\App\Repositories\PaperSubmissionRepository::class),
            $this->container->get(\App\Services\PaperService::class)
        ));

        // $this->container->singleton(\App\Controllers\Admin\PaperSubmissionController::class, fn() => new \App\Controllers\Admin\PaperSubmissionController(
        //     $this->container->get(\App\Services\PaperSubmissionService::class),
        //     $this->container->get(\App\Core\View::class)
        // ));

        $this->container->singleton(
            \App\Controllers\Admin\PaperSubmissionController::class,
            fn() => new \App\Controllers\Admin\PaperSubmissionController(
                $this->container->get(\App\Core\View::class),
                $this->container->get(\App\Core\Session::class),
                $this->container->get(\App\Core\Csrf::class),
                $this->container->get(\App\Core\Request::class),
                $this->container->get(\App\Core\Response::class),
                $this->container->get(\App\Services\PaperSubmissionService::class)
            )
        );

        // AdminUser uses AdminRepository
        $this->container->singleton(\App\Services\AdminUserService::class, fn() => new \App\Services\AdminUserService(
            $this->container->get(\App\Repositories\AdminRepository::class)
        ));

        $this->container->singleton(
            \App\Controllers\Admin\AdminUserController::class,
            fn() => new \App\Controllers\Admin\AdminUserController(
                $this->container->get(\App\Core\View::class),
                $this->container->get(\App\Core\Session::class),
                $this->container->get(\App\Core\Csrf::class),
                $this->container->get(\App\Core\Request::class),
                $this->container->get(\App\Core\Response::class),
                $this->container->get(\App\Services\AdminUserService::class)
            )
        );
    }
}
