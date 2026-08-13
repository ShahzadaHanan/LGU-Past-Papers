<?php

declare(strict_types=1);

namespace App\Core;

class View
{
    public function render(
        string $view,
        array $data = [],
        string $layout = 'app'
    ): void {

        extract($data);

        $viewFile = basePath(
            "app/views/{$view}.php"
        );

        $layoutFile = basePath(
            "app/views/layouts/{$layout}.php"
        );

        if (!file_exists($viewFile)) {
            throw new \Exception(
                "View {$view} not found."
            );
        }

        ob_start();

        require $viewFile;

        $content = ob_get_clean();

        require $layoutFile;
    }
}