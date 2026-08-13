<?php

declare(strict_types=1);

namespace App\Core;

class Controller
{
    public function __construct(
        protected View $view,
        protected Session $session,
        protected Csrf $csrf
    ) {
    }

    protected function render(
        string $view,
        array $data = [],
        string $layout = 'app'
    ): void {
        $this->view->render(
            $view,
            array_merge(
                [
                    'session' => $this->session,
                    'csrf' => $this->csrf,
                ],
                $data
            ),
            $layout
        );
    }
}