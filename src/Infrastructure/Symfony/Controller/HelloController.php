<?php

declare(strict_types=1);

namespace Infrastructure\Symfony\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class HelloController
{
    #[Route('/hello', name: 'api_hello', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        return new JsonResponse([
            'message'   => 'Ton template Symfony 8 + namespace personnalise marche a 100% !',
            'php'       => PHP_VERSION,
            'symfony'   => \Symfony\Component\HttpKernel\Kernel::VERSION,
            'datetime'  => (new \DateTimeImmutable())->format('c'),
        ]);
    }
}
