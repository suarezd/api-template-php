<?php

declare(strict_types=1);

namespace Application\Command;

use Domain\Task;
use Domain\TaskRepository;

class AddTaskUseCase
{
    public function __construct(
        private readonly TaskRepository $taskRepository
    ) {
    }

    public function execute(string $title): Task
    {
        if (empty(trim($title))) {
            throw new \InvalidArgumentException('Le titre ne peut pas être vide');
        }

        $task = new Task($title);
        $this->taskRepository->save($task);

        return $task;
    }
}
