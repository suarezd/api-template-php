<?php

namespace App\Infrastructure\Symfony\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class HelloController
{
    #[Route('/api/hello', name: 'api_hello', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        return new JsonResponse([
            'message'   => '🎉 Ton template Symfony 8 + namespace personnalisé marche à 100 % !',
            'php'       => PHP_VERSION,
            'symfony'   => \Symfony\Component\HttpKernel\Kernel::VERSION,
            'datetime'  => (new \DateTimeImmutable())->format('c'),
        ]);
    }
}
