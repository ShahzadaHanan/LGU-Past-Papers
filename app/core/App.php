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
    }

    public function run(): void
    {
    }

    public function container(): Container
    {
        return $this->container;
    }
}