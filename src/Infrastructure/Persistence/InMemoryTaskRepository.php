<?php

declare(strict_types=1);

namespace Infrastructure\Persistence;

use Domain\Task;
use Domain\TaskRepository;

class InMemoryTaskRepository implements TaskRepository
{
    private array[string, Task] $tasks = [];

    public function save(Task $task): void
    {
        $this->tasks[$task->getId()] = $task;
    }

    public function findById(string $id): ?Task
    {
        return $this->tasks[$id] ?? null;
    }

    public function findAll(): array
    {
        return array_values($this->tasks);
    }
}
