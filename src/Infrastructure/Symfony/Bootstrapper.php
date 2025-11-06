<?php
// src/Infrastructure/Symfony/Bootstrapper.php

namespace App\Infrastructure\Symfony\Infrastructure\Symfony;

use Symfony\Component\HttpFoundation\Request;

class Bootstrapper
{
    private Kernel $kernel;

    public static function create(): self { return new self(); }

    public function boot(): self
    {
        $this->kernel = new Kernel($_ENV['APP_ENV'] ?? 'dev', (bool)($_ENV['APP_DEBUG'] ?? true));
        $this->kernel->boot();
        return $this;
    }

    public function run(): void
    {
        $request = Request::createFromGlobals();
        $response = $this->kernel->handle($request);
        $response->send();
        $this->kernel->terminate($request, $response);
    }
}