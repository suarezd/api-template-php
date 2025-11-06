<?php
namespace App\Infrastructure\Symfony\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ApiController extends AbstractController
{
    #[Route('/api/hello', methods: ['GET'])]
    public function hello(): JsonResponse
    {
        return $this->json([
            'message' => 'Symfony 8.0 RC API (Hexagonal)',
            'php' => phpversion(),
            'symfony' => \Symfony\Component\HttpKernel\Kernel::VERSION
        ]);
    }
}