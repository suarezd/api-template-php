<?php

namespace App\Infrastructure\Symfony\Infrastructure\Symfony\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends AbstractController
{

    public function addTask(Request $request): JsonResponse
    {
        $data = $request->toArray();
        $title = $data['title'] ?? null;
        if (!$title) {
            return $this->json(['error' => 'Le champ "title" est requis'], 400);
        }

        try {
            $task = $this->addTaskUseCase->execute($title);
            return $this->json([
                'message' => 'Tâche créée',
                'task' => $task
            ], 201);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }
}