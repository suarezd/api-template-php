<?php

declare(strict_types=1);

namespace Application\Query;

use Domain\Task;
use Domain\TaskRepository;

class GetTasksUseCase
{
    public function __construct(
        private readonly TaskRepository $taskRepository
    ) {
    }

    public function findAll(): array
    {
        return $this->taskRepository->findAll();
    }
}
