<?php

declare(strict_types=1);

namespace Infrastructure\Symfony\Controller;

use Application\Command\AddTaskUseCase;
use Application\Query\GetTasksUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class TaskController extends AbstractController
{
    public function __construct(
        private readonly AddTaskUseCase $addTaskUseCase,
        private readonly GetTasksUseCase $getTasksUseCase
    ) {
    }

    #[Route('/add-task', methods: ['POST'])]
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
                'task' => $task->toArray()
            ], 201);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Erreur serveur: ' . $e->getMessage()], 500);
        }
    }

    #[Route('/tasks', methods: ['GET'])]
    public function getTasks(Request $request): JsonResponse
    {
        try {
            return $this->json($this->getTasksUseCase->findAll(), 200);
        } catch (\Throwable $e) {
            return $this->json(['error' => 'Erreur serveur: ' . $e->getMessage()], 500);
        }
    }
}
